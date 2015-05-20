<?php
	/**
	 * ZendExt_FastResponse_AbstractPDONomLoader
	 * 
	 * Clase abstracta para los cargadores que usan las librerias PDO
	 * 
	 * @author: Omar Antonio Diaz Peña
	 * @package: ZendExt
	 * @subpackage ZendExt_FastResponse
	 * @copyright: UCID-ERP Cuba
	 * @version 1.0-0
	 */
	abstract class ZendExt_FastResponse_AbstractPDONomLoader implements ZendExt_FastResponse_INomLoader
	{
		/**
		 * Conexion a la BD
		 * 
		 * @var PDO_Conexion
		 */
		protected $conn;
		
		/**
		 * Datos del nomenclador
		 * 
		 * @var array
		 */
		protected $data;

		/**
		 * Connect
		 * 
		 * Creacion de la conexion a la BD. 
		 * 
		 * @throws ZendExt_Exception - excepciones declaradas en el xml de excepciones
		 * @return void
		 */
		public function  Connect ()
		{
			$configInstance = Zend_Registry::get ('config')->bd;			
			try 
			{
				$result = $this->conn = new PDO ("$configInstance->gestor:host=$configInstance->host;dbname=$configInstance->bd", 
								  			  	  $configInstance->usuario, 
 			  								  	  $configInstance->password);
			}
			
			catch (PDOException $pException)
			{
				throw new ZendExt_Exception('FR001');
			}
		}	
		
		/**
		 * getConn
		 * 
		 * Devuelve la conexión a la BD
		 * 
		 * @return Doctrine_Conexion - conexión a la BD		 
		 */
		public function getConn ()
		{
			return $this->conn;
		}
		
		/**
		 * Data
		 * 
		 * Devuelve los datos del nomenclador
		 * 
		 * @return array  - datos del nomenclador	 
		 */
		public function Data ()
		{
			return $this->data;	
		}	
		
		/**
		 * Count
		 * 
		 * Cuenta las filas obtenidas del nomenclador
		 * 
		 * @return integer  - cantidad de filas obtenidas
		 */
		public function Count ()
		{
			return count ($this->data);
		}
	}
