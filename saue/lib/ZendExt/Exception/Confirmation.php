<?php
	/**
	 * ZendExt_Exception_Confirmation
	 * 
	 * Gestor de confirmaciones
	 * 
	 * @author Omar Antonio Diaz Peña
	 * @package ZendExt
	 * @subpackage ZendExt_Exception
	 * @copyright Centro UCID
	 * @version 1.0-0
	 */
	class ZendExt_Exception_Confirmation extends ZendExt_Exception_Base 
	{
		/**
		 * ZendExt_Exception_Presentation
		 * 
		 * Constructor de la clase
		 * 
		 * @param ZendExt_Exception_Presentation $pParent - Excepcion padre
		 * @return void.
		 */
		public function ZendExt_Exception_Confirmation($pParent)
		{
			parent :: ZendExt_Exception_Base($pParent);			
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
			return "
						----------------------------------------------------------------------
	   				   Excepción: 
					   Código: " . $this->parent->getIdException () . ",
					   Descripción: ". $this->parent->getDescription();
		}
		
		/**
		 * handle
		 * 
		 * Gestionar las excepciones de presentacion
		 * 
		 * @return void.
		 */
		public function handle ()
		{
			$strException = (string) $this->parent->getDescription ();
			$send->codMsg = 2;
			$send->mensaje = $strException;
			//Este es para mandar en el json los detalles de la excepción.
			$send->detalles = $this->getInnerExceptions ();
			if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) //Si la peticion es por ajax
				echo json_encode ($send); //Se imprime la excepcion en codigo json
		}
	}
