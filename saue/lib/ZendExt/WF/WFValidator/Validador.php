<?php
class ZendExt_WF_WFValidator_Validador{
		
		
//atributo
        private $objscanner;        
        private $objparser;
        
      private function __construct($objpaquete)
        {
           
            $this->objscanner = new ZendExt_WF_WFValidator_AnalizadorLexico($objpaquete);       
            $this->objparser = new ZendExt_WF_WFValidator_AnalizadorSintactico($this->objscanner);
		}
	static public function getInstance($objpaquete) {
			static $objinstance;
			if (!isset($objinstance))
			$objinstance = new self($objpaquete);
			return $objinstance;
		}
	public function validarDiagrama()
	{	
			$this->objparser->parse(); 
			if (count($this->objparser->getNotifications())>0)
			{
				
				$errores=$this->objparser->getNotifications();
				
				return $errores;
			}
			else
			{
				return $errores;
			}			
		}
		
		
}

?>

