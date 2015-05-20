<?php
class  ValordefectoModel extends ZendExt_Model
{

    public function ValordefectoModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomValorDefecto();
    }


    /** --------------------------------------------
     * Inserta un valor nuevo
     *
     * @param int $pFila
     * @param int $pCampo
     * @param mixed $pValor
     * @return bool
     */
    public function insertar($idcampo, $id, $valor)
    {

        $this->Instancia();

        $this->instance->idvalordefecto = $this->buscaridproximo();
        $this->instance->idcampo = $idcampo;
        $this->instance->valor = $valor;
        $this->instance->valorid = $id;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /** --------------------------------------------
     * ELimina los valores por defecto
     *
     * @param int $pidFila
     * @return bool
     */
    public function eliminarValor($idValor)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idvalordefecto')->from('NomValorDefecto')
                ->where("idvalordefecto = '$idValor'")
                ->execute();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /** --------------------------------------------
     * Borrar la instancia vieja que poseia el modelo
     *
     */
    public function Instancia()
    {
        $this->instance = null;
        $this->instance = new NomValorDefecto();
    }

    public function modificar($idvalordefecto, $idValor, $valor)
    {

        $this->Instancia();
        $this->instance = $this->conn
            ->getTable('NomValorDefecto')
            ->find($idvalordefecto);
        $this->instance->valor = $valor;
        $this->instance->valorid = $idValor;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /** -----------------------------------------
     * Buscar el proximo id a escribir
     *
     * @return int
     */
    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(a.idvalordefecto) as maximo')
            ->from('NomValorDefecto a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;
    }

    /** -------------------------------------------------
     * Lista Todos los campos de una tabla
     */
    public function buscarValoresCampo($idcampo)
    {
        try {

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select('t.*')
                ->from('NomValorDefecto t')
                ->where("t.idcampo = '$idcampo' ")
                ->orderBy(' t.idcampo ')
                ->execute()
                ->toArray();
            return $result;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function eliminarValorbyCampo($idcampo)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idvalordefecto')->from('NomValorDefecto')
                ->where("idcampo = '$idcampo'")
                ->execute();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

}


?>
