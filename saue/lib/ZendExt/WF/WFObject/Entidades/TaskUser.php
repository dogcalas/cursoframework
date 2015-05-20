
<?php

class ZendExt_WF_WFObject_Entidades_TaskUser extends ZendExt_WF_WFObject_Base_Complex {

    private $implementacion;

    /*
     *  aproximadamente 9.5 hecto pascales
     */
    /* private $idRol;
      private $actionController;
      private $Servicio_dir; */

    /* private $nombre;
      private $identificador;
      private $varIn;
      private $varOut; */

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'TaskUser';
    }

    /*
     * Setters
     */
    /* public function setIdRol($_idrol) {
      $this->idRol = $_idrol;
      }

      public function setImplementation($_implementation) {
      $this->implementacion->selectItem($_implementation);
      }

      public function setActionController($param) {
      $this->actionController = $param;
      } */

    /*
     * Getters
     */

    public function getPerformers() {
        return $this->get('Performers');
    }

    public function getImplementation() {
        return $this->implementacion->getSelectedItem();
    }

    /* public function getIdRol() {
      return $this->idRol;
      }

      public function getActionController() {
      return $this->actionController;
      } */

    public function getMessageIn() {
        return $this->get('MessageIn');
    }

    public function getMessageOut() {
        return $this->get('MessageOut');
    }

    public function getWebServiceOperation() {
        return $this->get('WebServiceOperation');
    }

    public function getWebServiceFaultCatch() {
        return $this->get('WebServiceFaultCatch');
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function fillStructure() {
        $this->add(new ZendExt_WF_WFObject_Entidades_Performers($this));
        $this->add(new ZendExt_WF_WFObject_Entidades_MessageType($this, 'MessageIn'));
        $this->add(new ZendExt_WF_WFObject_Entidades_MessageType($this, 'MessageOut'));
        $this->add(new ZendExt_WF_WFObject_Entidades_WebServiceOperation($this));
        //$this->add(new ZendExt_WF_WFObject_Entidades_Variables());

        $implementationChoices = array('IOC_Service', 'WebService', 'Other', 'Unspecified');
        $this->Implementation = new ZendExt_WF_WFObject_Base_SimpleChoice('Implementation', $implementationChoices, NULL);
    }

    public function toArray() {
        $array = array(
            'Implementation' => $this->getImplementation(),
            'Performers' => $this->getPerformers()->toArray(),
            'MessageIn' => $this->getMessageIn()->toArray(),
            'MessageOut' => $this->getMessageOut()->toArray(),
            'WebServiceOperation' => $this->getWebServiceOperation()->toArray(),
        );
        return $array;
    }

}
?>

