<?php
    class ZendExt_Metadata_Models_RelacionModel extends ZendExt_Model {
        private $_manager;
        private $_module;
        private $_campo_model;

        public function  __construct(){
            parent :: ZendExt_Model ();
            $this->_manager = new ZendExt_Metadata_Sql_Manager();
            
            $doctrineManager = Doctrine_Manager::getInstance();
            $this->_module = $doctrineManager->getConnectionName($this->conn);
        }

        function cargarRelaciones ($idtable) {
            $q = Doctrine_Query :: create ();
			
            return $q->select('r.actualizar_cascada,r.eliminar_cascada,
				d.idcampo as idcdestino,d.denominacion as campo_destino,
				o.idcampo as idcorigen,o.denominacion as campo_origen,
				t.tabla as tdestino,to.tabla as torigen')
			->from ('DatRelacion r')
			->innerJoin('r.Destino d')
			->innerJoin('r.Origen o')
			->innerJoin('d.DatTabla t')
			->innerJoin('o.DatTabla to')
			->where('t.idtabla = ? OR to.idtabla = ?',array($idtable,$idtable))
			->execute ()
			->toArray (true);
        }           
                    
        function contarRelaciones () {
            $q = Doctrine_Query :: create ();
            return $q->select ('COUNT(campo_destino) as total')->from ('DatRelacion r')->execute ()->getFirst ()->total;
        }

        function crearRelacion ($pSource, $pTarget, $pActulizar, $pEliminar) {
            $relacion = new DatRelacion ();
            $relacion->campo_origen = $pSource;
            $relacion->campo_destino = $pTarget;
            $relacion->eliminar_cascada = $pEliminar;
            $relacion->actualizar_cascada = $pActulizar;
            $relacion->save ();
			
            $table   = $relacion->Destino->DatTabla;

            $foreignkey   = $this->_manager->ultimaForeignKey($table->esquema, $table->tabla);
            $lastforeignk = explode($table->tabla."_fk",$foreignkey[0]['max']);
            $fkname = ($foreignkey)?($table->tabla."_fk".($lastforeignk[1]+1)):$table->tabla."_fk";
			
            $this->_manager->crearRelacion ($relacion,$fkname);
        }

        public function relacionarTablas ($esquemaorigen,$tablaorigen,$idtabladestino,$actualizar = 1, $eliminar = 1) {
            $this->_manager->setCurrentConnection('xmetacomponent');
            
            $tabla_origen  = DatTabla :: existeTabla($esquemaorigen,$tablaorigen);
            $campo_origen  = DatCampo :: obtenerLLavePrimaria($tabla_origen->idtabla);
            
            if($campo_origen) {
                $this->_campo_model = new ZendExt_Metadata_Models_CampoModel();
                $campo_destino = $this->_campo_model->adicionarCampo($campo_origen->denominacion,$idtabladestino,1,0,19,null,0,0,false);
            }
            
            $this->_manager->setCurrentConnection('xmetacomponent');
                $this->crearRelacion ($campo_origen->idcampo, $campo_destino->idcampo, $actualizar, $eliminar);

            $this->_manager->setCurrentConnection($this->_module);
            return true;
    }
}
?>
