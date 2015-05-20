<?php

//include 'Base/ZendExt_WF_WFObject_Base_Collections.php';

class ZendExt_WF_WFObject_Parser {

//-----------------// PRIVATE FUNCTIONS //--------------------------------        
    private function myEncode($json) {
        $decodedJsonAsArray = json_decode($json, true); //As an indexed array
        $decodedJsonAsObject = json_decode($json); //As an object
        $result = array();
        $result['asObject'] = $decodedJsonAsObject;
        $result['asArray'] = $decodedJsonAsArray;
        return $result; 
    }

    private function assembleObject($asArray, $asObject, &$objectToAssemble) {
        if($objectToAssemble instanceof ZendExt_WF_WFObject_Base_ComplexChoice){
            self::fillChoiceElements($asArray, $asObject, $objectToAssemble); 
        }else
        if(array_key_exists('Type', $asArray) && array_key_exists($asArray['Type'], $asArray)){
            $type = $asArray['Type'];
            $asArray = $asArray[$type];
            $asObject = $asObject->$type;
            self::assembleObject($asArray, $asObject, $objectToAssemble);
        }
        else{        
            foreach ($asArray as $key => $value) {
                if (is_array($value)) {
                    $funcPrefix = 'get';
                    $funcSuffix = $key;
                    $fullFuncName = $funcPrefix.$funcSuffix;
                    if(method_exists($objectToAssemble, $fullFuncName)){
                        $newObject = $objectToAssemble->$fullFuncName();
                        if($newObject === NULL)continue;
                        if($newObject instanceof ZendExt_WF_WFObject_Base_Collections){
                            self::fillCollectionElement($value, $asObject->$key, $newObject); 
                        }
                        else{
                            if($newObject instanceof ZendExt_WF_WFObject_Base_Choice){                                
                                self::fillChoiceElements($value, $asObject->$key, $newObject);
                            }
                            else{
                                self::assembleObject($value, $asObject->$key, $newObject);
                            }
                        }
                    }  else {
                        throw new Exception('Not such function '.$fullFuncName.' in '.$objectToAssemble->getTagName());
                    }
                }
                else{
                    $funcPrefix = 'set';
                    $funcSuffix = $key;
                    $fullFuncName = $funcPrefix.$funcSuffix;
                    if(method_exists($objectToAssemble, $fullFuncName)){
                        $newObject = $objectToAssemble->$fullFuncName($value);
                    }else {
                        throw new Exception('Not such function '.$fullFuncName.' in '.$objectToAssemble->getTagName());
                    }
                }
            }
        }
    }

    private function fillCollectionElement($asArray, $asObject, $objectToAssemble) {
        $type = $asObject->Type;
        $auxArray = $asObject->$type;
        $asArray = $asArray[$type];
        $counter = 0;
        foreach ($auxArray as $value) {
            $configArgAsObject = $value;
            $configArgAsArray = $asArray[$counter++];
            
            if($objectToAssemble->hasDecision()){
		$variableType = $value->Type;
		$variableObject = $value->$variableType;
		$variableTypeCreateDecision = $variableObject->Direccion;
		$objectToAssemble->decide($variableTypeCreateDecision);
            }            
            $object = $objectToAssemble->createObject();
            self::assembleObject($configArgAsArray, $configArgAsObject, $object);            
            $objectToAssemble->add($object);
        }
    }

    private function fillChoiceElements($asArray, $asObject, $objectToAssemble) {         
        if(array_key_exists('sItemIdProperty', $asArray)){            
            $asArray = $asArray['sItemIdProperty'];
            $asObject = $asObject->sItemIdProperty;
        }
        else{
            if(array_key_exists('Type', $asArray) && array_key_exists($asArray['Type'], $asArray)){
                $typeSelect = $asArray['Type'];                
            }
        }
        $typeSelect = $asArray['Type'];        
        $objectToAssemble->selectItem($typeSelect);
        $newObject = $objectToAssemble->getSelectedItem();
        self::assembleObject($asArray, $asObject, $newObject);        
    }

    private function assembleFromConfig($asArray, $asObject, $objectToAssemble) {
        $type = $asObject->Type;
        $configArgAsObject = $asObject->$type;
        $configArgAsArray = $asArray[$type];
        self::assembleObject($configArgAsArray, $configArgAsObject, $objectToAssemble);
    }

//-----------------// PUBLIC FUNCTIONS //-------------------------------        
    public function __construct() {
        
    }

    public function createObject($json) {
        $aux = self::myEncode($json);
        $asObject = $aux['asObject'];
        $asArray = $aux['asArray'];
        //print_r($asObject);die;
        $result = NULL;
        self::buildObject($asArray, $asObject, $result); 
        //print_r($result);die;
        return $result;
    }

    public function buildObject($asArray, $asObject, &$objectToAssemble) {
        $type = $asObject->Type;
        $configArgAsObject = $asObject->$type;
        $configArgAsArray = $asArray[$type];
        $method = 'build' . $type;
        self::$method($configArgAsArray, $configArgAsObject, $objectToAssemble);
    }

    public function buildPackage($asArray, $asObject, &$package) {
        $package = new ZendExt_WF_WFObject_Entidades_Package(null);//ZendExt_WF_WFObject_Entidades_Package(NULL);
        self::assembleObject($asArray, $asObject, $package);
    }

    public function buildPackageHeader($asArray, $asObject) {
        $packageHeader = new ZendExt_WF_WFObject_Entidades_PackageHeader();
        self::assembleObject($asArray, $asObject, $packageHeader);
    }

    public function buildXPDLVersion($asArray, $asObject) {
        $xpdlVersion = new ZendExt_WF_WFObject_Entidades_XPDLVersion();
        self::assembleObject($asArray, $asObject, $xpdlVersion);
    }

    public function buildWorkflowProcesses($asArray, $asObject) {
        $workflowProcesses = new ZendExt_WF_WFObject_Entidades_WorkflowProcesses();
        self::assembleObject($asArray, $asObject, $workflowProcesses);
    }

    public function buildWorkflowProcess($asArray, $asObject) {
        $workflowProcess = new ZendExt_WF_WFObject_Entidades_WorkflowProcess();
        self::assembleObject($asArray, $asObject, $workflowProcess);
    }

    public function buildProcessHeader($asArray, $asObject) {
        echo 'ProcessHeader';
    }

}

;
?>
