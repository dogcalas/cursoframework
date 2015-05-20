
<?php

class ZendExt_WF_WFObject_Entidades_VariableEntrada extends ZendExt_WF_WFObject_Base_Complex {

    //put your code here
    /*
     * La actividad que generó esta variable...
     */
    private $Actividad;
    
    /*
     * La entidad a la que pertenece esta variable...
     */
    private $Objeto;
    
    /*
     * el nombre de esta variable
     * ej:
     * Entidad : Persona
     * Campo: edad
     */
    private $Campo;
    
    /*
     * Dado que no hay formularios dinámicos, postvariable
     * se refiere al nombre de esta variable como baja en 
     * la peticion pro el metodo POST. ej:
     * Entidad: Persona
     * Campo: edad
     * 
     * Request:{
     *  POST:{
     *      params: n_edad
     *  }
     * }
     * que en una action determinada se trataria como:
     * $this->_request->getPost('n_edad');
     */
    private $PostVariable;
    
    /*
     * seudotipo: int, boolean, string... ?
     */
    private $Tipo;
    
    /*
     * Unaduda razonable
     */
    private $Direccion;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'VariableEntrada';
        $this->Tipo = 'Entrada';
    }

    public function clonar() {
        return;
    }

    public function getActividad() {
        return $this->Actividad;
    }

    public function getObjeto() {
        return $this->Objeto;
    }

    public function getCampo() {
        return $this->Campo;
    }

    public function getPostVariable() {
        return $this->PostVariable;
    }

    public function getTipo() {
        return $this->Tipo->getSelectedItem();
    }

    public function getDireccion() {
        return $this->Direccion;
    }

    public function setActividad($_actividad) {
        $this->Actividad = $_actividad;
    }

    public function setObjeto($_objeto) {
        $this->Objeto = $_objeto;
    }

    public function setCampo($_campo) {
        $this->Campo = $_campo;
    }

    public function setPostVariable($_postVariable) {
        $this->PostVariable = $_postVariable;
    }

    public function setTipo($_tipo) {
        $this->Tipo->selectItem($_tipo);
    }

    public function setDireccion($_direccion) {
        $this->Direccion = $_direccion;
    }

    public function toArray() {
        $array = array(
            'Actividad' => $this->getActividad(),
            'Objeto' => $this->getObjeto(),
            'Campo' => $this->getCampo(),
            'PostVariable' => $this->getPostVariable(),
            'Tipo' => $this->getTipo(),
            'Direccion' => $this->getDireccion()
        );
        return $array;
    }

    public function fillStructure() {
        $tipoChoices = array('Entrada','Salida');
        $this->Tipo = new ZendExt_WF_WFObject_Base_SimpleChoice('Tipo', $tipoChoices, NULL);
    }

}
?>

