<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of zendaction
 *
 * @author yriverog
 */
class ezcWorkflowZendActionTask extends ezcWorkflowNodeInput {

    //put your code here
    private $zendaction;
    private $idrol;
    private $SystemId; //el nombre del sistema
    private $Controller;
    private $Action;
    
    private $idsistema;
    private $idfuncionalidad;
    private $denominacion;

    public function __construct($configuration) {
        parent::__construct($configuration);

        $config = $this->getConfiguration();

        $check = $this->check($config);
        if (!$check) {
            throw new ezcBaseValueException(
                    'workflow task config exception', $config, 'exception'
            );
        }  else {
            $this->zendaction = $config['zendaction'];
            $this->idrol = $config['idrol'];
            $this->SystemId = $config['SystemId'];
            $this->Controller = $config['Controller'];
            $this->Action = $config['Action'];
            
            $this->idsistema = $config['IdSistema'];
            $this->idfuncionalidad = $config['IdFuncionalidad'];
            $this->denominacion = $config['Denominacion'];
        }
    }

    //Verificar si los atributos en la configuracion son validos de esta clase
    private function check($config) {
        if (!is_array($config)) {
            return false;
        }  else {
            return  array_key_exists('zendaction', $config) &&
                    array_key_exists('idrol', $config)&&
                    array_key_exists('SystemId', $config)&&
                    array_key_exists('Controller', $config)&&
                    array_key_exists('Action', $config)&&
                    array_key_exists('IdSistema', $config)&&
                    array_key_exists('IdFuncionalidad', $config)&&
                    array_key_exists('Denominacion', $config);
        }        
    }

    public function setZendAction($direccion) {
        $config = $this->getConfiguration();
        if (array_key_exists('zendaction', $config)) {
            $config['zendaction'] = $direccion;
            $this->zendaction = $direccion;
        } else {
            throw new Exception('zendaction no es un atributo valido de ' . get_class($this));
        }
    }

    public function setIdRol($idrol) {
        $config = $this->getConfiguration();
        if (array_key_exists('idrol', $config)) {
            $config['idrol'] = $idrol;
            $this->idrol = $idrol;
        } else {
            throw new Exception('zendaction no es un atributo valido de ' . get_class($this));
        }
    }
    
    public function setIdSistema($idsistema) {
        $config = $this->getConfiguration();
        if (array_key_exists('idsistema', $config)) {
            $config['idsistema'] = $idsistema;
            $this->idsistema = $idsistema;
        } else {
            throw new Exception('idsistema no es un atributo valido de ' . get_class($this));
        }
    }

    public function setIdFuncionalidad($idfuncionalidad) {
        $config = $this->getConfiguration();
        if (array_key_exists('idfuncionalidad', $config)) {
            $config['idfuncionalidad'] = $idfuncionalidad;
            $this->idfuncionalidad = $idfuncionalidad;
        } else {
            throw new Exception('idfuncionalidad no es un atributo valido de ' . get_class($this));
        }
    }   
    
    public function setDenominacion($denominacion) {
        $config = $this->getConfiguration();
        if (array_key_exists('denominacion', $config)) {
            $config['denominacion'] = $denominacion;
            $this->denominacion = $denominacion;
        } else {
            throw new Exception('denominacion no es un atributo valido de ' . get_class($this));
        }
    }     

    public function getIdRol() {
        return $this->idrol;
    }
    
    public function getSystemId() {
        return $this->SystemId;
    }
    
    public function getController() {
        return $this->Controller;
    }
    
    public function getAction() {
        return $this->Action;
    }    
    
    public function getZendAction() {
        return $this->zendaction;
    }
    
    public function getIdSistema(){
        return $this->idsistema;
    }
    
    public function getIdFuncionalidad(){
        return $this->idfuncionalidad;
    }  
    
    public function getDenominacion(){
        return $this->denominacion;
    }    

}

?>
