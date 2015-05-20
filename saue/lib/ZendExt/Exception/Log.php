<?php	
	/**
	 * ZendExt_Exception_Log
	 * 
	 * Gestor de excepciones de log
	 * 
	 * @author Omar Antonio Diaz Pena
	 * @package ZendExt
	 * @subpackage ZendExt_Exception
	 * @copyright Centro UCID
	 * @version 1.0-0
	 */
	class ZendExt_Exception_Log extends ZendExt_Exception_Base 
	{
		/**
		 * ZendExt_Exception_Log
		 * 
		 * Constructor de la clase
		 * 
		 * @param ZendExt_Exception_Log $pParent - Excepcion padre
		 * @return void.
		 */
		public function ZendExt_Exception_Log ($pParent)
		{
			parent::ZendExt_Exception_Base ($pParent);
		}	

		/**
		 * toString
		 * 
		 * Convierte la excepcion a cadena
		 * 
		 * @return string
		 */
		public function toString ()
		{
			$fecha = date ('d/m/y'); $hora = date('h:i:s'); 

			$interiores = $this->getInnerExceptions ();
			$interiores = ($interiores == '') ? '<No posee>' : $interiores;
			
			$str = "
----------------------------------------------------------------------
Excepcion: 
Codigo: " . $this->parent->getIdException () . "
Clase: " . $this->parent->getClass ()."
Metodo: ". $this->parent->getMethod () ."
Descripcion: ". $this->parent->getMessage()/*$this->parent->getDescription()*/ ."
Traza: 
". $this->parent->getTraceAsString() ."
Linea: ". $this->parent->getLine () ."	
Fichero: ". $this->parent->getFile () ."
Fecha: $fecha
Hora:  $hora
Excepciones Interiores: 
$interiores
";	 
			 return $str;
		}
		
		/**
		 * write
		 * 
		 * Escribe la excepcion en cadena en un fichero log
		 * 
		 * @param string $pStr - excepcion en cadena
		 * @return void
		 */
		public function write ($pStr)
		{	
			$configInstance = Zend_Registry::get('config');
			//Se intenta abrir el fichero para escribir al final del mismo
			$file = @fopen($configInstance->exception_log_file, "a");
			if (!$file) //Se intenta crear el fichero para escribir desde el principio
				$file = @fopen($configInstance->exception_log_file, "w+");
			if ($file) { //Si se pudo abrir o crear el fichero
				//Escribo el log y cierro el fichero
				fputs ($file, $pStr);
				fclose($file);
			}
			else {
				//Hacer algo para avisar al administrador del sistema un correo 
				//para que restaure los permisos de escritura de apache en el MT
			}
		}
		
		/**
		 * handle
		 * 
		 * Gestionar las excepciones de log
		 * 
		 * @return void.
		 */
		public function handle ()
		{	
			$this->write($this->toString());
		}
	}
