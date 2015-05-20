<?php

class SemiautomaticTask extends BaseSemiautomaticTask {

    public function setUp() {
        parent::setUp();
        $this->hasOne('Node', array('local' => 'node_id', 'foreign' => 'node_id'));
    }

    public function GetTodos() {
        $query = new Doctrine_Query;
        $result = $query->from('SemiautomaticTask')->execute();
        return $result->toArray();
    }

    static public function BuscarPorId($idnode) {

        $query = new Doctrine_Query;
        $datos = $query->from('SemiautomaticTask s')
                        ->where('s.node_id=?', $idnode)
                        ->execute()->toArray();

        return $datos;
    }

    static public function Existencia($idaction) {
        //print_r($idaction);die;
        $query = new Doctrine_Query;
        $datos = $query->from('SemiautomaticTask s')
                        ->where('s.action_id=?', $idaction)
                        ->execute()->toArray();
        //print_r($datos->toArray());die;//->toArray());die;
        return $datos;
    }

    public static function buscarId($id) {
        $datos = Doctrine::getTable('SemiautomaticTask')->find($id);
        //print_r($datos);die;
        return $datos;
    }

    public static function get_Semiautomatic_Task($id) {
        $query = Doctrine_Query::create();
        $datos = $query->from('SemiautomaticTask s')
                ->where('s.node_id=?', $id)
                ->execute()
                ->toArray();
        return $datos;
    }

}

//fin clase


