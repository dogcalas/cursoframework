<?php

/**
 * SeguridadSoapService
 * Interfaz o Proxy utilizado por SIGIS para brindar los servicios web a todos los sistemas 
 * que se suscriban a �l.
 * 
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garcia Tejo 
 * @version 1.0-0
 */
class SeguridadSoapService {
    
    private function devolverIp()
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
    
    private function verificarIP($ip,$arraybd)
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
    
    private function compreobarIP($valor1,$valor2,$opc)
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
    
    /**
     * authenticateUser
     * Brinda el servicio de autenticacion por usuario y password
     * 
     * @param string $user - Usuario que se esta autenticando
     * @param string $password - Clave de acceso del usuario
     * @return string - Certificado o token de seguridad asignado al usuario. 
     */
     
    public function authenticateUser($user, $password) 
    {
        $mac=1;
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

          $options = array('server' => array('host'=>$ldap,'accountDomainName'=> $ldap,'baseDn'=>"OU=$servidor[0] Domain Users,DC=$servidor[0],DC=$servidor[1],DC=$servidor[2]"));
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
                    return $result;
                   }
              }
          else
            {                        
                return 0;
            }
        }
        else
        {  
            $objUsuario = new SegUsuario();
            $resultUsuario = $objUsuario->comprobarusuario($user, md5($password));
            if(count($resultUsuario))
                {
                $result = $this->returnCertificate($mac,$resultUsuario[0]['idusuario']);
                return $result;
                }
            else return 0;
        }
         }
        return 0;
    }
     
    /**
     * returnCertificate
     * Actualiza o crea un certificado al usuario autenticado
     * 
     * @param integer $idusuario - Id del usuario que se esta autenticando
     * @param string $mac - Mac del usuario
     * @return string - Certificado o token de seguridad asignado al usuario. 
     */
    private function returnCertificate($mac, $idusuario)
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
 
    /**
     * createCertificate
     * Crea un certificado a partir del identificador del usuario
     * 
     * @param integer $iduser - Identificador del usuario
     * @ignore - El certificado no se esta generando correctamente
     */
    private function createCertificate($iduser)
    {
        //$numero = rand(1, 100000000000000);
        return 1 . $iduser /*+ $numero*/;
    }
    
    /**
     * updateCertificate
     * Cambia el valor del certificado asignado al usuario
     * 
     * @param string $mac - Mac de la PC cliente
     * @param string $idcertificate - Identificador del certificado
     * @param integer $iduser - Identificador del usuario
     * @return string - Nuevo certificado asignado al usuario
     */ 
    private function updateCertificate($mac, $idcertificate, $iduser)
    {
        $objCertificado = Doctrine::getTable('SegCertificado')->find($idcertificate);
        $objCertificado->mac = $mac;
        $objCertificado->valor = $this->createCertificate($iduser); 
        $model = new SegCertificadoModel();
        return  $model->modificarcertificadoServicio($objCertificado);
    }

    /**
     * loadProfile
     * Brinda el servicio de configuracion del perfil del usuario autenticado
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @return array - Datos del perfil del usuario autenticado.
     */
    public function getProfile($certificate) 
    {
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);
        if(count($datos)) {            
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
            $integrator = ZendExt_IoC::getInstance();
            $arrayEstructuras = $integrator->metadatos->MostrarCamposEstructuraSeguridad($perfil[0]['identidad']);
            $perfilusuario['entidad'] = $arrayEstructuras[0]['text']; 
            if($perfil[0]['idarea'] != 0)
                {
                $area = $integrator->metadatos->EstructurasInternasDadoIDSeguridad($perfil[0]['idarea']);
                $perfilusuario['area'] = $area[0]['text'];
                }
            if($perfil[0]['idcargo'] != 0) 
                {     
                $cargo = $integrator->metadatos->CargoDadoIDSeguridad($perfil[0]['idcargo']);
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
            return $perfilusuario;    
        }
        else 
            throw new ZendExt_Exception('SGIS003');
    }

    /**
     * loadDomain
     * Obtener el dominio de entidades a la que el usuario tiene acceso
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     *  @param integer $idEstructura - Id estructura.
     * @return array - Entidades a las que el usuario tiene acceso.
     */
    public function loadDomain($certificate, $idEstructura)
    {
    	try {
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
    	} catch (Exception $e) {
    		throw new SoapFault($e->getMessage(),'SERVER');
    	}
    }
    
    /**
     * loadFormalEntity
     * Obtener las entidades formales a las que el usuario tiene acceso
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $param - Id del organo.
     * @return array - Entidades formales a las que el usuario tiene acceso.
     */
    public function getEntityByIdOrgano($certificate,$param)
    {       
        $objcertificado = new SegCertificado();                
        $datos = $objcertificado->verificarcertificado($certificate);                    
        $idusuario = $datos[0]->idusuario;
        if(count($datos))
        { 
            $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
            $integrator = ZendExt_IoC::getInstance();
            $arrayEstructuras = $integrator->metadatos->ObtenerEstructurasPorIdOrgano($iddominio[0]->iddominio,$param);
            return $arrayEstructuras;                             
       }
        else 
            throw new ZendExt_Exception('SGIS003');
    } 
    
    private function cargar($array)
    {
     $resultado = array();
            foreach($array as $valor)
            {
                $temp = explode("-",$valor);
                foreach($temp as $valor1)
                {
                    if($valor1[strlen($valor1)-1] == 'e'){
                        if(!$this->existeValorArray($resultado, substr($valor1,0, strlen($valor1)-2)))
                            $resultado[] = substr($valor1,0, strlen($valor1)-2);}
                }
            }
            return $resultado;
    }           
    
    /**
     * arrayEstructurasUsuarios
     * Obtener las estructuras a las que tiene acceso un usuario
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @return array - Estructuras a las que tiene acceso
     */
    public function ObtenerEstructurasNoFormales($certificate)
    {
            $objcertificado = new SegCertificado();                
            $datos = $objcertificado->verificarcertificado($certificate);                    
        $idusuario = $datos[0]->idusuario;
        if(count($datos))
            {
            $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
            $integrator = ZendExt_IoC::getInstance();
            $arrayEstructuras = $integrator->metadatos->ObtenerEstructurasNoFormales($iddominio[0]->iddominio);
            $resultado = array();
            foreach($arrayEstructuras as $valor)
                $resultado[] = $valor["idestructura"];
            return $resultado;

            }
        else 
            throw new ZendExt_Exception('SGIS003');
    }
    
    /**
     * existSomeRol
     * Funcion para saber si hay algun rol aparte del de instalacion creado.
     * 
     * @return boolean - buleano. 
     */    
    public function existSomeRol()
    {
        $cantRoles = SegRol::cantrol();
        if($cantRoles > 1)
            return true;
        return false;
    }
    
    /**
     * existUserRol
     * Funcion para saber si existe algun usuario.
     * 
     * @return boolean - buleano. 
     */    
    public function existUserRol()
    {
       if(SegUsuario::existealgunusuario() > '0')
                return 1;
             else
                return 0;
    }

    private function buscarIdCargar($array, $idEntidad=0)
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
    
    private function cargarEntidadesDadoDominio($arrayEntidades, $idEntidad)
    {
        return  $this->buscarIdCargar($arrayEntidades, $idEntidad); 
    }
    
    /**
     * getSystems
     * Brinda el servicio de listar todos los sistemas a los que tiene acceso un usuario en una entidad.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la estructura.
     * @return array - array.
     */
    public function getSystems ($certificate, $identidad)
    {
		$objcertificado = new SegCertificado();
		$datos = $objcertificado->verificarcertificado($certificate);
        if(count($datos))
        {
        	$idusuario = $datos[0]->idusuario;
        	$rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario, $identidad);
            
            $sistemaObj = DatSistema::obtenersistemas($rolusuarioentidad[0]->idrol);
		$sistemaArr = array();
		foreach ($sistemaObj as $key => $sistema) {
			$sistemaArr[$key]['id'] = $sistema->idsistema;
			$sistemaArr[$key]['icono'] = ($sistema->icono) ? $sistema->icono : 'falta';
			$sistemaArr[$key]['titulo'] = $sistema->denominacion;
            $sistemaArr[$key]['externa'] = $sistema->externa;
		}
            return $sistemaArr;
        }
		else
			throw new ZendExt_Exception('SGIS003');
	}
    
    private function existeValorArray($array, $valor)
    {
        foreach($array as $valor1)
            if($valor1 == $valor)
                return true;    
        return false;    
    }
    
    private function existeValorArrayNoUltimo($array, $valor)
    {
        for($i=0; $i<count($array)-1; $i++)
            if(substr($array[$i],0,strlen($array[$i])-2) == $valor)
                return $array[$i+1]; 
        return -1;    
    } 
    
    private function ponerComoHoja($array)
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
    
    /**
     * loadActions
     * Servicio que le permite obtener las acciones de un usuario.
     * 
     * @param string $certificado - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la entidad.
     * @param integer $idsistema - Identificador del sistema.
     * @param integer $idfuncionalidad -  Identificador de la funcionalidad.
     * @return array - array.
     */    
    function loadActions($identidad,$idsistema,$idfuncionalidad,$certificado)
    {
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificado);
        if($datos){
            $idusuario = $datos[0]->idusuario;    
            $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identidad);      
            $accionesquetiene = DatAccion::cargaracciones($idsistema,$idfuncionalidad,$rolusuarioentidad[0]->idrol);
                foreach ($accionesquetiene as $valores=>$valor)
                    {
                    $arracciones[$valores]['idaccion']= $valor->idaccion;
                    $arracciones[$valores]['denominacion']= $valor->denominacion;
                    }
            return $arracciones;
        }
        else
        throw new ZendExt_Exception('SGIS003');                    
    }
    
    /**
     * getSystemsFunctions
     * Carga sistemas, subsistemas y funcionalidades.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $idsistema - Identificador del sistema.
     * @param integer $identidad - Identificador de la estructura.
     * @return array - array.
     */
    public function getSystemsFunctions($certificate, $idsistema, $identidad)
	{
		$tmpidsistema = explode('_', $idsistema);
        
		if (count($tmpidsistema) >= 1)
			$idsistema = $tmpidsistema[0];
            $objcertificado = new SegCertificado();
            $datos = $objcertificado->verificarcertificado($certificate);
            $idusuario = $datos[0]->idusuario;			
            $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identidad);
            $idrol = $rolusuarioentidad[0]->idrol;
            $acceso = DatSistema::verificarrolsistema($idrol,$idsistema);            
            if(count($acceso))
            { 
                    $sistemas = DatSistema::cargarsistema($idsistema);
                    $externa = DatSistema::buscarservidorweb($idsistema); 
                    $contador = 0;
                    $sistemafunArr = array();
                    if($sistemas->count())
                        {
                            foreach ($sistemas as $contador=>$valor)
                            {
                            	
                            $sistemafunArr[$contador]['id'] = $valor->id.'_'.$idsistema;
                            $sistemafunArr[$contador]['idsistema'] = $valor->id;
                            $sistemafunArr[$contador]['externa'] = $valor->externa;
                            $sistemafunArr[$contador]['icono'] = $valor->icono;  
                            $sistemafunArr[$contador]['text'] = $valor->text;
                            //$sistemafunArr[$contador]['servidores'] = $this->servidores($valor->id);
                            $contador++;
                            }
                        }
					$funcionalidad = DatFuncionalidad::cargarfuncionalidades($idsistema,0,0);
                    if($funcionalidad->getData() != NULL)
                        {
                            foreach ($funcionalidad as $contador=>$valor)
                            {
                            $sistemafunArr[$contador]['id'] = $valor->id.'_'.$idsistema;
                            $sistemafunArr[$contador]['idfuncionalidad'] = $valor->id;
                             if($externa[0]->externa)
                                $sistemafunArr[$contador]['referencia'] = 'http://'.$externa[0]->externa.'/'.$valor->referencia;
                             else
                                $sistemafunArr[$contador]['referencia'] = '../../../' . $valor->referencia;
                            $sistemafunArr[$contador]['icono'] = $valor->icono;                            
                            $sistemafunArr[$contador]['text'] = $valor->text;
                            $sistemafunArr[$contador]['leaf'] = true;
                            $contador++;
                            }
                        }
                    if(count($sistemafunArr))
                    return $sistemafunArr;
                    else
                    return array();
            }
            else        
            	throw new ZendExt_Exception('SGIS003');        
	}
    
    /**
     * getSystemsDesktopModules
     * Obtener los modulos para el portal tipo escritorio.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la entidad a la que accedió el usuario.
     * @return array - Arreglo de modulos. 
     */
   public function getSystemsDesktopModules($certificate, $identidad)
	{
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);    
		if(count($datos))
        {
        	$idusuario = $datos[0]->idusuario;
            $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario, $identidad);
            $sistemas = DatSistema::obtenersistemas($rolusuarioentidad[0]->idrol)->toArray(true);
            if(count($sistemas))
            {
            	foreach ($sistemas as $mdf)
                	$resultado[] = $this->subsistemas($mdf,$rolusuarioentidad[0]->idrol);        
				return $resultado;
			}                
            else return array();
		}
		else        
            throw new ZendExt_Exception('SGIS003');
	}
    
    /**
     * getSystemsFunctionsDesktopModules
     * Obtener los modulos para el portal tipo escritorio.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la entidad a la que accedió el usuario.
     * @return array - Arreglo de modulos. 
     */
  public function getSystemsFunctionsDesktopModules($certificate, $identidad)
	{
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
                return $resultado;
			}                
            else return array();
		}
		else        
            throw new ZendExt_Exception('SGIS003');
	}

    private function subsistemas($raiz,$idrol)
    {            
        $result = array('icono'=>$raiz['icono'], 'id'=>$raiz['idsistema'], 'text'=>$raiz['denominacion'],'externa'=>$raiz['externa']/*,'servidores'=> $this->servidores($raiz['idsistema'])*/);
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
    
   private function subsistemasfunc($raiz,$idrol)
	{            
		$result = array('icono'=>$raiz['icono'], 'id'=>$raiz['idsistema'], 'text'=>$raiz['denominacion'],'externa'=>$raiz['externa'], 'menu'=> $this->funcionalidades($raiz['idsistema'],$idrol,$raiz['externa'])/*,'servidores'=> $this->servidores($raiz['idsistema'])*/);
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

    private function servidores($idsistema)
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
        
    private function funcionalidades($idsistema,$idrol,$externa)
    {
         $funcsistema = DatFuncionalidad::obtenerFuncionalidades($idsistema,$idrol);
         $arrayFuncionalidades = array();
         foreach($funcsistema as $key=>$funcionalidades)
             {
              $arrayFuncionalidades[$key]['text'] = $funcionalidades->text; 
              $arrayFuncionalidades[$key]['id'] = $funcionalidades->idfuncionalidad;
              $arrayFuncionalidades[$key]['index'] = $funcionalidades->index;
              if($externa)
              $arrayFuncionalidades[$key]['referencia'] = 'http://'.$externa.'/'.$funcionalidades->referencia;
              else
              $arrayFuncionalidades[$key]['referencia'] = '../../../' . $funcionalidades->referencia;
             }
         return $arrayFuncionalidades;
        } 

    /**
     * verifyAccessEntity
     * Verifica si un usuario tiene acceso a una entidad.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identity - Identificador de la entidad a la que accedió el usuario.
     * @return integer - entero. 
     */
    public function verifyAccessEntity ($certificate, $identity)
    {
        $objcertificado = new SegCertificado();                
        $datos = $objcertificado->verificarcertificado($certificate);                    
        $idusuario = $datos[0]->idusuario;
        $result = $this->verificarendominio($idusuario,$identity);
          if(count($datos))
          {
            if($this->usuarioEntidad($idusuario,$identity)) {
                          $roles = SegRol::obtenerrolesusuarioentidad($idusuario,$identity);                           
             if (count($roles))
                 return $roles[0]->text;
             else return '0';
            }
         elseif(count($result))
             {
              foreach($result as $valor){
                  $roles = SegRol::obtenerrolesusuarioentidad($idusuario,$valor);
                  if (count($roles))
                     return $roles[0]->text;
                 }
               return '0';        
             }        
             return '0';
          }
          return '0';
    }
    
    /**
     * LoadXML
     * Obtiene un XML con la configuracion para un usuario.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identity - Identificador de la entidad a la que accedió el usuario.
     * @return array - array. 
     */
   function LoadXML($certificate,$identity)
   {
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);    
        $idusuario = $datos[0]->idusuario;
        if(count($idusuario))
        {                
             $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identity);
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
    
    /**
     * LoadXMLModule
     * Obtiene un XML con la configuracion de un modulo dado para un usuario.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param string $module - Modulo especificado.
     * @param integer $identity - Identificador de la entidad a la que accedió el usuario.
     * @return array - array. 
     */
   function LoadXMLModule($certificate,$identity,$module)
   {
                $objcertificado = new SegCertificado();
                $datos = $objcertificado->verificarcertificado($certificate);    
                $idusuario = $datos[0]->idusuario;
                if(count($idusuario))
                {                
                    $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identity);
                    $sistemas = DatSistema::obtenersistemasxml($rolusuarioentidad[0]->idrol,$module)->toArray(true);
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
    
   private function subsistemasxml($raiz,$idrol,& $menu) 
   {   
        $menu .= "<MenuItem name=\"{$raiz['denominacion']}\" id=\"{$raiz['idsistema']}\" externa=\"{$raiz['externa']}\" icon=\"{$raiz['icono']}\"  status=\"{$raiz['descripcion']}\">" ;
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
        
   private function funcionalidadesxml($idsistema,$idrol, & $menu)
   {
     $funcsistema = DatFuncionalidad::obtenerFuncionalidades($idsistema,$idrol);
     if(count($funcsistema))
       foreach($funcsistema as $funcionalidades)
         $menu .= "<MenuItem name=\"{$funcionalidades->text}\" id=\"{$funcionalidades->idfuncionalidad}\"  src=\"{$funcionalidades->referencia}\" icon=\"{$funcionalidades->icono}\"  status=\"{$funcionalidades->descripcion}\"/>" ;
    } 
   
   /**
     * returnUsersProfile
     * Devuelve el usuario con los datos del perfil.
     * 
     * @param array $array - Identificadores de usuarios.
     * @return array - array. 
     */ 
   public function getUsersProfile($array)
   {       
         foreach($array as $key=>$usuarios)
         {
             $datos = SegUsuario::nombusuario($usuarios)->toArray();
             $perfil = SegUsuario::cargarperfilusuario($usuarios);
             if (is_array($perfil[0]['NomFila']['NomValor']))
             {  
             foreach ($perfil[0]['NomFila']['NomValor'] as $valor) 
                if($valor['idfila'])
                {                        
                    $arraycampos= NomValor::cargarcamposdadovalores($valor['idvalor']);
                    $arrayreturn[$key][$arraycampos[0]['NomCampo']['nombre']] =$valor['valor'];                       
                }          
             }    
             $arrayreturn[$key]['idusuario']= $usuarios;
             $arrayreturn[$key]['usuario']= $datos[0]['nombreusuario']; 
         }
         return $arrayreturn; 
        }
        
   /**
     * getUsersEntity
     * Devuelve la entidad, el area y el cargo de cada usuario.
     * 
     * @param array - Identificadores de usuarios.
     * @return array - array. 
     */ 
   public function getUsersEntity($array)
   {
         foreach($array as $key=>$usuarios)
         {
             $datos = SegUsuario::cargarentidadesareascargos($usuarios);
             $integrator = ZendExt_IoC::getInstance();
             $arrayEstructuras = $integrator->metadatos->MostrarCamposEstructuraSeguridad($datos[0]['identidad']);
                    $arrayreturn[$key]['entidad'] = $arrayEstructuras[0]['text'];
                    $arrayreturn[$key]['identidad'] = $datos[0]['identidad']; 
                    if($datos[0]['idarea'] != 0)
                        {
                        $area = $integrator->metadatos->EstructurasInternasDadoIDSeguridad($datos[0]['idarea']);
                        $arrayreturn[$key]['area'] = $area[0]['denominacion'];        
                        $arrayreturn[$key]['idarea'] = $datos[0]['idarea'];
                        }
                    if($datos[0]['idcargo'] != 0) 
                        {     
                        $cargo = $integrator->metadatos->CargoDadoIDSeguridad($datos[0]['idcargo']);
                        $arrayreturn[$key]['cargo'] = $cargo[0]['denominacion'];
                        $arrayreturn[$key]['idcargo'] = $datos[0]['idcargo'];
                        }
             $arrayreturn[$key]['idusuario'] = $usuarios;
         }
         return $arrayreturn; 
        }
        
     /**
     * loadUserActions
     * Devuelve las acciones de un usuario.
     * 
     * @param string $certificate - Certificado del usuario.
     * @param integer $idfuncionalidad -  Identificador de la funcionalidad.
     * @param string $entity -  Identificador de la entidad.
     * @return array - array. 
     */    
     public function loadUserActions($certificate,$idfuncionalidad,$entity)
     { 
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);
        $idcertificado = $datos[0]->idcertificado;    
        $idusuario = $datos[0]->idusuario;    
        $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$entity);      
        $accionesquetiene = DatAccion::cargaraccionesservice($idfuncionalidad,$rolusuarioentidad[0]->idrol);
        if(count($accionesquetiene)){
        foreach ($accionesquetiene as $valores=>$valor)
            {
            $arracciones[$valores]['idaccion']= $valor->idaccion;
            $arracciones[$valores]['abreviatura']= $valor->abreviatura;
            }
            return $arracciones;
        }
        else
            return $arracciones = array(); 
        }
     
     /**
     * LoadUserRoles
     * Devuelve un arreglo con todos los roles de un usuario.
     * 
     * @param string $certificate - Certificado del usuario.
     * @return array - array. 
     */   
     public function LoadUserRoles($certificate)
     {
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
         return $arrayresult; 
      }
    
     /**
     * ExistUserRoles
     * Devuelve true o false si la entidad tiene roles o usuarios.
     * 
     * @param string $entity - Entidad.
     * @return integer - entero. 
     */  
    public function ExistUserRoles($entity)
    {
     $existe = DatEntidadSegUsuarioSegRol::existeusuariorol($entity);
     if($existe)
        return 1;
     else
        return 0;
    }
    
     /**
     * ChangeState
     * Devuelve true si se ha cambiado el estado de un usuario.
     * 
     * @param string $idusuario - Id de usuario.
     * @param string $opcion - Opcion.
     * @return integer - entero. 
     */
    public function ChangeState($idusuario,$opcion)
    {
    	$obj = Doctrine::getTable('SegUsuario')->find($idusuario);
        if($opcion == '0')
             {
                 $obj->estado = '0';
                 $obj->save();
                 return 1;
             }
         elseif($opcion == '1')
             {
                 $obj->estado = '1';
                 $obj->save();
                 return 1;
             }
         return 0;
    }
    
    /**
     * ChangePassword
     * Funcion para cambiar el password a un usuario.
     * 
     * @param string $usuario - nombre de usuario.
     * @param string $oldpass - Contrase�a vieja.
     * @param string $newpass - Nueva contrase�a.
     * @return integer - entero. 
     */    
    public function ChangePassword($usuario,$oldpass,$newpass)
    {
     $verificar = SegUsuario::verificarpass($usuario);
     $oldpass = md5($oldpass);
     if($verificar[0]->contrasenna == $oldpass)
     {
     if($this->verificarpass($newpass))
         {
          $objusuario = Doctrine::getTable('SegUsuario')->find($verificar[0]->idusuario);
          $objusuario->contrasenna = md5($newpass);
          $objusuario->contrasenabd =  md5($newpass);
          $objusuario->save();
          return 1;
         }
     }
     else
     return 0;
    } 
    
    private function verificarpass($pass)
    {
            $s = '/^([a-zA-Z��������])+$/';
            $sn = '/^[\da-zA-Z��������]+$/';
            $nn = '/[\d]/';
            $nl = '/[\!\\�\\!\@\#\$\%\^\&\*\(\)\_\+\=\{\}\[\]\:\"\;\'\?\/\>\.\<\,\\\*\-]+$/';
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
        
    private function hayNumeros($cadena)
    {
        $cant = strlen($cadena);
        for($i=0; $i<$cant; $i++)
            if($cadena[$i] >= '0' && $cadena[$i] <= '9')
                return true;
        return false; 
    }
        
    private function hayLetras($cadena)
    {
        $cant = strlen($cadena);
        for($i=0; $i<$cant; $i++)
            if(($cadena[$i] >= 'A' && $cadena[$i] <= 'Z') || ($cadena[$i] >= 'a' && $cadena[$i] <= 'z') || $cadena[$i] == '�' || $cadena[$i] == '�')
                return true;
        return false; 
    }
    
    /**
     * rolAsUsersGain
     * Servicio para obtener todos los usuarios de un rol en una entidad.
     * 
     * @param integer $identidad - Identificador de la entidad.
     * @param integer $idrol - Identificador del rol.
     * @return array - array. 
     */ 
    public function rolAsUsersGain($identidad,$idrol)
    {
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
    
    /**
     * modificateDomain
     * Servicio para saber si el dominio fue modificado.
     * 
     * @param integer $dominio - Identificador del dominio.
     * @return integer - integer. 
     */
     public function modificateDomain($dominio)
    {
      $result = SegDominio::verificarmodificaciones($dominio ='1');
      $cadena = explode(',',$result[0]['cadena']);
      if(count($cadena) > '1')
        return 1;
        else
        return 0;
    }
    
     /**
     * verificateCertificate
     * Servicio para saber si un certificado es valido.
     * 
     * @param integer $certificate - Certificado.
     * @return integer - Uno si existe cero de lo contrario. 
     */
     function verificateCertificate($certificate)
    {
         $existe = SegCertificado::existcertificado($certificate);
          if($existe)
            return 1;
          else
            return 0;
        } 
        
     private function verificarendominio($idusuario,$idEstructura)
    {
       $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
       $arrayEntidades = SegDominio::obtenerCadenaEntidades($iddominio[0]->iddominio);
       if($this->buscarIdCargarnueva($arrayEntidades,$idEstructura))
               return $this->buscarIdCargarnueva($arrayEntidades,$idEstructura);
        else
             return array();
     }
     
     private function usuarioEntidad($idusuario,$idEstructura)
    {
      $exist = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$idEstructura);
      if(count($exist))
      return 1;
      else
      return 0;
     }
     
     private function buscarIdCargarnueva($array, $idEntidad)
     {   
        foreach($array as $valor)
        {    
            $temp = explode("-",$valor);                        
            $res = $this->existeValor($temp,$idEntidad);
            if($res)
            $result[] = $res; 
        } 
        return $result;   
     }
    
     private function existeValor($array, $valor)
    {   
     for($i=0; $i<count($array)-1; $i++)
        {
            if(substr($array[$i],0,strlen($array[$i])-2) == $valor)
                {
                    $result = substr($array[count($array)-1],0,strlen($array[count($array)-1])-2);
                }
        }
          return $result;   
    }
    
     private function arrayUsuariosPerfil($array)
    {
    	$arrayreturn = array();
         foreach($array as $key=>$usuarios)
         {
             $perfil = SegUsuario::cargarperfilusuario($usuarios['idusuario']);
             if (is_array($perfil[0]['NomFila']['NomValor']))
             foreach ($perfil[0]['NomFila']['NomValor'] as $valor) 
                if($valor['idfila'])
                {                        
                    $arraycampos= NomValor::cargarcamposdadovalores($valor['idvalor']);
                    $arrayreturn[$key][$arraycampos[0]['NomCampo']['nombre']] =$valor['valor'];                       
                }          
             $arrayreturn[$key]['id'] = $usuarios['idusuario'];
             $arrayreturn[$key]['text'] = $usuarios['nombreusuario']; 
             $arrayreturn[$key]['idusuario'] = $usuarios['idusuario'];
             $arrayreturn[$key]['usuario'] = $usuarios['nombreusuario'];
             $arrayreturn[$key]['idpadre']= $perfil[0]['idcargo'];             
             $arrayreturn[$key]['leaf'] = true;
             $arrayreturn[$key]['checked'] = false;
         }
         return $arrayreturn; 
        }
        
     private function ponerComoHojaPDO($array) {
    $integrator = ZendExt_IoC::getInstance();
    $n = count($array);
    for($i=0; $i<$n; $i++)
    {
     $array[$i]['checked'] = false;    
      if($array[$i]['rgt'] - $array[$i]['lft'] == 1)
      {
      $arrayEstructuras1 = $integrator->metadatos->DameEstructurasInternasSeguridad($array[$i]['idestructura'],true);
      $cargos = $integrator->metadatos->BuscarCargosPorTiposSeguridad($array[$i]['idestructura']);
      if(!count($arrayEstructuras1) && !count($cargos) )
      $array[$i]['leaf'] = true;
      }      
    }
        return $array;        
    }
    
     /**
     * loadDomainPDO
     * Servicio para saber si un certificado es valido.
     * 
     * @param integer $certificado - Certificado.
     * @param integer $idEstructura - Id de la estructura.
     * @param string $tipo - Tipo. 
     * @return array - array. 
     */
     function loadDomainPDO($certificado,$idEstructura,$tipo){
                    $objcertificado = new SegCertificado();                
                    $datos = $objcertificado->verificarcertificado($certificado);                    
                    $idusuario = $datos[0]->idusuario;
                    if($idusuario)
                    {
                        $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
                        $arrayEntidades = SegDominio::obtenerCadenaEntidades($iddominio[0]->iddominio);
                        $arrayId = $this->cargarEntidadesDadoDominio($arrayEntidades,$idEstructura);
                         if((!count($arrayId)) && ($tipo =='interna'))
                         {
                          $integrator = ZendExt_IoC::getInstance();
                          $result = $integrator->metadatos->BuscarCargosPorTiposSeguridad($idEstructura);                          
                          foreach($result as $key=>$cargo)
                            {
                            $arrayEstructuras[$key]['text'] = $cargo['Asignacion']['text'];
                            $arrayEstructuras[$key]['id'] = $cargo['id'];
                            $arrayEstructuras[$key]['tipo'] = 'cargo';
                            $arrayEstructuras[$key]['idcargo'] = $cargo['id'];
                            $arrayEstructuras[$key]['checked'] = false;
                            if(!count(SegUsuario::usuariosdelcargo($cargo['id'])))
                            {$arrayEstructuras[$key]['leaf'] = true;}               
                            }
                         }
                         elseif($tipo =='cargo')
                         { 
                          $usuarios = SegUsuario::usuariosdelcargo($idEstructura);
                          $arrayEstructuras = $this->arrayUsuariosPerfil($usuarios);
                         }
                         else
                         {
                             $integrator = ZendExt_IoC::getInstance();
                             if(count($arrayId))
                             {
                                 $arrayEstructuras = $integrator->metadatos->ListarEstructurasDadoArrayId($arrayId);         
                                 $arrayEstructuras = $this->ponerComoHojaPDO($arrayEstructuras);
                             }                             
                         }
                        if(count($arrayEstructuras))
                        { return $arrayEstructuras;}
                        else
                        { return $arrayEstructuras = array();}                            
                    }

        } 
     
     /**
     * getUsersDates
     * Servicio que debuelve la entidad, el area y el cargo de un grupo de usuarios.
     * 
     * @param integer $idusuario - Id del usuario.
     * @return array - array. 
     */   
    function getUsersDates($idusuario)
    {
     $a = new  SegUsuario();
     $datos = $a->cargarperfilusuario($idusuario);
     foreach($datos as $key=>$valores)
     {
     $result[$key]['identidad'] = $valores['identidad'];
     $result[$key]['idarea'] = $valores['idarea'];
     $result[$key]['idcargo'] = $valores['idcargo'];
     }
     return $result;
    }  

    /**
     * nameUsersProfile
     * Devuelve el nombre del usuario.
     * 
     * @param integer $user - Identificador del usuarios.
     * @return string - string. 
     */ 
    public function nameUsersProfile($user) {       
     $datos = SegUsuario::nombusuario($user)->toArray();
     $nombre = $datos[0]['nombreusuario']; 
     return $nombre; 
    }
    
     /**
     * getUserNames
     * Funcion para devolver los nombres de usuarios.
     * 
     * @param array $arrayid - Nombres de usuarios.
     * @return array - array. 
     */
    function getUserNames($arrayid)
    {
     foreach($arrayid as $key=>$idusuario)
     {
      $nombreusuario = SegUsuario::cargarperfilusuario($idusuario);
      $arrayresult[$key]['nombreusuario'] =  $nombreusuario[0]['nombreusuario'];
     }
     return  $arrayresult;
    }
    
     /**
     * accessToFunctionality
     * Devuelve si un usuario tiene acceso una funcionalidad.
     * 
     * @param string $certificate - Token de seguridad.
     * @param string $url - Url.
     * @param integer $identity - Identificador de la entidad.
     * @return bool - bool. 
     */
    function accessToFunctionality($certificate,$url,$identity)
    {
      $objcertificado = new SegCertificado();                
      $datos = $objcertificado->verificarcertificado($certificate);                    
      $idusuario = $datos[0]->idusuario;
      $rol = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identity);
      $acceso = DatFuncionalidad::accesoFuncionalidad($rol[0]->idrol,$url);
      if(count($acceso))
        return true;
      else
        return false;
    }
    
     /**
     * accessToAction
     * Devuelve si un usuario tiene acceso una accion.
     * 
     * @param string $certificate - Token de seguridad.
     * @param string $denominacion - Denominacion de la accion.
     * @param integer $identity - Identificador de la entidad.
     * @return bool - bool. 
     */
    function accessToAction($certificate,$denominacion,$identity) {
      $objcertificado = new SegCertificado();                
      $datos = $objcertificado->verificarcertificado($certificate);                    
      $idusuario = $datos[0]->idusuario;
      $rol = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identity);
      $acceso = DatAccion::accesoAccion($rol[0]->idrol,$denominacion);
      if(count($acceso))
        return true;
      else
        return false;
    }
    
    /**
     * AddUserAduana
     * Activa un usuario.
     * 
     * @param string $certificate - Token de seguridad.
     * @param string $usuario - Nombre del usuario.
     * @param string $pass - Password del usuario.
     * @param integer $dominio - Identificador del dominio en que sera asignado.
     * @param integer $estructura - Identificador de la entidad a la cual tendra permisos.
     * @param integer $rol - Identificador el rol que le sera asignado.
     * @return bool - bool. 
     */
	function AddUserAduana($certificate,$usuario,$pass,$dominio,$estructura,$rol) {
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
}
