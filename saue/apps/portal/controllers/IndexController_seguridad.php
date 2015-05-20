<?php
	class IndexController extends ZendExt_Controller_Secure 
	{
		public function init ()
		{
			$this->model = new PortalModel();
			parent::init ();
		}
		
		public function cargardominioAction()
		{
			$idestructura = $this->_request->getPost('node');
			if ($idestructura)
				$estructuras = $this->integrator->metadatos->DameHijosEstructura($idestructura);
			else
				$estructuras = $this->integrator->metadatos->DameHijosEstructura(0);
			$newEstructuras = array();
			foreach ($estructuras as $estructura) {
				$temp = $estructura;
				unset($temp->icon);
				$newEstructuras[] = $temp;
			}
			echo json_encode($newEstructuras);
		}
		
		public function entraralsistemaAction()
		{
			$session = Zend_Registry::get('session');
			$identidad = $this->_request->getPost('dominio');
			if ($this->_request->getPost('tipoacceso') == 'nuevo' && !$session->close) {
				//Se limpian todas las variables de session.
				$session->idestructura = $identidad;
				$session->close = true;
				header('Location: entraralsistema');
			}
			else {
				if (!$identidad)
					$identidad = $session->idestructura;
				$_SERVER['HTTP_X_REQUESTED_WITH'] = 1;
				if (!isset($session->entidad) || $identidad != $session->entidad->idestructura) {
					$integrator = ZendExt_IoC::getInstance();
					$estructura = $integrator->metadatos->DameEstructura($identidad);
					$session->entidad = $estructura[0];
					$session->idestructura = $identidad;
					//$session->entidad->idestructura = 2; //TEMPORAL
				}
				echo json_encode(array('codMsg'=>1, 'Entidad'=>$session->idestructura));
			}
		}

		protected function getUsuario()
		{
			$registro = Zend_Registry::getInstance();
			$alias = $registro->session->usuario;
			$usuario = $this->model->buscarUsuarioByAlias($alias);
			if($usuario)
			{
				$usrArray = $usuario->toArray();
				$registro->session->perfil = $usrArray[0];
			    return $usrArray[0];
			}
			else
				throw new ZendExt_Exception('EP001');
		}
		
		public function indexAction ()
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

		public function closeportalAction()
		{
			$registro = Zend_Registry::getInstance();
			$registro->session->close = true;
			echo json_encode(array('close'=>true));
		}
	}
