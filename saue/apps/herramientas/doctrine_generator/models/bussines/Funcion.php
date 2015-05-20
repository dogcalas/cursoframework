<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of function
 *
 * @author ino
 */
class Funcion {

    /**
     * @AttributeType string
     */
    private $_id;

    /**
     * @AttributeType string
     */
    private $_name;

    /**
     * @AttributeType string
     */
    private $_params;
    /**
     * @AttributeType string
     */
    private $_visibility;

    /**
     * @AttributeType bool
     * 
     */
    private $_query;

    function __construct() {

        $this->_params = array();
    }

    public function get_id() {
        return $this->_id;
    }

    public function set_id($_id) {
        $this->_id = $_id;
    }

    public function get_name() {
        return $this->_name;
    }

    public function get_params() {
        return $this->_params;
    }

    public function get_query() {
        return $this->_query;
    }

    public function set_name($_name) {
        $this->_name = $_name;
    }

    public function set_params($_params) {
        $this->_params = $_params;
    }

    public function set_query($_query) {
        $this->_query = $_query;
    }
    public function get_visibility() {
        return $this->_visibility;
    }

    public function set_visibility($_visibility) {
        $this->_visibility = $_visibility;
    }



}
