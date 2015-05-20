<?php
/**
 * ZendExt_App_Config
 * Configurador de la aplicacion
 * 
 * @author Yoandry Morejon Borbon
 * @package ZendExt
 * @subpackage ZendExt_App
 * @copyright UCID - ERP Cuba
 * @version 1.0-0
 */
class ZendExt_App_Config {
	
	/**
     * Constructor de la clase
     */
    public function __construct () {
    	
	}
	
	/**
	 * configApp
	 * Devuelve la configuracion del sistema
	 * 
	 * @param array $config - Arreglo con la configuracion de la aplicacion
	 * @param 
	 */
	public function configApp($config, $idXML = 'modulesconfig') {
		if ($idXML) {
			$xmlConfig = simplexml_load_file($config ['xml']['modulesconfig']);
			$arrModuleRef = explode('/', $config ['module_reference']);
			$config['bd'][$config ['module_name']] = $this->_parseXMLConf($arrModuleRef, $xmlConfig);
			$config['security'] = $this->configSecurity($xmlConfig);
			$config['report_systems'] = $this->configReport($xmlConfig);
		}
		return new Zend_Config($config, true);
	}
	
	protected function _parseXMLConf($arrModuleRef, $xmlConfig) {
		$bdMod = '';
		$gestorMod = '';
		$hostMod = '';
		$usuarioMod = '';
		$passwordMod = '';
		$esquemaMod = '';
		
		$tmpXMLModule = $xmlConfig;
		$this->__setNewAtributeValue($bdMod, $tmpXMLModule->conn['bd']);
		$this->__setNewAtributeValue($gestorMod, $tmpXMLModule->conn['gestor']);
		$this->__setNewAtributeValue($hostMod, $tmpXMLModule->conn['host']);
		$this->__setNewAtributeValue($usuarioMod, $tmpXMLModule->conn['usuario']);
		$this->__setNewAtributeValue($passwordMod, $tmpXMLModule->conn['password']);
		$this->__setNewAtributeValue($esquemaMod, $tmpXMLModule->conn['esquema']);

		foreach ($arrModuleRef as $arrFolder) {
			$tmpXMLModule = $tmpXMLModule->$arrFolder;
			$this->__setNewAtributeValue($bdMod, $tmpXMLModule->conn['bd']);
			$this->__setNewAtributeValue($gestorMod, $tmpXMLModule->conn['gestor']);
			$this->__setNewAtributeValue($hostMod, $tmpXMLModule->conn['host']);
			$this->__setNewAtributeValue($usuarioMod, $tmpXMLModule->conn['usuario']);
			$this->__setNewAtributeValue($passwordMod, $tmpXMLModule->conn['password']);
			$this->__setNewAtributeValue($esquemaMod, $tmpXMLModule->conn['esquema']);
		}
		$RSA = new ZendExt_RSA();
		$config['bd'] = $bdMod;
		$config['gestor'] = $gestorMod;
		$config['host'] = $hostMod;
		$config['usuario'] = $usuarioMod;
		$config['password'] = $RSA->decrypt ($passwordMod, '85550694285145230823', '99809143352650341179');
		$config['esquema'] = $esquemaMod;
		$config['seguridad'] = (string) $xmlConfig->security['ttl'];
		
		return $config;
	}
	
	private function __setNewAtributeValue(& $atribute, $newValue) {
		$tmpAtribute = (string) $newValue;
		if ($tmpAtribute)
			$atribute = $tmpAtribute;
	}

	public function addBdToConfig ($module, $moduleRef = '', $idXML = 'modulesconfig') {
		$xmlConfig = ZendExt_FastResponse::getXML('modulesconfig');
		$register = Zend_Registry::getInstance();
		$objConfig = $register->config;
		$arrModuleRef = explode('/', $moduleRef);
		$config['bd'][$module] = $this->_parseXMLConf($arrModuleRef, $xmlConfig);
		$newObjConfig = new Zend_Config($config, true);
		$register->config = $objConfig->merge($newObjConfig);
	}
	
	public function configSecurity($xmlConfig) {
		$configSec = array();
		$configSec['id'] 	   = $xmlConfig->security->id;
		$configSec['uri'] 	   = $xmlConfig->security->uri;
		$configSec['location'] = $xmlConfig->security->location;
		$configSec['wsdl'] 	   = $xmlConfig->security->wsdl;
		$configSec['ttl'] 	   = $xmlConfig->security->ttl;
		return $configSec;
	}

	public function configReport($xmlConfig) {
		$configRep = array();
		$configRep['uri_portal'] = $xmlConfig->report_systems->uri_portal;
		$configRep['uri_visor'] = $xmlConfig->report_systems->uri_visor;
		$configRep['token'] = $xmlConfig->report_systems->token;
		return $configRep;
	}
}
