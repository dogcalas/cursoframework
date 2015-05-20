<?php
	class PortalController extends IndexController 
	{
		public function indexAction()
		{
			//Se redirecciona hacia la pagina de inicio del portal
			header ("Location:../");
		}

		public function portalAction ()
		{
			$perfil = $this->getPerfil();
			if ($perfil['tema'])
			{
				$this->view->dir_ext_ccs = $this->view->dir_extjs.'temas/'.$perfil['tema'].'/';
				$dir_fich = str_replace('//','/',$_SERVER['DOCUMENT_ROOT'] . $this->view->dir_ext_ccs);
				if (file_exists($dir_fich))
					$this->render();
				else
					throw new ZendExt_Exception('EP003');
			}
			else
				throw new ZendExt_Exception('EP002');
		}
		
		public function cargarperfilAction ()
		{
			$perfil = $this->getPerfil();
			echo json_encode($perfil);
		}
		
		public function cargarsistemasAction ()
		{
			$idnodo = $this->_request->getPost('node');
			$modulo['menu'] = Modulo::getArrayModulo($idnodo);
			echo json_encode ($modulo);
		}

		public function cargarconceptosAction()
		{
			$perfil = $this->getPerfil();
			//Temporal
			header("Content-type:text/xml");
			echo file_get_contents("comun/recursos/xml/conceptos_{$perfil['idioma']}.xml");
		}
		
		public function cargarmodfuncAction()
		{
			$idnodo = $this->_request->getPost('node');
			$modulo = Modulo::getArrayModulo($idnodo);
			if (count($modulo)) {
				$modulos = array();
				foreach ($modulo as $mod) {
					$temp = $mod;
					if ($mod['icono'])
						$temp['icon'] = "geticonmenu?icon={$mod['icono']}";
					$modulos[] = $temp;
				}
			    echo json_encode ($modulos);
			}
			else
			{
				$funcionalidad = Modulo::getArrayFuncionalidad($idnodo);
				$funcionalidades = array();
				foreach ($funcionalidad as $func) {
					$temp = $func;
					if ($func['icono'])
						$temp['icon'] = "geticonmenu?icon={$func['icono']}";
					$funcionalidades[] = $temp;
				}
			    echo json_encode ($funcionalidades);
			}
		}
		
		public function cargardesktopAction()
		{
			$modulofunc = Modulo::getModulosPadres();
			if ($modulofunc->count())
			    $this->json_encode_desktop($modulofunc, 1);
		}

		public function json_encode_desktop($modulofunc, $padre)
		{
			echo '[';
			$ini = true;
			foreach ($modulofunc as $key=>$mdf)
			{
				if ($padre || $mdf instanceof Funcionalidad || (!$padre && $mdf->idmodulo != $mdf->idmodpadre))
				{
					if ($ini)
					{
						if ($mdf->icono)
							echo "{icono:'{$this->view->dir_ext_ccs}images/iconos/{$mdf->icono}.png'";
						else
							echo "{icono:'{$this->view->dir_ext_ccs}images/iconos/funcionalidad.png'";
						$ini = false;
					}
					else {
						if ($mdf->icono)
							echo ",{icono:'{$this->view->dir_ext_ccs}images/iconos/{$mdf->icono}.png'";
						else
							echo ",{icono:'{$this->view->dir_ext_ccs}images/iconos/funcionalidad.png'";
					}
					if ($mdf instanceof Funcionalidad)
					{
						echo ",id:'{$mdf->idfuncionalidad}'";
						echo ",text:'{$mdf->denominacion}'";
						if ($mdf->referencia)
							echo ",referencia:'..\/..\/..\/{$mdf->referencia}'";
						else
							echo ",referencia:''";
					}
					else
					{
						echo ",id:'{$mdf->idmodulo}'";
						echo ",text:'{$mdf->nombre}'";
						if ($mdf->Funcionalidad->count())
						{
							echo ",menu:";
							$this->json_encode_desktop($mdf->Funcionalidad, 0);
						}
						elseif ($mdf->Hijos->count())
						{
							echo ",menu:";
							$this->json_encode_desktop($mdf->Hijos, 0);
						}
						else
							echo ",menu:[]";
					}
					echo "}";
				}
			}
			echo ']';
		}
		
		public function cargardesktopmoduloAction()
		{
			$modulofunc = Modulo::getModulosPadres();
			if ($modulofunc->count())
			    $this->json_encode_desktopmodulo($modulofunc, 1);
		}
		
		public function json_encode_desktopmodulo($modulofunc, $padre)
		{
			echo '[';
			$ini = true;
			foreach ($modulofunc as $key=>$mdf)
			{
				if ($padre || (!$padre && $mdf->idmodulo != $mdf->idmodpadre))
				{
					if ($ini)
					{
						if ($mdf->icono)
							echo "{icono:'{$this->view->dir_ext_ccs}images/iconos/{$mdf->icono}.png'";
						else
							echo "{icono:'{$this->view->dir_ext_ccs}images/iconos/funcionalidad.png'";
						$ini = false;
					}
					else {
						if ($mdf->icono)
							echo ",{icono:'{$this->view->dir_ext_ccs}images/iconos/{$mdf->icono}.png'";
						else
							echo ",{icono:'{$this->view->dir_ext_ccs}images/iconos/funcionalidad.png'";
					}
					echo ",id:'{$mdf->idmodulo}'";
					echo ",text:'{$mdf->nombre}'";
					if ($mdf->Hijos->count() && !($mdf->Hijos->count() == 1 && $mdf->idmodulo == $mdf->idmodpadre))
					{
						echo ",menu:";
						$this->json_encode_desktopmodulo($mdf->Hijos, 0);
					}
					echo "}";
				}
			}
			echo ']';
		}
		
		public function closeportalAction()
		{
			$registro = Zend_Registry::getInstance();
			$registro->session->close = true;
			echo json_encode(array('close'=>true));
		}
		
		public function geticonmenuAction() {
			header('Content-type: image/gif');
			$img	= imagecreatefromgif("{$this->view->dir_ext_ccs}images/iconos/{$_GET['icon']}.png");
   	 		imagegif($img);
		}
		
		public function cargardatostabpanelAction()
		{
			$idestructura = Zend_Registry::get('session')->idestructura;
			$entidadconfigurada = $this->integrator->parametros->EntidadConfigurada($idestructura);
			if ($entidadconfigurada === true)
				echo "{\"success\": {$idestructura}}";
			else {
				header("Content-type:text/xml");
				echo file_get_contents("comun/recursos/xml/carga_inicial.xml");
			}
			//erp/aplicaciones/portal/comun/recursos/xml/carga_inicial.xml
			/*$xml = ZendExt_FastResponse :: getXML ('carga_inicial');
			echo xml_encode($xml);*/
		}
		
		public function dameserviciocargainicialAction()
		{
			$idServ = $_GET['id'];
			if($idServ)
			{
				switch ($idServ)
				{
					case 1: $this->integrator->parametros->Bienvenida();
					break;
					case 2: $this->integrator->parametros->Ejercicio(); 
					break;
					case 3: $this->integrator->parametros->Fecha(); 
					break;
					case 4: $this->integrator->parametros->Cierre(); 
					break;
					case 5: $this->integrator->parametros->MonedaC(); 
					break;
					case 6: $this->integrator->parametros->MonedaE(); 
					break;
					case 7: $this->integrator->parametros->Finalizar();
					break;
				}
			}
		}
	}
