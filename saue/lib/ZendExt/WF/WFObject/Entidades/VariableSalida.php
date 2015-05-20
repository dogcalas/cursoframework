
<?php

class ZendExt_WF_WFObject_Entidades_VariableSalida extends ZendExt_WF_WFObject_Base_Complex {

    //put your code here
    //Oiner...
    private $Sistema;
    /*
     * Idem en VariableEntrada
     */
    private $Objeto;
    /*
     * Idem en VariableEntrada
     */
    private $Campo;
    private $Asociado;
    /*
     * Idem en VariableEntrada
     */
    private $Tipo;
    private $Direccion;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'VariableSalida';
        $this->Tipo = 'Salida';
    }

    //Oiner...
    public function getSistema() {
        return $this->Sistema;
    }

    public function getObjeto() {
        return $this->Objeto;
    }

    public function getCampo() {
        return $this->Campo;
    }

    public function getAsociado() {
        return $this->Asociado;
    }

    public function getTipo() {
        return $this->Tipo;
    }

    public function getDireccion() {
        return $this->Direccion;
    }

    //Oiner...
    public function setSistema($system) {
        $this->Sistema = $system;
    }

    public function setActividad($actividad) {
        $this->Actividad = $actividad;
    }

    public function setObjeto($objeto) {
        $this->Objeto = $objeto;
    }

    public function setCampo($campo) {
        $this->Campo = $campo;
    }

    public function setAsociado($pAsociado) {
        $this->Asociado = $pAsociado;
    }

    public function setTipo($tipo) {
        $this->Tipo = $tipo;
    }

    public function setDireccion($direccion) {
        $this->Direccion = $direccion;
    }

    public function toArray() {
        $array = array(
            'Sistema' => $this->getSistema(),
            'Objeto' => $this->getObjeto(),
            'Campo' => $this->getCampo(),
            'Asociado' => $this->getAsociado(),
            'Tipo' => $this->getTipo(),
            'Direccion' => $this->getDireccion()
        );
        return $array;
    }

    public function clonar() {
        return;
    }

    public function fillStructure() {
        return;
    }

}
?>

