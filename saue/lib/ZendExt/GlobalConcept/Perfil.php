<?php
/**
* ZendExt_GlobalConcept_Perfil
*Clase para obtener los datos del perfil por defecto del concepto "Configuracion"
*
* @author Elianys Hurtado Sola
* @package ZendExt
* @subpackage GlobalConcept
* @copyright ERP Cuba 
* @version 1.5.0
*/
class ZendExt_GlobalConcept_Perfil {
	
	public $tema = 'default';
	
	public $portal = 'desktopaction';
	
	public $idioma = 'es';
	
	public $usuario = 'instalacion';
	
	public $idestructuracomun = 0;

	public $denomestructuracomun = 'instalacion';
    
	public $certificado = 0;
	
	public $idusuario = 0;
	
	public $idarea = 0;

	public $idcargo = 0;

	public $iddominio = 1;
        
        public $dominio="Dominio de Instalación";

	public $accesodirecto = 0;
	
	public $caduca = 0;
		
	public function __construct() {
		$register = Zend_Registry::getInstance();
		$perfil = $register->session->perfil;
		if ($perfil) {
			$this->caduca = $perfil['caduca'];
			$this->usuario = $perfil['usuario'];
			$this->tema = $perfil['tema'];
			$this->portal = $perfil['portal'];
			$this->idioma = $perfil['idioma'];
			$this->iddominio = $perfil['iddominio'];
                        $this->dominio = $perfil['dominio'];
			$this->idestructuracomun = $perfil['identidad'];
			$this->denomestructuracomun = $perfil['entidad'];
			$this->idusuario = $perfil['idusuario'];
			$this->idarea = $perfil['idarea'];
			$this->idcargo = $perfil['idcargo'];
			$this->certificado = $register->session->certificado;
			$this->accesodirecto = $perfil['cantidad'];
			if (isset($perfil['dinamico']))
				foreach ($perfil['dinamico'] as $camposdinamicos)
					$this->$camposdinamicos = $perfil[$camposdinamicos];
		}
	}
	
	public function ObtenerConcepto() {
		return $this;
	}
}

?>
