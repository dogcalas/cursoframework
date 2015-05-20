<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Token
 *
 * 
 */
class ZendExt_WF_BPEL_Token {
    //put your code here
    private $path;

    public function __construct() {
        $this->path = array();
    }
    
    private function combine($sourceToke, $targetToken) {
        foreach ($targetToken as $subPath) {
            $sourceToke[] = $subPath;
        }
    }
    
    private function toPath($pathAsArray) {
        $return = '';
        foreach ($pathAsArray as $value) {
            $return = $return . '/' . $value;
        }
        return $return;
    }
    
    private function fromPath($path) {
        $return = array();
        $parts = explode('/', $path);
        foreach ($parts as $part) {
            $return[] = $part;
        }
        return $return;
    }
}

?>
