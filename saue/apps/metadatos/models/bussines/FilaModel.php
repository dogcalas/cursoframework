<?php
class FilaModel extends ZendExt_Model
{

    public function FilaModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomFilaestruc();
    }

    /** --------------------------------------------
     * Inserta un valor nuevo
     *
     * @param int $pestructura
     * @param int $pidTabla
     * @return bool
     */
    public function insertar($pidTabla)
    {
        try {
            $this->Instancia();
            // buscar el ultimo valor de la fila de la tabla
            //$idFila							= $this->buscaridproximo( );
            //$this->instance->idfila			= $idFila;
            $this->instance->idnomeav = $pidTabla;


            $this->instance->save();


            return $this->instance->idfila;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function insertarConPadre($pidTabla, $idPadre)
    {
        try {
            $this->Instancia();
            // buscar el ultimo valor de la fila de la tabla
            //$idFila							= $this->buscaridproximo( );
            //$this->instance->idfila			= $idFila;
            $this->instance->idnomeav = $pidTabla;
            if ($idPadre != 'Estructuras')
                $this->instance->idpadre = $idPadre;

            $this->instance->save();

            $cadenaDominio_0 = str_pad('{', 100, '0,', STR_PAD_RIGHT) . '}';
            $sqlActualizaDominio = "update
    					mod_estructuracomp.nom_filaestruc
    					set dominiofila = '$cadenaDominio_0'
    					where idfila = {$this->instance->idfila}";

            $this->conn->execute($sqlActualizaDominio);

            $sqlActualizaDominio = "update
    					mod_estructuracomp.nom_filaestruc
    					set dominiofila[1] = 1::bit
    					where idfila = {$this->instance->idfila}";
            $this->conn->execute($sqlActualizaDominio);

            return $this->instance->idfila;
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
    public function eliminarFila($pidFila)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);


            $result = $query->delete('idfila')->from('NomFilaestruc')
                ->where("idfila = '$pidFila'")
                ->execute();
            return true;
        } catch (Doctrine_Exception $ee) {
            //if(DEBUG_ERP)
            //echo (__FILE__.' '.__LINE__.' '.$ee->getMessage());
            throw new ZendExt_Exception('EC22', $ee);
            return false;
        }
    }

    /** --------------------------------------------
     * Devuelve las filas de una tabla
     *
     * @param int $idTabla
     * @return array
     */
    public function buscarFilas($idTabla)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->from('NomFilaestruc')
                ->where("idnomeav = '$idTabla'")
                ->execute();
            return $result->toArray();
        } catch (Doctrine_Exception $ee) {
            return false;
        }
    }

    /** --------------------------------------------
     * Devuelve las filas de una tabla
     *
     * @param int $idTabla
     * @return array
     */
    public function eliminarFilasTabla($idTabla)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);


            $result = $query->delete('idnomeav')->from('NomFilaestruc')
                ->where("idnomeav = '$idTabla'")
                ->execute();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /** --------------------------------------------
     * Devuelve el proximo id de la tabla
     *
     * @return int
     */
    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(a.idfila) as maximo')
            ->from('NomFilaestruc a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;

        return $proximo;

    }

    /** --------------------------------------------
     * Borrar la instancia vieja que poseia el modelo
     *
     */
    public function Instancia()
    {
        $this->instance = null;
        $this->instance = new NomFilaestruc();
        //print_r($this->instance); die;
    }

    public function buscarFilaEstructura($idFila)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->from('NomFilaestruc')
                ->where("idfila = '$idFila'")
                ->execute();
            return $result->toArray();
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }
}


?>
