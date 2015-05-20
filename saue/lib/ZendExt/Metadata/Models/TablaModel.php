<?php
    class ZendExt_Metadata_Models_TablaModel extends ZendExt_Model {
        private $_manager;
        private $_ciar;
        private $_campo_model;
        private $_relation_model;
        private $_tabla_domain;
        private $_campo_domain;
        private $_module;
        
    public function  __construct() {
        parent :: ZendExt_Model ();
        $this->_manager         = new ZendExt_Metadata_Sql_Manager();
        $this->_campo_model     = new ZendExt_Metadata_Models_CampoModel();
        $this->_relation_model  = new ZendExt_Metadata_Models_RelacionModel();
        $this->_tabla_domain    = new DatTabla();
        $this->_campo_domain    = new DatCampo();
        $doctrineManager = Doctrine_Manager::getInstance();
        $this->_module = $doctrineManager->getConnectionName($this->conn);

    }

    public function cargarTablas($limit,$start) {
        $data->total = $this->_tabla_domain -> contarTablas ();
        $data->data  = $this->_tabla_domain -> cargarTablas ($limit, $start);
        return $data;
    }
    
    public function buscarEnTablas($query, $limit, $start) {                
        return $this->_tabla_domain -> buscarEnTablas ($query, $limit, $start);        
    }

    public function cargarNomencladores() {
        return $this->_tabla_domain->cargarNomencladores();
    }

    public function cargarTiposCompuestos(){
        $query = Doctrine_Query::create();
        return $query->select('denominacion')
                     ->from('NomTipoComponenteCompuesto')
                     ->execute()
                     ->toArray(true);
    }

    public function eliminarTabla ($tId) {

        $this->_manager->setCurrentConnection('xmetacomponent');
        //Obtengo la tabla que deseo eliminar
        $tabla = Doctrine::getTable('DatTabla')->find($tId);
        //Valido si la tabla en efecto se puede eliminar.
        //Para ello hay q tener en cuenta que si tiene una relacion con otra tabla
        //Donde ella exporte una llave foranea y tiene datos la otra tabla, no se puede eliminar
        //Otras validaciones por el estilo

        //Si se puede eliminar correctamente.
        //Obtengo los campos de la tabla a eliminar
        $campos = $tabla->DatCampo;

        foreach ($campos as $campo)  {
            $this->_campo_model->eliminarCampo ($campo->idcampo,true);
        }
        $tabla->delete ();
        if($tabla->importada != 1){
            if ($tabla->arbol) $this->_manager->eliminarFuncsArbol ($tabla);
            $this->_manager->eliminarTabla ($tabla);
            $nombre_seq = str_replace('nom', 'sec', $tabla->tabla); $nombre_seq .= '_seq';
            $this->_manager->eliminarSecuencia ("{$tabla->esquema}.{$nombre_seq}");
        }     
        //Elimino recursivamente todas las tablas que se relacionan con la actual.        
        /*$relacionadas = $this->_tabla_domain->obtenerTablasDestinoDe($tId);
        foreach($relacionadas as $idTabla)
		$this->eliminarTabla($idTabla['idtabla']);*/
        $this->_manager->setCurrentConnection($this->_module);
        return true;
    }

    function cargarArbolEsquemas($nodo) {
            return ($nodo != '0') ? $this->_manager->cargarTablasEnArbol($nodo) : $this->_manager->cargarEsquemasEnArbol();
    }
		
    public function  adicionarTabla ($esquema, $tabla,$arbol, $alias = '', $values = array() ) {

        $this->_manager->setCurrentConnection('xmetacomponent');
        $table = (substr ($tabla, 0, 4)) != 'nom_' ? "nom_$tabla" : $tabla;

            $existsTable = $this->_manager->existeTablaEnEsquema($esquema,$table);
        
            if($existsTable[0]['count'] == 0) {
                    $tbl = new DatTabla();
                    $tbl->esquema = $esquema;
                    $tbl->tabla = strtolower($table);
                    $tbl->arbol = ($arbol) ? 1 : 0;
                    $tbl->importada = 0;
                    $tbl->alias = $alias;
                    $tbl->save ();

                    $this->_manager->adicionarTabla ($esquema, $table, $arbol,$alias);

                    $module_name = Zend_Registry :: get ('config')->module_name;

                    $config = Zend_Registry :: get ('config')->bd->$module_name;
                    $gestor = $config->gestor;

                    $idtipodatogestor = $this->_campo_domain->getTipoDatoGestor (1, $gestor);
                    $campo = $this->_campo_model->adicionarCampo ("id".substr ($tbl->tabla, 4, strlen($tbl->tabla)-4), $tbl->idtabla, $idtipodatogestor, 0, 19, null, 1, 0, false);

                    $tmp = "{$campo->DatTabla->esquema}.{$campo->DatTabla->tabla}";
                    $tmp = str_replace('nom', 'sec', $tmp); $tmp = $tmp.'_seq';

                    $this->_manager->crearSecuencia($tmp);
                    $this->_manager->definirValorPorDefecto($campo, "nextval ('$tmp')");

                    if ($arbol == 1) {
                            $tipodatocadena = $this->_campo_domain->getTipoDatoGestor (2, $gestor);
                            $idpadre = $this->_campo_model->adicionarCampo ("id".substr ($tbl->tabla, 4, strlen($tbl->tabla)-4)."padre", $tbl->idtabla, $idtipodatogestor, 0, 19, null, 0, 0, false);
                            $this->_campo_model->adicionarCampo ('ordenizq', $tbl->idtabla, $idtipodatogestor, 0, 19, null, 0, 0, false);
                            $this->_campo_model->adicionarCampo ('ordender', $tbl->idtabla, $idtipodatogestor, 0, 19, null, 0, 0, false);
                            $this->_campo_model->adicionarCampo ('denominacion', $tbl->idtabla, $tipodatocadena, 0, 255, $values, 0, 0, true);
                            $this->_relation_model->crearRelacion ($campo, $idpadre, 0, 0);
                            $this->_manager->crearFuncionesArbol($tbl);
                    }
				
                $this->_manager->setCurrentConnection($this->_module);
                
                return $tbl->toArray();
            }
            else
                throw new ZendExt_Exception ('XMT001');
    }
		
    public function importarTabla($esquema,$tabla) {

        $tbl = new DatTabla ();
        $tbl->esquema = $esquema;
        $tbl->tabla = $tabla;
        $tbl->arbol = 0;
        $tbl->importada = 1;
        $tbl->save ();

        $module_name = Zend_Registry :: get ('config')->module_name;

        $config = Zend_Registry :: get ('config')->bd->$module_name;
        $gestor = $config->gestor;

        $idtipodatogestor = $this->_campo_domain->getTipoDatoGestor (1, $gestor);

        $llave_primaria = $this->_manager->obtenerPrimaryKeyDeTabla($esquema,$tabla);

        $campo = new DatCampo();
        $campo->denominacion = $llave_primaria[0]['column_name'];
        $campo->llave_primaria = 1;
        $campo->secuencia = 1;
        $campo->permite_nulo = 0;
        $campo->longitud = 19;
        $campo->idtabla = $tbl->idtabla;
        $campo->idtipodatogestor = $idtipodatogestor;
        $campo->save();

        return $tbl->toArray();
    }
		
    public function cargaresquemas() {
        $schemas = $this->_manager->obtenerEsquemas ();
        $result = array ();

        foreach ($schemas as $schema)
                $result[] = array ('esquema' => $schema ['schema']);

        $data->data = $result;
        $data->total = sizeof ($result);

        return $data;
    }
}
?>
