<?php

class ZendExt_WF_WFTranslatorXPDL_Base_Controlador {

    private $package;
    private $doc;

    function __construct($package) {
        $this->package = $package;
    }

    function get_package() {
        return $this->package;
    }

    function set_package($nnombre) {
        $this->package = $nnombre;
    }

    function Generar_Documento_XML() {
        
    }

    public function myGenerarDocumento() {
        $myPackage = new ZendExt_WF_WFTranslatorXPDL_Clases_Package($this->package, TRUE);
        $this->doc = new DOMDocument('1.0', 'UTF-8');
        $this->doc->formatOutput = true;
        $packageResult = null;
        $myPackage->toXPDL($this->doc, $packageResult);
        $this->doc->save('/home/yriverog/Escritorio/XPDL.xml');
    }

    public function myParsearXPDL() {
        try {
            if (file_exists('/home/yriverog/Escritorio/XPDL.xml')) {
                $xmlDoc = new DOMDocument();
                $xmlDoc->preserveWhiteSpace = false;
                $xmlDoc->load('/home/yriverog/Escritorio/XPDL.xml');
                $_package = $xmlDoc->documentElement;
                $_sourcePackageObject = new ZendExt_WF_WFObject_Entidades_Package(NULL);
                $_targetPackageObject = new ZendExt_WF_WFTranslatorXPDL_Clases_Package(NULL);
                $_targetPackageObject->setObject($_sourcePackageObject);
                $_retValue = $_targetPackageObject->fromXPDL($_package);
                return $_retValue;
                
            } else {
                throw new Exception('El archivo XPDL.xml no existe.');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
?>


