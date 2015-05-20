
<?php

class ZendExt_WF_WFObject_Entidades_Transaction extends ZendExt_WF_WFObject_Base_Complex {

    private $TransactionId;
    private $TransactionProtocol;
    private $TransactionMethod;

    public function __construct($parent) {
        parent::__construct($parent);
        $this->tagName = 'Transaction';
    }

    /*
     * Setters
     */

    public function setTransactionMethod($_transactionMethod) {
        $this->TransactionMethod->selectItem($_transactionMethod);
    }

    public function setTransactionProtocol($_transactionProtocol) {
        $this->TransactionProtocol = $_transactionProtocol;
    }

    public function setTransactionId($_transactionId) {
        $this->TransactionId = $_transactionId;
    }

    /*
     * Getters
     */

    public function getTransactionMethod() {
        return $this->TransactionMethod->getSelectedItem();
    }

    public function getTransactionProtocol() {
        return $this->TransactionProtocol;
    }

    public function getTransactionId() {
        return $this->TransactionId;
    }

    /*
     * Abstractions
     */

    public function clonar() {
        return;
    }

    public function toArray() {
        $array = array(
            'TransactionId' => $this->getTransactionId(),
            'TransactionProtocol' => $this->getTransactionProtocol(),
            'TransactionMethod' => $this->getTransactionMethod(),
        );
        return $array;
    }

    public function fillStructure() {
        /*
         *  <xsd:sequence>
         *      <xsd:any namespace="##other" processContents="lax" minOccurs="0" maxOccurs="unbounded"/>
         *  </xsd:sequence>
         */

        $transactionMethodChoices = array('Compensate', 'Store', 'Image');
        $this->TransactionMethod = new ZendExt_WF_WFObject_Base_SimpleChoice('TransactionMethod', $transactionMethodChoices, NULL);
        return;
    }

}
?>

