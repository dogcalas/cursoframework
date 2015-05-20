<?php
/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garca Tejo
 * @author Julio Cesar Garca Mosquera  
 * @version 1.0-0
 */
class GestseguridadController extends ZendExt_Controller_Secure
	{
	 function init ()
	    {
			parent::init();
		}
        
    function devolverIp()
    {
        if ($_SERVER) 
        { 
            if($_SERVER['HTTP_X_FORWARDED_FOR'])
                $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else{
                if($_SERVER['HTTP_CLIENT_IP']) 
                     $realip = $_SERVER['HTTP_CLIENT_IP']; 
                    else 
                     $realip = $_SERVER['REMOTE_ADDR'];  
                }
        } 
        else{ 
            if ( getenv( "HTTP_X_FORWARDED_FOR" ) ) 
                $realip = getenv( "HTTP_X_FORWARDED_FOR" ); 
            elseif ( getenv( "HTTP_CLIENT_IP" ) ) 
                $realip = getenv( "HTTP_CLIENT_IP" ); 
            else 
             $realip = getenv( "REMOTE_ADDR" ); 
             }
        return $realip;
    }
    
    function verificarIP($ip,$arraybd)
    {
    $arraybd = explode(',',$arraybd);
    $ip = explode('/',$ip);    
     foreach($arraybd as $key=>$array)
       $result[$key] = explode('/',$array);
     foreach($result as $rest) 
       {
            if($this->compreobarIP($ip,$rest,$rest[1]))
            return true;
       }
     return false;
    }
    
    function compreobarIP($valor1,$valor2,$opc)
    {
        $result1 = explode('.',$valor1[0]);
        $result2 = explode('.',$valor2[0]);
     if($opc == '0')
        return 1;
     elseif($opc == '8')
     {  
      if( ($result1[0] == $result2[0]) )
        return 1;
     }
     elseif($opc == '16')
     {
      if( ($result1[0] == $result2[0]) && ($result1[1] == $result2[1]) )
        return 1;
     }
     elseif($opc == '24')
     {
      if( ($result1[0] == $result2[0]) && ($result1[1] == $result2[1]) && ($result1[2] == $result2[2]))
        return 1;
     }
     elseif($opc == '32')
     {
    if( ($result1[0] == $result2[0]) && ($result1[1] == $result2[1]) && ($result1[2] == $result2[2]) && ($result1[3] == $result2[3]))
        return 1; 
     }
     else
     {
    if( ($result1[0] == $result2[0]) && ($result1[1] == $result2[1]) && ($result1[2] == $result2[2]) && ($result1[3] == $result2[3]))
        return 1; 
     }
     return 0;
    }
    		
     //////-----------funcion para la autenticacion del usuario con ldap
     function autenticarAction() 
    {
        $mac=1;
        $user = 'costosprocesos';
        $password = '80a59e8a32b1575ec6c391161d702db6';
        $verificar = SegUsuario::verificarpass($user);
        if($verificar[0]->ip)
            {  
            $ip = $this->devolverIp(); 
            $acceso = $this->verificarIP($ip,$verificar[0]->ip);
            }
        elseif($verificar[0]->idusuario) 
                $acceso = true;
            else
                $acceso = false; 
        if($acceso)
        {   
        $observ = SegUsuarioDatSerautenticacion::cantservidoraut($verificar[0]->idusuario);
        if(count($observ))
        { 
          $objldap = DatServidor::usuarioservidoraut($observ[0]->idservidor);
          $ldap = $objldap[0]->denominacion;
          $servidor = explode('.',$ldap);
          $options = array('server' => array('host'=>$ldap,'accountDomainName'=> $ldap,'baseDn'=>"OU=$servidor[0] Domain Users,DC=$servidor[0],DC=$servidor[1]"));
          $auth = Zend_Auth::getInstance();
          $adapter = new Zend_Auth_Adapter_Ldap($options, $user, $password);
          $result = $auth->authenticate($adapter);
          $array = $result->getMessages();                  
          if(!$array[0])
              {      
               if($verificar[0]->contrasenna != md5($password))
                   {
                    $objusurio = Doctrine::getTable('SegUsuario')->find($verificar[0]->idusuario);
                    $objusurio->contrasenna = md5($password);
                    $objusurio->save();
                    $result = $this->returnCertificate($mac,$verificar[0]->idusuario);
                    return $result;
                   }
               else
                   {                      
                    $result = $this->returnCertificate($mac,$verificar[0]->idusuario);
                    echo'<pre>';print_r($result);die;
                   }
              }
          else
                return 0;
        }
        else
        {   
            $objUsuario = new SegUsuario();
            $resultUsuario = $objUsuario->comprobarusuario($user,$password);
            if(count($resultUsuario))
                {
                $result = $this->returnCertificate($mac,$resultUsuario[0]['idusuario']);
                echo'<pre>';print_r($result);die;
                }
            else return 0;
        }
        }
        echo'<pre>';print_r('no puedes'); 
}
    //////-----------funcion que retorna el certificado del usuario logeado
     function returnCertificate($mac, $idusuario)
    { 
            $objCertificado = new SegCertificado();
            $resultCertificado = $objCertificado->existecertificado($idusuario);
             if(count($resultCertificado))
                $nuevocertificado = $this->updateCertificate($mac,$resultCertificado[0]['idcertificado'],$idusuario);
            else 
            {
                $modelcertificado= new SegCertificadoModel();
                $objCertificado->mac = $mac;
                $objCertificado->valor = $this->createCertificate($idusuario);                      
                $objCertificado->idusuario = $idusuario;
                $nuevocertificado = $objCertificado->valor;                    
                $modelcertificado->insertarcertificado($objCertificado);                        
            }                    
             return $nuevocertificado;
        }		

    //////-----------funcion para crear certificados  
	function createCertificate($iduser)
        {
        //$numero = rand(1, 100000000000000);
        return 1 . $iduser /*+ $numero*/;
        }
	//////-----------funcion para modificar certificados
	function updateCertificate($mac, $idcertificate, $iduser)
        {
            $objCertificado = Doctrine::getTable('SegCertificado')->find($idcertificate);
            $objCertificado->mac = $mac;
            $objCertificado->valor = $this->createCertificate($iduser); 
            $nuevocertificado = $objCertificado->valor;
            $model = new SegCertificadoModel();
            $model->modificarcertificado($objCertificado);
            return $nuevocertificado;
        }
	//////-----------carga el dominio de un usuario y sus estructuras
	function cargardominioAction() 
		{
			$certificado = '110000000039';
            $idEstructura = 100000097;
            $tipo = 'interna';

					$objcertificado = new SegCertificado();				
					$datos = $objcertificado->verificarcertificado($certificado);					
					$idusuario = $datos[0]->idusuario;
					if($idusuario)
					{
                        $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
                        $arrayEntidades = SegDominio::obtenerCadenaEntidades($iddominio[0]->iddominio);
                        $arrayId = $this->cargarEntidadesDadoDominio($arrayEntidades,$idEstructura);
                         if(!count($arrayId) && $tipo =='interna')
                         {    
                          $arrayEstructuras = $integrator->metadatos->BuscarCargosPorTiposSeguridad($idEstructura);                                      echo "<pre>";print_r($arrayEstructuras);die();
                         }
                         elseif($tipo =='cargo')
                         { 
						  $usuarios = SegUsuario::usuariosdelcargo($idEstructura);
                          $arrayEstructuras = $this->arrayUsuariosPerfil($usuarios);
                         }
                         else
                         {
                                $arrayEstructuras = $this->integrator->metadatos->ListarEstructurasDadoArrayId($arrayId);
                                $arrayEstructuras = $this->ponerComoHoja($arrayEstructuras);
                         }

                        echo json_encode($arrayEstructuras);return;    						
					}

        }

    //////-----------cargar las entidades dado un dominio///////////
    function cargarEntidadesDadoDominio($arrayEntidades, $idEntidad)
    {
        return $this->buscarIdCargar($arrayEntidades, $idEntidad);
    }
    //////-----------buscar los id de las entidades a cargar///
    function buscarIdCargar($array, $idEntidad=0)
    {
        $resultado = array();
        if(!$idEntidad)
        {
            foreach($array as $valor)
            {
                $temp = explode("-",$valor);
                if(!$this->existeValorArray($resultado, substr($temp[0],0, strlen($temp[0])-2)))
                    $resultado[] = substr($temp[0],0, strlen($temp[0])-2);        
            }    
        }
        else
        {
            foreach($array as $valor)
            {    
                $temp = explode("-",$valor);
                $res = $this->existeValorArrayNoUltimo($temp,$idEntidad);
                if($res != -1)
                {    
                    if(!$this->existeValorArray($resultado, substr($res,0,strlen($res)-2)))
                        $resultado[] = substr($res,0,strlen($res)-2);        
                }
            }    
        }
        return $resultado; 
    }
    //////-----------verifica si un valor esta en un arreglo//////
    function existeValorArray($array, $valor)
    {
        foreach($array as $valor1)
            if($valor1 == $valor)
                return true;    
        return false;    
    }
    //////-----------verifica si una entidad es la ultima de un arbol///////
    function existeValorArrayNoUltimo($array, $valor)
    {
        for($i=0; $i<count($array)-1; $i++)
            if(substr($array[$i],0,strlen($array[$i])-2) == $valor)
                return $array[$i+1]; 
        return -1;    
    } 
    //////-----------pone como hojas las entidades que no tienen hijos
    function ponerComoHoja($array)
    {
        $n = count($array);
        for($i=0; $i<$n; $i++)
        {
            if($array[$i]['rgt'] - $array[$i]['lft'] == 1)
            {
                if($array[$i]['rgt'] - $array[$i]['lft'] == 1)
                {
                    //$arrayEstructuras1 = $this->integrator->metadatos->DameEstructurasInternasSeguridad($array[$i]['idestructura'],true);
                   // if(!count($arrayEstructuras1))
                        $array[$i]['leaf'] = true;
                }      
            }
        }
       return $array;  
    }
	//////-----------carga todos los sistemas de una entidad
	function cargarsistemasdadoentidadAction() 
	{
		try 
			{
			$certificado = '110000000039';//$this->_request->getPost('certificado');
            $identidad = '100000030';//$this->_request->getPost('identidad');
			$objcertificado = new SegCertificado();
			$datos = $objcertificado->verificarcertificado($certificado);
			$idcertificado = $datos[0]->idcertificado;	
			$idusuario = $datos[0]->idusuario;
			if(count($datos))
			{					
				$rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identidad);
                $sistemaObj = DatSistema::obtenersistemas($rolusuarioentidad[0]->idrol)->toArray(true);
                $sistemaArr = array();
                foreach ($sistemaObj as $key => $sistema) {
                    $sistemaArr[$key]['id'] = $sistema->idsistema;
                    $sistemaArr[$key]['icono'] = ($sistema->icono) ? $sistema->icono : 'falta';
                    $sistemaArr[$key]['titulo'] = $sistema->denominacion;
                    $sistemaArr[$key]['externa'] = $sistema->externa; 
                }
				echo json_encode($sistemaArr);	
			}
			}
			catch (ZendExt_Exception $ee)
			{
				throw $ee;
			}			
	}
	//////-----------Cargar sistemas, susbsistemas y funcionalidades de una entidad a las que un usuario tiene acceso
	function cargarsistemafuncionalidadesAction() 
	{
	try{
		$certificado = '110000000039';//$this->_request->getPost('certificado');
		$idsistema = '10000000084';//$this->_request->getPost('idsistema');
        $identidad = '100000030';
		$objcertificado = new SegCertificado();
		$datos = $objcertificado->verificarcertificado($certificado);
		$idusuario = $datos[0]->idusuario;
		$rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identidad);
        $idrol = $rolusuarioentidad[0]->idrol;
        $acceso = DatSistema::verificarrolsistema($idrol,$idsistema);
		if(count($acceso))
		{     
			if($idsistema)
				{					
				$sistemas = DatSistema::cargarsistema($idsistema);
				if(count($sistemas))
					    {
					     foreach ($sistemas as $valores=>$valor)
						    {
						    $sistemaArr[$valores]['id'] = $valor->id._.$idsistema;
						    $sistemaArr[$valores]['idsistema'] = $valor->id;
						    $sistemaArr[$valores]['text'] = $valor->text;
                            $sistemaArr[$valores]['externa'] = $valor->externa;						
						    }					 
					    echo json_encode($sistemaArr);						
			            }								
				 else
				        {     
					    $funcionalidad = DatFuncionalidad::cargarfuncsistemarol($idsistema,$idrol);
					    if(count($funcionalidad))
						    {
						    foreach ($funcionalidad as $valores=>$valor)
							    {
							    $funcionalidadArr[$valores]['id'] = $valor->id._.$idnodo;
							    $funcionalidadArr[$valores]['idfuncionalidad'] = $valor->id;
							    $funcionalidadArr[$valores]['text'] = $valor->text;
                                $funcionalidadArr[$valores]['referencia'] = '../../../'.$valor->referencia;
                                $funcionalidadArr[$valores]['icono'] = $valor->icono;
							    $funcionalidadArr[$valores]['leaf'] = true;
							    }
						    echo json_encode ($funcionalidadArr);
				            }
					     else
						    {
						    $func=$funcionalidad->toArray();
					        echo json_encode($func);
						    } 
					    }
				}
		}
		else		
		die("{'codMsg':3,'mensaje': 'No tiene acceso al sistema solisitado.'}");	    
	}		
	catch (Doctrine_Exception $ee)
	    {
	    throw new ZendExt_Exception('CONT001',$ee);
	    } 
	}
	//////-----------carga todas las acciones de un usuario
	function accionesquetieneAction()
	{
	try {
		$certificado = '110000000039';//$this->_request->getPost('certificado');
        $identidad = '100000030';//$this->_request->getPost('identidad');
        $idfuncionalidad = '10000000133';//$this->_request->getPost('idfuncionalidad');
        $idsistema = '10000000084';
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificado);
        $idcertificado = $datos[0]->idcertificado;    
        $idusuario = $datos[0]->idusuario;    
        $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identidad);
		$accionesquetiene = DatAccion::cargaracciones($idsistema,$idfuncionalidad,$rolusuarioentidad[0]->idrol);
		foreach ($accionesquetiene as $valores=>$valor)
		{
			$arracciones[$valores]['idaccion']= $valor->idaccion;
			$arracciones[$valores]['denominacion']= $valor->denominacion;
		}
		echo json_encode($arracciones);
		}
	catch (Doctrine_Exception $ee)
	    {
	    throw new ZendExt_Exception('CONT001',$ee);
	    } 		
	}	
	//////-----------carga el perfil de un usuario dado el certificado
	function cargarperfilAction() 
	{
            $certificado = '11000000001';
            $entidad = '100000004';
			$objcertificado = new SegCertificado();
			$datos = $objcertificado->verificarcertificado($certificado);
			$idusuario = $datos[0]->idusuario;
			$perfil = SegUsuario::cargarperfilusuario($idusuario);
			$perfilusuario=array();
			if(count($perfil))
				{		
				$perfilusuario['tema'] = $perfil[0]['NomTema']['tema'];
				$perfilusuario['idioma'] = $perfil[0]['NomIdioma']['idioma'];
				$perfilusuario['portal'] = $perfil[0]['NomDesktop']['desktop'];
                $perfilusuario['idusuario'] = $perfil[0]['idusuario'];
				$perfilusuario['usuario'] = $perfil[0]['nombreusuario'];
                $perfilusuario['identidad'] = $perfil[0]['identidad'];
                $perfilusuario['idarea'] = $perfil[0]['idarea'];
                $perfilusuario['idcargo'] = $perfil[0]['idcargo'];
                $roles = SegRol::obtenerrolesusuarioentidad($idusuario,$entidad);
                foreach($roles as $key=>$rol)
                {                                        
                 
                     $arrayroles[$key]['identidad'] = $rol['identidad'];
                     $arrayroles[$key]['idrol'] = $rol['idrol']; 
                     $arrayroles[$key]['rol'] = $rol['denominacion'];
                }
                 $perfilusuario['roles'] = $arrayroles; 
                $arrayEstructuras = $this->integrator->metadatos->MostrarCamposEstructuraSeguridad($perfil[0]['identidad']);
                $perfilusuario['entidad'] = $arrayEstructuras[0]['text']; 
                if($perfil[0]['idarea'] != 0)
                    {
                    $area = $this->integrator->metadatos->EstructurasInternasDadoIDSeguridad($perfil[0]['idarea']);
                    $perfilusuario['area'] = $area[0]['text'];
                    }
                if($perfil[0]['idcargo'] != 0) 
                    {     
                    $cargo = $this->integrator->metadatos->CargoDadoIDSeguridad($perfil[0]['idcargo']);
                    $perfilusuario['cargo']=$cargo[0]['denominacion'];
                    }
                
				if (is_array($perfil[0]['NomFila']['NomValor']))  
				foreach ($perfil[0]['NomFila']['NomValor'] as $valor) 
				{ 				
					if($valor['idfila'])
					{	               	 
	                    $arraycampos= NomValor::cargarcamposdadovalores($valor['idvalor']);
	                    $perfilusuario[$arraycampos[0]['NomCampo']['nombre']] =$valor['valor'];	                   
					}  		
				}			
				}				
			echo'<pre>';print_r($perfilusuario);	
	}
    //////-----------Cargar todos los sistemas y su configuracion de servidor a los cuales tiene acceso un usuario en una entidad
	function cargartododesistemasAction() 
	{
	try{
		$certificado = '110000000039';
        $identidad = '100000030';
			$objcertificado = new SegCertificado();
			$datos = $objcertificado->verificarcertificado($certificado);	
			$idusuario = $datos[0]->idusuario;
			if(count($datos))
			{				
				 $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identidad);
                 $sistemas = DatSistema::obtenersistemas($rolusuarioentidad[0]->idrol)->toArray(true);
				if(count($sistemas))
				{
					foreach ($sistemas as $mdf)
					{
						$resultado[] = $this->subsistemas($mdf,$rolusuarioentidad[0]->idrol);		
					}	
					echo '<pre>'; print_r($resultado);
				}				
			}   
	}		
	catch (Doctrine_Exception $ee)
	    {
            throw new ZendExt_Exception('CONT001',$ee);
	    } 
	}
	//////-----------funcion recursiva que carga la configuracion de un sistema
	function subsistemas($raiz,$idrol) 
	{            
		$result = array('icono'=>$raiz['icono'], 'id'=>$raiz['idsistema'], 'text'=>$raiz['denominacion'],'externa'=>$raiz['externa'], 'funcionalidades'=> $this->funcionalidades($raiz['idsistema'],$idrol),'servidores'=> $this->servidores($raiz['idsistema']));
        $sistemashijos = DatSistema::cargarsistemahijos($raiz['idsistema'],$idrol);
        if(count($sistemashijos))
		{ 
            foreach ($sistemashijos as $hijo) 
			{
				if($raiz['idsistema'] != $hijo['idsistema'])
				{
					$result['menu'][] = $this->subsistemas($hijo,$idrol);
				}		
			}
			return $result; 
		}
		else 
			return $result; 
	}
    //////-----------funcion que verifica el acceso de un usuario a una entidad
	function verifyaccessAction ()
    {
	$certificado = '110000000010';
	$identidad = 2;
	$objcertificado = new SegCertificado();
	$datos = $objcertificado->verificarcertificado($certificado);
	$idusuario = $datos[0]->idusuario;
	$rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identidad);
	if ($rolusuarioentidad->count())
		echo 'true';//return true;
	else echo 'false';
	//return false;			
	}
    //////-----------Cargar xml
    function cargarxmlAction() 
    {
    try{
        $certificado = '110000000039';
        $identidad = '100000030';
            $objcertificado = new SegCertificado();
            $datos = $objcertificado->verificarcertificado($certificado);    
            $idusuario = $datos[0]->idusuario;
            if(count($idusuario))
            {                
                 $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identidad);
                 $sistemas = DatSistema::obtenersistemas($rolusuarioentidad[0]->idrol)->toArray(true);
                if(count($sistemas))
                {   
                    $menu = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><menu>";
                    foreach ($sistemas as $mdf)
                    {
                        $this->subsistemasxml($mdf,$rolusuarioentidad[0]->idrol,$menu);        
                    }    
                    $menu .= "</menu>"; 
                    header('Content-Type: text/xml');
                    echo $menu;
                    return $menu;
                }                
            }
            return '';   
    }        
    catch (Doctrine_Exception $ee)
        {
            throw new ZendExt_Exception('CONT001',$ee);
        } 
    }
    //////-----------Cargar xml para un modulo especifico
    function cargarxmlmoduloAction() 
    {
    try{
        $certificado = '110000000039';
        $identidad = '100000030';
        $modulo = 'lolo';
      
            $objcertificado = new SegCertificado();
            $datos = $objcertificado->verificarcertificado($certificado);    
            $idusuario = $datos[0]->idusuario;
            if(count($idusuario))
            {                
                 $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identidad);
                 $sistemas = DatSistema::obtenersistemasxml($rolusuarioentidad[0]->idrol,$modulo)->toArray(true);
                if(count($sistemas))
                {   
                    $menu = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><menu>";
                    foreach ($sistemas as $mdf)
                    {
                        $this->subsistemasxml($mdf,$rolusuarioentidad[0]->idrol,$menu);        
                    }    
                    $menu .= "</menu>";
                    header('Content-Type: text/xml');
                    echo $menu;
                    return $menu;
                }                
            }
            return '';   
    }        
    catch (Doctrine_Exception $ee)
        {
            throw new ZendExt_Exception('CONT001',$ee);
        } 
    }
    //////-----------funcion recursiva para cargar los hijos en el xml
    function subsistemasxml($raiz,$idrol,& $menu) 
    {   
        $menu .= "<MenuItem name=\"{$raiz['denominacion']}\" id=\"{$raiz['idsistema']}\"  icon=\"{$raiz['icono']}\"  status=\"{$raiz['descripcion']}\">" ;
        $this->funcionalidadesxml($raiz['idsistema'],$idrol, $menu);
        $sistemashijos = DatSistema::cargarsistemahijos($raiz['idsistema'],$idrol);
        if(count($sistemashijos))
        { 
            foreach ($sistemashijos as $hijo) 
                if($raiz['idsistema'] != $hijo['idsistema'])
                     $this->subsistemasxml($hijo,$idrol,$menu);
                     
        }
        $menu .= "</MenuItem>";
    }
    //////-----------funcion recursiva para cargar funcionalidades en el xml 
    function funcionalidadesxml($idsistema,$idrol, & $menu) 
    {
     $funcsistema = DatFuncionalidad::obtenerFuncionalidades($idsistema,$idrol);
     if(count($funcsistema))
       foreach($funcsistema as $funcionalidades)
         $menu .= "<MenuItem name=\"{$funcionalidades->text}\" id=\"{$funcionalidades->idfuncionalidad}\"  src=\"{$funcionalidades->referencia}\" icon=\"{$funcionalidades->icono}\"  status=\"{$funcionalidades->descripcion}\"/>" ;
    }
    //////-----------
    function getSystemsFunctionsDesktopModulesAction()
    {
        $certificate = '11000000001';
        $identidad = '100000001';
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate); 
        if(count($datos))
        {          
            $idusuario = $datos[0]->idusuario; 
            $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario, $identidad);
            $sistemas = DatSistema::obtenersistemas($rolusuarioentidad[0]->idrol)->toArray();
            if(count($sistemas))
            {
                foreach ($sistemas as $mdf)
                    $resultado[] = $this->subsistemasfunc($mdf,$rolusuarioentidad[0]->idrol);        
                echo '<pre>'; print_r($resultado);
            }                
            else return array();
        }
        else        
            throw new ZendExt_Exception('SGIS003');
    } 
    //////----------- funcion recursiva
    function subsistemasfunc($raiz,$idrol)
    {
    $result = array('icono'=>$raiz['icono'], 'id'=>$raiz['idsistema'], 'text'=>$raiz['denominacion'],'externa'=>$raiz['externa'], 'menu'=> $this->funcionalidades($raiz['idsistema'],$idrol),'servidores'=> $this->servidores($raiz['idsistema']));
    $sistemashijos = DatSistema::cargarsistemahijos($raiz['idsistema'],$idrol);
    if(count($sistemashijos))
    { 
    foreach ($sistemashijos as $hijo) 
    {
        if($raiz['idsistema'] != $hijo['idsistema'])
        {
        $result['menu'][] = $this->subsistemasfunc($hijo,$idrol);
        }        
    }
    return $result; 
    }
    else 
    return $result; 
    }
    //////----------- para cargar la configuracion de los servidores
    function servidores($idsistema)
    {
        $obj = new DatSistemaDatServidores();
        $esquemassistema = $obj->obtenersistemacompleto($idsistema);                            
        foreach ($esquemassistema as $valor1)
        {
            $arrayresult['idsistema'] = $valor1['idsistema'];
            $arrayresult['denominacion'] = $valor1['denominacion'];                        
            $arrayresult['servidor'] = $valor1['DatServidor'][0]['denominacion'];                                    
            $arrayresult['gestor'] = $valor1['DatGestor'][0]['gestor'];
            foreach($valor1['DatBd'] as $bd)
            {    
                $arraybd[]=$bd['denominacion'];
                foreach($valor1['DatEsquema'] as $esquemas)
                {
                    $arrayesquemas[]= $esquemas['denominacion'];            
                }
                $arrayresult['esquemas']=$arrayesquemas;
            }
            $arrayresult['bd']=$arraybd;                                    
        }
        return $arrayresult; 
    }
    //////----------- para cargar las funcionalidades
    function funcionalidades($idsistema,$idrol)
    {
     $funcsistema = DatFuncionalidad::obtenerFuncionalidades($idsistema,$idrol);
     $arrayFuncionalidades = array();
     foreach($funcsistema as $key=>$funcionalidades)
         {
          $arrayFuncionalidades[$key]['text'] = $funcionalidades->text; 
          $arrayFuncionalidades[$key]['id'] = $funcionalidades->idfuncionalidad;
          $arrayFuncionalidades[$key]['referencia'] = '../../../' . $funcionalidades->referencia;
         }
     return $arrayFuncionalidades;
    }
    //////----------- Esta funcion es para debolver el usuario y todo su perfil
    function arrayUsuariosPerfil($arrayusers)
    {
        
         foreach($arrayusers as $key=>$usuarios)
         {
             $perfil = SegUsuario::cargarperfilusuario($usuarios['idusuario']);
             if (is_array($perfil[0]['NomFila']['NomValor']))
             foreach ($perfil[0]['NomFila']['NomValor'] as $valor) 
                if($valor['idfila'])
                {                        
                    $arraycampos= NomValor::cargarcamposdadovalores($valor['idvalor']);
                    $arrayreturn[$key][$arraycampos[0]['NomCampo']['nombre']] =$valor['valor'];                       
                }          
             $arrayreturn[$key]['idusuario']= $usuarios['idusuario'];
             $arrayreturn[$key]['usuario']= $usuarios['nombreusuario']; 
         }
         return $arrayreturn; 
    }
    //////-----------Esta funcion es para debolver la entidad, el area y cargo de un usuario
    function devolverUsersEntidadAction()
    {
         $arrayusers = array('10000000010','10000000024','10000000026','10000000028');
         foreach($arrayusers as $key=>$usuarios)
         {
             $datos = SegUsuario::cargarentidadesareascargos($usuarios);
             $arrayEstructuras = $this->integrator->metadatos->MostrarCamposEstructuraSeguridad($datos[0]['identidad']);
                    $arrayreturn[$key]['entidad'] = $arrayEstructuras[0]['text'];
                    $arrayreturn[$key]['identidad'] = $datos[0]['identidad']; 
                    if($datos[0]['idarea'] != 0)
                        {
                        $area = $this->integrator->metadatos->EstructurasInternasDadoIDSeguridad($datos[0]['idarea']);
                        $arrayreturn[$key]['area'] = $area[0]['denominacion'];
                        $arrayreturn[$key]['idarea'] = $datos[0]['idarea'];
                        }
                    if($datos[0]['idcargo'] != 0) 
                        {     
                        $cargo = $this->integrator->metadatos->CargoDadoIDSeguridad($datos[0]['idcargo']);
                        $arrayreturn[$key]['cargo'] = $cargo[0]['denominacion'];
                        $arrayreturn[$key]['idcargo'] = $datos[0]['idcargo'];
                        }
             $arrayreturn[$key]['idusuario'] = $usuarios;
         }
         echo json_encode($arrayreturn);  
        } 
    //////-----------funcion que me debulve las acciones de un usuario dada una funcionalidad y una entidad.  
    function cargarAccionesUsuarioAction()
    { 
            $certificado = '11000000001';
            $identidad = '100000001';
            $idfuncionalidad = '10000000008';
            $objcertificado = new SegCertificado();
            $datos = $objcertificado->verificarcertificado($certificado);
            
            $idcertificado = $datos[0]->idcertificado;    
            $idusuario = $datos[0]->idusuario;
            $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identidad);
            $accionesquetiene = DatAccion::cargaraccionesservice($idfuncionalidad,$rolusuarioentidad[0]->idrol);
            
            
            if(count($accionesquetiene))
            {
            	$direccion ='Location: http://'.$_SERVER['HTTP_HOST'].'/'.'report_generator.php/api/getReportsCatalog?id=';
	            foreach ($accionesquetiene as $valores=>$valor)
	            {
	            
	            $arracciones[$valores]['idaccion']= $valor->idaccion;
	            $arracciones[$valores]['abreviatura']= $valor->abreviatura;	            	
	            $reportes = DatAccionDatReporte::cargaraccionesasociadasrep($valor->idaccion);
	                   
	            if(count($reportes))
	            {
	            	foreach ($reportes as $rep=>$valorrep)	            	
		            {
		            	$arrayrep[$rep]['url'] = $direccion.$valorrep['idreporte'];
		            }
	            }
	            $arracciones[$valores]['reportes']=$arrayrep;	
	            

	            }
	             echo"<pre>";print_r($arracciones);die;	    
            	echo $arracciones;
            }
            else
                echo $arracciones = array(); 
        } 
    //////-----------funcion que me debulve todos los roles de un usuario dado un certificado.
    function rolesdelusuarioAction()
    {
         $certificate = '110000000039';
         $objcertificado = new SegCertificado();
         $datos = $objcertificado->verificarcertificado($certificate);
         $idcertificado = $datos[0]->idcertificado;    
         $idusuario = $datos[0]->idusuario;
         $rolesuser = SegRol::obtenerrolesusuario($idusuario)->toArray();
         foreach($rolesuser as $key=>$rol)
         {
            $arrayresult[$key]['text'] = $rol['text'];
            $arrayresult[$key]['denominacion'] = $rol['text'];
            $arrayresult[$key]['id'] = $rol['id'];
            $arrayresult[$key]['idrol'] = $rol['id'];
            $arrayresult[$key]['abreviatura'] = $rol['abreviatura']; 
         }
         echo'<pre>';print_r($arrayresult);die(); 
      }     
    //////-----------dado una entidad si tiene roles o usuarios asignados.
    function existenusuariorolesAction()
    {
         if(SegUsuario::existealgunusuario() > '0')
            echo 'true';
         else
            echo 'false';
    }   
    //////-----------servicio para cambiar el estado de un usuario.
    function cambiarestadoAction()
    {
            $idusuario = '10000000041';
            $opcion = '0';
            if($opcion == '0')
                 {
                     $obj = Doctrine::getTable('SegUsuario')->find($idusuario);
                     $obj->estado = '0';
                     $obj->save();
                     die('en talla');
                 }
             elseif($opcion == '1')
                 {
                     $obj = Doctrine::getTable('SegUsuario')->find($idusuario);
                     $obj->estado = '1';
                     $obj->save();
                     die('en talla');
                 }
             echo 'fula';
        }
    //////-----------servicio para cambiar la contrasena de un usuario.
    function ChangePassword($usuario,$oldpass,$newpass)
    {
     $verificar = SegUsuario::verificarpass($usuario);
     if($verificar[0]->contrasenna == $oldpass)
     {
     if($this->verificarpass($newpass))
         {
          $objusuario = Doctrine::getTable('SegUsuario')->find($verificar[0]->idusuario);
          $objusuario->contrasenna = $newpass;
          $objusuario->contrasenabd =  $newpass;
          $objusuario->save();
          return 1;
         }
     }
     else
     return 0;
    } 
        
    function verificarpass($pass)
    {
            $s = '/^([a-zA-Z])+$/';
            $sn = '/^[\da-zA-Z]+$/';
            $nn = '/[\d]/';
            $nl = '/[\!\\\\!\@\#\$\%\^\&\*\(\)\_\+\=\{\}\[\]\:\"\;\'\?\/\>\.\<\,\\\*\-]+$/';
            $clave = new SegRestricclaveacceso();
            $datos = $clave->cargarclave(0,0);
            $resultados = array();
            $results = array();
            if($datos->getData() == null)
                throw new ZendExt_Exception('SEG001');
            $datosacc=$datos->toArray(true);
            if(strlen($pass)< $datosacc[0]['minimocaracteres'])
            {
                $long=$datosacc[0]['minimocaracteres'];
                echo("{'codMsg':3,'mensaje':'La clave debe tener almenos'+' '+'$long'+' '+'caracteres.'}");
                return false;
            }
            if($datosacc[0]['numerica'] == 1 && $datosacc[0]['alfabetica'] == 0 && $datosacc[0]['signos'] == 0)
            {
                if(!$this->hayNumeros($pass))
                    throw new ZendExt_Exception('SEG002');
            }
            if($datosacc[0]['numerica'] == 0 && $datosacc[0]['alfabetica'] == 1 && $datosacc[0]['signos'] == 0)
            {
                if(!$this->hayLetras($pass))
                    throw new ZendExt_Exception('SEG003'); 
            }
            if($datosacc[0]['numerica'] == 0 && $datosacc[0]['alfabetica'] == 0  && $datosacc[0]['signos'] == 1 )
            {
                $signos=preg_match($sn,$pass,$resultados);
                if($signos == 1)
                    throw new ZendExt_Exception('SEG004');
            }    
            if ($datosacc[0]['numerica'] == 1 && $datosacc[0]['alfabetica'] == 1 && $datosacc[0]['signos'] == 0)
            {
                if(!$this->hayLetras($pass) || !$this->hayNumeros($pass))
                    throw new ZendExt_Exception('SEG005');
            }
            if($datosacc[0]['numerica'] == 0 && $datosacc[0]['alfabetica'] == 1 && $datosacc[0]['signos'] == 1)
            {
                $signos = preg_match($sn,$pass,$resultados);
                if(!$this->hayLetras($pass) || !$signos)
                    throw new ZendExt_Exception('SEG006');
            }
        
            if($datosacc[0]['numerica'] == 1 && $datosacc[0]['alfabetica'] == 0 && $datosacc[0]['signos'] == 1)
            {                
                $signos=preg_match_all($sn,$pass,$resultados);
                if(!$signos == 1 || !$this->hayNumeros($pass) )
                    throw new ZendExt_Exception('SEG007');
            }
            if($datosacc[0]['numerica'] == 1 && $datosacc[0]['alfabetica'] == 1 && $datosacc[0]['signos'] == 1)
            {
                $signos=preg_match($sn,$pass,$resultados);                
                if(!$this->hayNumeros($pass) || $signos==1 || !$this->hayNumeros($pass))
                    throw new ZendExt_Exception('SEG008');
            }
            return true;
        }
            
    function hayNumeros($cadena)
    {
        $cant = strlen($cadena);
        for($i=0; $i<$cant; $i++)
            if($cadena[$i] >= '0' && $cadena[$i] <= '9')
                return true;
        return false; 
    }
            
    function hayLetras($cadena)
    {
        $cant = strlen($cadena);
        for($i=0; $i<$cant; $i++)
            if(($cadena[$i] >= 'A' && $cadena[$i] <= 'Z') || ($cadena[$i] >= 'a' && $cadena[$i] <= 'z') || $cadena[$i] == '' || $cadena[$i] == '')
                return true;
        return false; 
    } 
        
    function devolverusuariosdadorolentidadAction()
    {
    $identidad = '100000001';
    $idrol = '10000000001';
    $result = SegUsuario::cargarusuarios($identidad,$idrol);
    foreach($result as $key=>$usuarios)
         {
             $perfil = SegUsuario::cargarperfilusuario($usuarios['idusuario']);
             if (is_array($perfil[0]['NomFila']['NomValor']))
             foreach ($perfil[0]['NomFila']['NomValor'] as $valor) 
                if($valor['idfila'])
                {                        
                    $arraycampos= NomValor::cargarcamposdadovalores($valor['idvalor']);
                    $arrayreturn[$key][$arraycampos[0]['NomCampo']['nombre']] =$valor['valor'];                       
                }          
             $arrayreturn[$key]['idusuario']= $usuarios['idusuario'];
         }
       return $arrayreturn;
    }
        
    function modificardominioAction()
    {
          $dominio = '1';
          $result = SegDominio::verificarmodificaciones($dominio);
          $cadena = explode(',',$result[0]['cadena']);
          if(count($cadena) > '1')
          echo 'true';
          else
          echo 'false';
        }
        
    function verificarexistecertificadoAction()
    {
         $certificado = '1100000000178';
         $existe = SegCertificado::existcertificado($certificado);
          if($existe)
          echo 'true';
          else
          echo 'false';
        }
        
    function loadDomain($certificate, $idEstructura)
    {
        $objcertificado = new SegCertificado();                
        $datos = $objcertificado->verificarcertificado($certificate);                    
        $idusuario = $datos[0]->idusuario;
      
        if(count($datos))
        {
            $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
            $arrayEntidades = SegDominio::obtenerCadenaEntidades($iddominio[0]->iddominio);
        
            $arrayId = $this->cargarEntidadesDadoDominio($arrayEntidades,$idEstructura);
            $integrator = ZendExt_IoC::getInstance();
            $arrayEstructuras = $integrator->metadatos->ListarEstructurasExternasDadoArrayId($arrayId);
            $arrayEstructuras = $this->ponerComoHoja($arrayEstructuras);
            return $arrayEstructuras;                            
        }
        else 
            throw new ZendExt_Exception('SGIS003');
    }
    
    function verificaraccesosAction()
    {
      $certificate = '110000000155';
      $idEstructura = '100000093';
      $objcertificado = new SegCertificado();                
      $datos = $objcertificado->verificarcertificado($certificate);                    
      $idusuario = $datos[0]->idusuario;
          if(count($datos))
          {
            if($this->usuarioEntidad($idusuario,$idEstructura) || $this->verificarendominio($idusuario,$idEstructura))
             echo 'true';//return 1;
            else
             echo 'false';//return 0;
          }
          echo 'pepito';//return 0; 
     }
     
    function verificarendominio($idusuario,$idEstructura)
    {
       $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
       $arrayEntidades = SegDominio::obtenerCadenaEntidades($iddominio[0]->iddominio);
       if($this->buscarIdCargarnueva($arrayEntidades,$idEstructura))
            return 1;
        else 
            return 0;
     }
     
    function usuarioEntidad($idusuario,$idEstructura)
    {
      $exist = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$idEstructura);
      if(count($exist))
      return 1;
      else
      return 0;
     }
     
    function buscarIdCargarnueva($array, $idEntidad)
    {
            
        foreach($array as $valor)
        {    
            $temp = explode("-",$valor);
            $res = $this->existeValor($temp,$idEntidad);
            if($res)
            return $res;
        } 
        return 0;   
    }
    
    function existeValor($array, $valor)
    {
     for($i=0; $i<count($array)-1; $i++)
        {
            if(substr($array[$i],0,strlen($array[$i])-2) == $valor)
                {
                return substr($array[count($array)-1],0,strlen($array[$i])-2);
                }
        } 
          return 0;   
    }
    
    function devolverdatosAction()
    {
     $idusuario = '1000000001';
     $a = new  SegUsuario();
     $datos = $a->cargarperfilusuario($idusuario);
     foreach($datos as $key=>$valores)
     {
     $result[$key]['identidad'] = $valores['identidad'];
     $result[$key]['idarea'] = $valores['idarea'];
     $result[$key]['idcargo'] = $valores['idcargo'];
     }
     echo '<pre>';print_r($result);die;
    }

    public function loadDomainAction()
    {       
        $certificate = 11000000001;
        $objcertificado = new SegCertificado();                
        $datos = $objcertificado->verificarcertificado($certificate);                    
        $idusuario = $datos[0]->idusuario;
        $param = 1010;
        if(count($datos))
        { 
            $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
            $integrator = ZendExt_IoC::getInstance();
            $arrayEstructuras = $integrator->metadatos->ObtenerEstructurasPorIdOrgano($iddominio[0]->iddominio,$param);
            echo'<pre>';print_r($arrayEstructuras);die; 
       }
      
    }       
    
     function buscarIdCargar1($array, $idEntidad=0)
    {   
        $resultado = array();
        if(!$idEntidad)
        {
            foreach($array as $valor)
            {
                $temp = explode("-",$valor);
                if(!$this->existeValorArray1($resultado, substr($temp[0],0, strlen($temp[0])-2)))
                    $resultado[] = substr($temp[0],0, strlen($temp[0])-2);        
            }    
        }
        else
        {
            foreach($array as $valor)
            {    
                $temp = explode("-",$valor);
                $res = $this->existeValorArrayNoUltimo1($temp,$idEntidad);
                if($res != -1)
                {    
                    if(!$this->existeValorArray1($resultado, substr($res,0,strlen($res)-2)))
                        $resultado[] = substr($res,0,strlen($res)-2);        
                }
            }    
        }
        return $resultado; 
    }
    
    function cargarEntidadesDadoDominio1($arrayEntidades, $idEntidad)
    {
     return $this->buscarIdCargar1($arrayEntidades,$idEntidad);
    }
    
    function ponerComoHoja1($array)
    {
    $integrator = ZendExt_IoC::getInstance();
    $n = count($array);
    for($i=0; $i<$n; $i++)
    {
      if($array[$i]['rgt'] - $array[$i]['lft'] == 1)
      {
      $arrayEstructuras1 = $integrator->metadatos->DameEstructurasInternasSeguridad($array[$i]['idestructura'],true);
      if(!count($arrayEstructuras1))
      $array[$i]['leaf'] = true;
      }      
    }
        return $array;        
    }
    
    function existeValorArray1($array, $valor)
    {
        foreach($array as $valor1)
            if($valor1 == $valor)
                return true;    
        return false;    
    }
    
    function existeValorArrayNoUltimo1($array, $valor)
    {
        for($i=0; $i<count($array)-1; $i++)
            if(substr($array[$i],0,strlen($array[$i])-2) == $valor)
                return $array[$i+1]; 
        return -1;    
    }

    function cargar($array)
    {
     $resultado = array();
            foreach($array as $valor)
            {
                $temp = explode("-",$valor);
                foreach($temp as $valor1)
                {
                    if($valor1[strlen($valor1)-1] == 'e'){
                        if(!$this->existeValorArray1($resultado, substr($valor1,0, strlen($valor1)-2)))
                            $resultado[] = substr($valor1,0, strlen($valor1)-2);}
                }
            }
            return $resultado;
    } 

    public function pruebaglobalAction()
    {

         $perfil = $this->global->estructura;
         echo"<pre>";print_r($perfil);die;
    
    }
    function obtenerestructurasnoformalesAction()
    {die('kjhgkhasdkhg');
       $certificate = 11000000001;
       $objcertificado = new SegCertificado();                
       $datos = $objcertificado->verificarcertificado($certificate);                    
       $idusuario = 1000000001;//$datos[0]->idusuario;
        
      $entidades = DatEntidadSegUsuarioSegRol::cargarentidadesusuario($idusuario);
	//echo'<pre>';print_r($idusuario);die('llego');
      $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
      $arrayEntidades = SegDominio::obtenerCadenaEntidades($iddominio[0]->iddominio);
           foreach($arrayEntidades as $valor1)
                {
                    $temp = explode("-",$valor1);
                    $ultima =  $this->arrayUltimasEntidades($temp,$entidades);
                    if($ultima)
                    {
                        $arrayultimas[] = $ultima;
                    }         
                }
           echo'<pre>';print_r($arrayultimas);die;
    }
     
     function arrayUltimasEntidades($array,$entidades)
     {
            
	    $cantidad = count($array);
            $internas = explode('_',$array[$cantidad - 1]);		
            if($internas[1] == 'i' && $this->verificarValorArray($internas[0], $entidades))
                return $internas[0];
            else
                return 0;    
        }
    function verificarValorArray($valor, $array)
	{
	   foreach($array as $interna)
		{
		if($interna == $valor)
		return true;
		}
	return false;	
	}
    function insertarUsuarioSIGACAction()
    {
     $user = 'andres';
     $pass = 'lolo';
     $url = 'http://10.12.62.65/aki/alla.php';
     $usuario = new SegUsuario(); 
     $idusuario = $this->insertar($user,$pass);
     $idcampo = $this->insertarCampo();
     $idfila = $this->insertarFila($idusuario);
     if($this->insertarValor($idfila,$idcampo,$url) > '0')
        return true;
     else
        return false; 
    }
    
    function insertar($user,$pass)
    {
    $tema = NomTema::cargarTema();
    $dominio = SegDominio::cargarDominio();
    $idioma = NomIdioma::cargarcomboidioma();
    $desktop = NomDesktop::cargarcombodesktop();
    $usuario = new SegUsuario();    
    $usuario->nombreusuario = $user;
    $usuario->contrasenna = $pass;
    $usuario->contrasenabd = $pass;
    $usuario->ip = '0.0.0.0/0';
    $usuario->idtema = $tema[0]->idtema;
    $usuario->iddominio = $dominio[0]->iddominio;
    $usuario->ididioma = $idioma[0]->ididioma;
    $usuario->iddesktop = $desktop[0]->iddesktop;
    $usuario->idarea = '0';
    $usuario->identidad = '0';
    $usuario->idcargo = '0';
    $usuario->save();
    return $usuario->idusuario; 
    }
    
    function insertarCampo()
    {
    $idexpresion = NomExpresiones::obtenerexpresionRegular();
    $campo = new NomCampo();
    $campo->tipo = 'varchar';
    $campo->nombre = 'urlSIGAC';
    $campo->nombreamostrar = 'urlSIGAC';
    $campo->longitud = '200';
    $campo->visible = 'FALSE';
    $campo->idexpresiones = $idexpresion[0]->idexpresiones;
    $campo->descripcion = '';
    $campo->tipocampo = 'textfield';
    $campo->save();
    return $campo->idcampo;
    }
    
    function insertarFila($idusuario)
    {
     $fila = new NomFila();
     $fila->idusuario = $idusuario;
     $fila->save();
     return $fila->idfila; 
    }
    
    function insertarValor($idfila,$idcampo,$url)
    {
     $valor = new NomValor();
     $valor->idfila = $idfila;
     $valor->idcampo = $idcampo;
     $valor->valor = $url;
     $valor->save();
     return $valor->idvalor;
    }
    
    function devolverDatosSIGACAction()
    {
      $user = 'andres';
      $idusuario = SegUsuario::verificarpass($user);
      $perfil = SegUsuario::cargarperfilusuario($idusuario[0]->idusuario);
      $datosSIGAC['usuario'] = $user;
      $datosSIGAC['password'] =  $idusuario[0]->contrasenna; 
      $datosSIGAC['url'] = $perfil[0]['NomFila']['NomValor'][0]['valor'];
      return $datosSIGAC;
    }
    
    function getUserNamesAction()
    {
     $arrayid = array('0'=>1000000001,'1'=>10000000006,'2'=>10000000010,'3'=>10000000011);
     foreach($arrayid as $key=>$idusuario)
     {
      $nombreusuario = SegUsuario::cargarperfilusuario($idusuario);
      //echo'<pre>';print_r($nombreusuario);die; 
      $arrayresult[$key]['nombreusuario'] =  $nombreusuario[0]['nombreusuario'];
     }
     echo'<pre>';print_r($arrayresult);
    }
    
    function modificarDatosSIGACAction() {
    $userviejo = 'andres';
    $nuevouser = 'pepe';
    $pass = 'lolonnn';
    $url = 'http://10.12.62.65/repo/aplicaciones/comun/';
    
    $idusuario = SegUsuario::verificarpass($userviejo); 
    $objusuario = Doctrine::getTable('SegUsuario')->find($idusuario[0]->idusuario);
    if($userviejo != $nuevouser)
        {$objusuario->nombreusuario = $nuevouser;}
    $objusuario->contrasenna = $pass;
    $objusuario->contrasenabd = $pass;
    $objusuario->save();
    $perfil = SegUsuario::cargarperfilusuario($idusuario[0]->idusuario);
    $objvalor = Doctrine::getTable('NomValor')->find($perfil[0]['NomFila']['NomValor'][0]['idvalor']);
    $objvalor->valor = $url;
    $objvalor->save();
    if($objusuario->idusuario && $objvalor->idvalor)
        return true;
    else
        return false;
    }
    
    function AddUserAduanaAction() {
      $certificate = '11000000001';
      $usuario = 'darien';
      $pass = 'darien123';
      $dominio = '1';
      $estructura = '100000003';
      $rol = '10000000001';
      if(SegCertificado::existcertificado($certificate) == 1)
      	if(SegUsuario::contarusuario($usuario) == 0)
      	{
      	 $arrayEntidades = SegDominio::obtenerCadenaEntidades($dominio);		
         if($this->verifyEntityDomain($arrayEntidades,$estructura))
         {
         	$tema = NomTema::cargarTema();
    		$idioma = NomIdioma::cargarcomboidioma();
    		$desktop = NomDesktop::cargarcombodesktop();
         	$objUser = new SegUsuario();
         	$objUser->nombreusuario = $usuario;
         	$objUser->contrasenna = md5($pass);
         	$objUser->contrasenabd = md5($pass);
         	$objUser->iddesktop = $desktop[0]->iddesktop;
         	$objUser->idtema = $tema[0]->idtema;
         	$objUser->ididioma = $idioma[0]->ididioma;
         	$objUser->iddominio = $dominio;
         	$objUser->save();
         	$objEntUserRol = new DatEntidadSegUsuarioSegRol();
         	$objEntUserRol->idusuario = $objUser->idusuario;
         	$objEntUserRol->identidad = $estructura;
         	$objEntUserRol->idrol = $rol;
         	$objEntUserRol->save();
         	return true;        	
         }
         else 
         	return false;
      	}
      	else return false;      	
      else 
      	return false; 	
    }
    
	private function verifyEntityDomain($array,$idestructura)
    {
            foreach($array as $valor)
            {
             $temp = explode("-",$valor);
                
                foreach($temp as $valor1)
                {                	
                    if($valor1[strlen($valor1)-1] == 'e')
                        if(substr($valor1,0, strlen($valor1)-2) == $idestructura)
                        	{return true;}
                }
            }
            return false;
    }

    /**
     * getSistems
     * Devuelve los sistemas a los que tiene permiso un usuario en una entidad.
     * 
     * @param integer $idsistema - Id de sistema.
     * @param string $certificate - Certificado de seguridad asociado al usuario.
     * @param integer $entity - Entidad donde se encuentra logeado el usuario.
     * @return array - array. 
     */
    function getSistemsAction() {
    	$idsistema = '10000000002';
    	$certificate = '11000000001';
    	$entity = '100000001';
    	$objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);   
        $idusuario = $datos[0]->idusuario;
        if($idusuario) {
        	$rol = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$entity);
        	$result = DatSistema::cargarsistemasdelrol($idsistema,$rol[0]->idrol);
        }
        echo '<pre>';print_r($result);die;
    }
    
	function returnConexAction() {
    	$array = array( 'idsistema'=>'300000000009',
    				    'idservidor'=>'30000002',
    					'idgestor'=>'30000000
    					',
    					'idbd'=>'30000001
    					',	
    					'idesquema'=>'',
    					);
    	if ( $array['idsistema'] && $array['idservidor'] && $array['idgestor'] && $array['idbd'] ){ 
	    		$result = DatEsquema::getSchemasConfig($array['idsistema'], $array['idservidor'], $array['idgestor'], $array['idbd']);
	    		echo '<pre>';print_r($result);die;
	    	} 
	    	elseif ( $array['idsistema'] && $array['idservidor'] && $array['idgestor']) {
	    		$result = DatBd::getBdConfig($array['idsistema'], $array['idservidor'], $array['idgestor']); 
	    		echo '<pre>';print_r($result);die;
	    	}
	    	elseif ( $array['idsistema'] && $array['idservidor']) {
	    		$result = DatGestor::getGestorConfig($array['idsistema'], $array['idservidor']); 
	    		echo '<pre>';print_r($result);die;
	    	}
	    	else 
	    	{$result = DatServidor::getServerName($array['idsistema']);}
	   echo '<pre>';print_r($result);die;
    }
    
    function getConexByUriAction() {
    	$certificate = '11000000001';
    	$uri = 'usuario/index.php/gestusuario/gestusuario';
    	$objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);   
        $idusuario = $datos[0]->idusuario;
        if($idusuario) {
        	$sistema = DatFuncionalidad::getSistemId($uri);
        	$conex = DatSistemaDatServidores::getConection($sistema[0]['idsistema']);
        	echo'<pre>';print_r($conex);
        }
    }
}	
     
