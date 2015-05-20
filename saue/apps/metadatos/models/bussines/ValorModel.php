<?php
class  ValorModel extends ZendExt_Model
{

    public function ValorModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomValorestruc();
    }


    /** --------------------------------------------
     * Inserta un valor nuevo
     *
     * @param int $pFila
     * @param int $pCampo
     * @param mixed $pValor
     * @return bool
     */
    public function insertar($pFila, $pCampo, $pValor)
    {

        $this->Instancia();
        $this->instance->idfila = $pFila;
        $this->instance->idcampo = $pCampo;
        $this->instance->valor = $pValor;

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
     * ELimina los valores de la fila
     *
     * @param int $pidFila
     * @return bool
     */
    public function eliminarValorFila($pidFila)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idfila')->from('NomValorestruc')
                ->where("idfila = '$pidFila'")
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
        $this->instance = new NomValorestruc();
    }

    public function modificar($pFila, $pCampo, $pValor)
    {

        $this->Instancia();
        $this->instance = $this->conn->getTable('NomValorestruc')->find(array($pFila, $pCampo));
        if (!is_object($this->instance))
            return false;
        $this->instance->valor = $pValor;

        try {
            if (is_object($this->instance))
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