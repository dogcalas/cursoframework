<?php

class ZendExt_WF_WFObject_BuildJson {
    //put your code here
    private function myJsonEncode($package){
        $myPackage = new ZendExt_WF_WFObject_Entidades_Package();
        $myPackage = $package;
        $packageArray = $myPackage->toArray();
        $packageJson = json_encode($packageArray);
    }
    
}

?>
