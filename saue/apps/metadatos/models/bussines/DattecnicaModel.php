<?php
class DattecnicaModel extends ZendExt_Model
{
    public function DattecnicaModel()
    {
        parent::ZendExt_Model();
        $this->instance = new DatTecnica();
    }

    public function existeDatTecnica($pId, $idcargo)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idtecnica')
                ->from('DatTecnica')
                ->where("idnomtecnica ='$pId' AND idestructura = '$idcargo'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    public function buscarDatTecnica($idestructura, $limit = 10, $start = 0)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select('t.idtecnica,t.dentecnica,t.abrevtecnica,
										t.codtecnica,t.vaplantilla,nt.idnomtecnica,
										nt.idestructura,nt.idtecnica,nt.ctp,nt.ctg')
                //->from('NomTecnica t')
                //->innerJoin('t.DatTecnica nt ')
                ->from('DatTecnica nt')
                ->innerJoin('nt.NomTecnica t')
                ->where("nt.idestructura='$idestructura'")
                ->limit($limit)
                ->offset($start)
                //->orderby('t.orden')
                ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                ->execute();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    public function buscardatosTecnica($idtecnica)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select('nt.dentecnica,nt.abrevtecnica,nt.codtecnica,nt.vaplantilla,t.idestructura,t.idtecnica,t.ctp,t.ctg')
                ->from('DatTecnica t')
                ->innerJoin('t.NomTecnica nt ')
                ->where("t.idtecnica='$idtecnica'")
                ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                ->execute();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }


    public function buscarDatTecnicaLineal($idestructura)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select("nt.idtecnica as id,t.idtecnica,t.dentecnica,t.abrevtecnica,
								t.codtecnica,t.vaplantilla,CONCAT(nt.ctg,
								 CONCAT('  ', CONCAT(nt.ctp , CONCAT('  ' ,t.dentecnica ) ))) as text,
								 'tecnica' as tipo, 1 = 0 as leaf
            					, 'geticon?icon=11' as icon,nt.idnomtecnica
									
								")
                //->from('NomTecnica t')
                //->innerJoin('t.DatTecnica nt ')
                ->from('DatTecnica nt')
                ->innerJoin('nt.NomTecnica t ')
                ->where("nt.idestructura='$idestructura'")
                //->orderby('t.orden')
                ->setHydrationMode(Doctrine:: HYDRATE_ARRAY)
                ->execute();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }


    public function insertarDatTecnica($idestructura, $idtecnica, $ctp, $ctg)
    {

        $this->instance->idestructura = $idestructura;
        $this->instance->idnomtecnica = $idtecnica;
        $this->instance->ctp = $ctp;
        $this->instance->ctg = $ctg;


        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }


    function eliminarTecnica($idestructura, $idtecnica)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idestructura,idtecnica')
                ->from('DatTecnica')
                ->where("idestructura = '$idestructura' AND idtecnica='$idtecnica'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

        }
    }

    public function modificartecnica($pidtecnica, $pcantidad, $ctg)
    {

        $this->instance = $this->conn->getTable('DatTecnica')->find($pidtecnica);
        $this->instance->ctp = $pcantidad;
        $this->instance->ctg = $ctg;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


}

?>