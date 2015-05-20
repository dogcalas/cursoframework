<?php
class NomsalarioModel extends ZendExt_Model
{

    public function NomsalarioModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomSalario();
    }

    /**
     * Insertar un salario.
     *
     * @param unknown_type $pgrupocomple
     * @param unknown_type $pescalasalarial
     * @param unknown_type $psalario
     * @param unknown_type $pFini
     * @param unknown_type $pFfin
     * @return unknown
     */

    public function insertarSalario($pgrupocomple, $pescalasalarial, $psalario, $pTarifa, $pOrden, $pFini, $pFfin)
    {

        //$this->instance->idsalario			= $this->buscaridproximo();
        $this->instance->idgrupocomplejidad = $pgrupocomple;
        $this->instance->idescalasalarial = $pescalasalarial;
        $this->instance->salario = $psalario;
        $this->instance->tarifa = $pTarifa;
        $this->instance->orden = $pOrden;
        $this->instance->fechaini = $pFini;
        $this->instance->fechafin = $pFfin;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo(__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC25', $ee);
            return false;
        }
    }

    /**
     * Modificar un salario.
     *
     * @param unknown_type $pIdsalario
     * @param unknown_type $pgrupocomple
     * @param unknown_type $pescalasalarial
     * @param unknown_type $psalario
     * @param unknown_type $pFini
     * @param unknown_type $pFfin
     * @return unknown
     */

    public function modificarNomSalario($pIdsalario, $pGrupocomple, $pEscalasalarial, $pSalario, $pTarifa, $pOrden, $pFini, $pFfin)
    {

        try {

            $this->instance = $this->conn->getTable('NomSalario')->find($pIdsalario);
            $this->instance->idgrupocomplejidad = $pGrupocomple;
            $this->instance->idescalasalarial = $pEscalasalarial;
            $this->instance->salario = $pSalario;
            $this->instance->tarifa = $pTarifa;
            $this->instance->orden = $pOrden;
            $this->instance->fechaini = $pFini;
            $this->instance->fechafin = $pFfin;


            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                //echo(__FILE__.' '.__LINE__.' '.$ee->getMessage());
                throw new ZendExt_Exception('EC25', $ee);

            return false;
        }

    }


    /**
     * Busca todos los salario dado un limite y comienzo.
     *
     * @param unknown_type $limit
     * @param unknown_type $start
     * @return unknown
     */

    public function buscarNomsalario($limit, $start, $denom = false)
    {

        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->select("s.*,g.*,e.*")
                    ->from('NomSalario s')
                    ->innerJoin('s.NomGrupocomple g')
                    ->innerJoin('s.NomEscalasalarial e')
                    ->where("LOWER(e.denominacion) like '%$denom%'")

                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();
                return $result;
            } else {
                $result = $q->select("s.*,g.*,e.*")
                    ->from('NomSalario s')
                    ->innerJoin('s.NomGrupocomple g')
                    ->innerJoin('s.NomEscalasalarial e')
                    ->limit($limit)
                    ->offset($start)
                    ->orderby('orden')
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();
                return $result;
            }
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());
            return false;
        }
    }

    public function buscarNomsalarioPorgrupyescala($pIdg, $pIde)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select("s.*")
                ->from('NomSalario s')
                ->innerJoin('s.NomGrupocomple g')
                ->innerJoin('s.NomEscalasalarial e')
                ->where(" g.idgrupocomplejidad='$pIdg' and e.idescalasalarial='$pIde'")
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();
            return $result;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Contar la cantidad de salarios que hay.
     *
     * @return unknown
     */
    public function contNomsalario($denom = false)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);
            if ($denom) {
                $denom = strtolower($denom);
                $result = $q->select("s.*,g.*,e.*")
                    ->from('NomSalario s')
                    ->innerJoin('s.NomGrupocomple g')
                    ->innerJoin('s.NomEscalasalarial e')
                    ->where("LOWER(e.denominacion) like '%$denom%'")
                    ->execute()->count();

                return $result;
            } else {
                $result = $q->select("s.*,g.*,e.*")
                    ->from('NomSalario s')
                    ->innerJoin('s.NomGrupocomple g')
                    ->innerJoin('s.NomEscalasalarial e')
                    ->execute()->count();

                return $result;
            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function verificargrupoescala($Idg, $Ide)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select('count(idsalario) as cont')
                ->from('NomSalario s')
                ->where("idgrupocomplejidad='$Idg'and idescalasalarial='$Ide'")
                ->execute()
                ->toArray();

            return ($result[0]['cont'] >= 1) ? true : false;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    /**
     * Verificar si exite un salario.
     *
     * @param unknown_type $pId
     * @return unknown
     */

    public function existeNomsalario($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idsalario')
                ->from('NomSalario')
                ->where("idsalario ='$pId'")
                ->execute()
                ->count();
            return ($consulta != 0);
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }
    }

    public function usandoNonsalario($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->from('DatCargocivil')
                ->where("idsalario ='$pId'")
                ->execute()
                ->count();
            return ($consulta > 0);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }

    }


    /**
     * Eliminar un salario.
     *
     * @param unknown_type $pId
     * @return unknown
     */
    public function eliminarNomsalario($pId)
    {
        try {

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idsalario')
                ->from('NomSalario')
                ->where("idsalario = '$pId'")
                ->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $e) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $e->getMessage());

            return false;
        }

    }


    /**
     * Tomar el proximo id para insertar;
     *
     * @return unknown
     */
    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(s.idsalario) as maximo')
            ->from('NomSalario s')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;

    }


}

?>
