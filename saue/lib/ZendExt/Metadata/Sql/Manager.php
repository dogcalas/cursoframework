<?php
    /**
     * Clase para la comunicación de las estructuras físicas
     *
     * @author Omar Antonio Díaz Peña
     * @version 1.0
     * @package planificacion
     * @subpackage metadatos
     */

 class ZendExt_Metadata_Sql_Manager {
     private $_field;
     private $_relation;
     private $_table;
     private $_conn;
         /**
          * Retorna un arreglo con los esquemas de la BD actual.
          *
          * @return array
          */
     public function obtenerEsquemas () {
                     //$conn=Zend_Registry::get('conexion');
         return $this->_conn->execute ($this->_table->obtenerEsquemas ())->fetchAll ();
     }

             public function cargarEsquemasEnArbol () {
         return $this->_conn->execute ($this->_table->cargarEsquemasEnArbol ())->fetchAll ();
     }

             public function crearSecuencia ($pTabla) {
        return $this->_conn->exec ($this->_field->crearSecuencia ($pTabla));
     }

     public function adicionarCampoALaLlavePrimaria ($pTabla) {
        return $this->_conn->exec ($this->_field->adicionarCampoALaLlavePrimaria ($pTabla));
     }

     public function ponerNoNulo ($pTabla) {
        return $this->_conn->exec ($this->_field->ponerNoNulo ($pTabla));
     }

     public function crearRelacion ($pValue,$pFKName) {
        return $this->_conn->exec ($this->_relation->crearRelacion ($pValue,$pFKName));
     }

     public function ultimaForeignKey($pSchema, $pTable)
     {
            return $this->_conn->execute ($this->_relation->ultimaForeignKey($pSchema, $pTable))->fetchAll ();
     }

     public function crearFuncionesArbol ($pValue) {
        return $this->_conn->exec ($this->_field->crearFuncionesArbol ($pValue));
     }

     public function crearIndiceUnico ($pSource, $pTarget) {
        return $this->_conn->exec ($this->_relation->crearIndiceUnico ($pSource, $pTarget));
     }

     public function definirValorPorDefecto ($pField, $pValue) {
        return $this->_conn->exec ($this->_field->definirValorPorDefecto ($pField, $pValue));
     }

     public function eliminarSecuencia ($pNombre) {
        return $this->_conn->exec ($this->_table->eliminarSecuencia ($pNombre));
     }

     public function eliminarFuncsArbol ($pNombre) {
        return $this->_conn->exec ($this->_table->eliminarFuncsArbol ($pNombre));
     }

     public function eliminarTabla ($pTabla) {
        return $this->_conn->exec ($this->_table->eliminarTabla ($pTabla->esquema, $pTabla->tabla));
     }

     public function adicionarTabla ($pEsquema, $pTabla, $pArbol) {
        return $this->_conn->exec ($this->_table->adicionarTabla ($pEsquema, $pTabla, $pArbol));
     }

     public function existeTablaEnEsquema ($pEsquema, $pTabla) {
        return $this->_conn->execute ($this->_table->existeTablaEnEsquema ($pEsquema, $pTabla))->fetchAll ();
     }

     public function cargarTablasEnArbol ($pEsquema) {
        return $this->_conn->execute ($this->_table->cargarTablasEnArbol ($pEsquema))->fetchAll ();
     }

     public function adicionarCampo ($table, $denominacion, $tipo, $longitud, $permite_nulo, $secuencia, $llave_primaria) {
             return $this->_conn->exec ($this->_field->adicionarCampo ($table, $denominacion, $tipo, $longitud, $permite_nulo, $secuencia, $llave_primaria));
    }

     public function eliminarCampo ($field) {
             return $this->_conn->exec ($this->_field->eliminarCampo ($field));
    }
    public function obtenerCamposDeTabla($schema,$table) {
            return $this->_conn->execute ($this->_field->obtenerCamposDeTabla ($schema,$table))->fetchAll ();
    }

    public function obtenerPrimaryKeyDeTabla($schema,$table) {
            return $this->_conn->execute ($this->_field->obtenerPrimaryKeyDeTabla ($schema,$table))->fetchAll ();
    }

    public function getConnection(){
        $transactionManager = ZendExt_Aspect_TransactionManager::getInstance();
        $configApp = new ZendExt_App_Config();
        $configApp->addBdToConfig('xmetacomponent', 'xmetacomponent');
        return $transactionManager->openConections('xmetacomponent');
    }
    
    public function setCurrentConnection($module) {
    	$doctrineManager = Doctrine_Manager::getInstance();
        $doctrineManager->setCurrentConnection($module);
    } 

    public function __construct () {
         $module_name = Zend_Registry :: get ('config')->module_name;

         $config = Zend_Registry :: get ('config')->bd->$module_name;
         $dbms = strtoupper($config->gestor);

         $fieldname     = 'ZendExt_Metadata_Sql_'. $dbms . '_Field';
         $tablename     = 'ZendExt_Metadata_Sql_'. $dbms . '_Table';
         $relationname  = 'ZendExt_Metadata_Sql_'. $dbms . '_Relation';

         $this->_field = new $fieldname ();
         $this->_table = new $tablename ();
         $this->_relation = new $relationname ();

         $this->_conn = $this->getConnection();
    }
}
?>
