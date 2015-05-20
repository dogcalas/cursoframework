<?php
	/**
	 * ZendExt_Exception_Presentation
	 * 
	 * Gestor de excepciones de presentacion
	 * 
	 * @author Omar Antonio Diaz Peña
	 * @package ZendExt
	 * @subpackage ZendExt_Exception
	 * @copyright Centro UCID
	 * @version 1.0-0
	 */
	class ZendExt_Exception_Presentation extends ZendExt_Exception_Base 
	{
		/**
		 * ZendExt_Exception_Presentation
		 * 
		 * Constructor de la clase
		 * 
		 * @param ZendExt_Exception_Presentation $pParent - Excepcion padre
		 * @return void.
		 */
		public function ZendExt_Exception_Presentation($pParent)
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

			//3 - para tiempo de explotación y 4 para tiempo de desarrollo.
			$send->codMsg = 3;
			$send->mensaje = $strException;
			//Este es para mandar en el json los detalles de la excepción.
			$send->detalles = $this->getInnerExceptions ();
			if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) //Si la peticion es por ajax
				echo json_encode ($send); //Se imprime la excepcion en codigo json
			else //Si la peticion no es por ajax
			{
				//Obtengo una instancia del registro.
				$registro = Zend_Registry::getInstance();
				
				//Obtengo una instancia del GlobalConcept
				$global = ZendExt_GlobalConcept::getInstance();

				//Obtengo el idioma y el tema
				$tema = $global->Perfil->tema;
			
				//Obteniendo la direccion de ExtJS y UCID
				$dir_tema = $registro->config->extjs_themes_path . $tema . '/';

				//Se imprime la excepcion en codigo HTML
				$msg_error = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				$msg_error .= '<link href="'.$dir_tema.'css/icon-clases.css" rel="stylesheet" type="text/css" media="screen"/></head><body>';
				$msg_error .= '<pre><table width="100%" border="0"><tr><td width="7%"><div class="iconoERROR" style="margin:10 0 0 10"></div></td>';
				$msg_error .= '<td width="93%"><div style="margin:10px 0px 0px 0px;font-size:9pt" ><b>ERROR</b><br>'.$send->mensaje.'</div></td></tr>';
				if ($send->codMsg == 4)
					$msg_error .= "<tr><td colspan = \"2\">&nbsp;</td></tr><tr><td colspan = \"2\">{$send->detalles}</td></tr></table></pre></body>";
				else
					$msg_error .= "</table></pre></body>";
				echo $msg_error;
			}
		}
	}
