<?php

class SegUsuarioDatServidorDatGestor extends BaseSegUsuarioDatServidorDatGestor
{

    public function setUp()
    {
        parent :: setUp ();
        $this->hasOne ('DatServidor', array ('local' => 'idservidor', 'foreign' => 'idservidor'));
        $this->hasOne ('DatGestor', array ('local' => 'idgestor', 'foreign' => 'idgestor'));
        $this->hasOne ('SegUsuario', array ('local' => 'idusuario', 'foreign' => 'idusuario'));
    }
    
    static public function CantidadUsuarioServidorGestor($idusuario,$ip,$gestor,$puerto){
        $query = Doctrine_Query::create();
        $cantidad=$query->select('count(usg.idusuario)')
                        ->from('SegUsuarioDatServidorDatGestor usg')
                        ->innerJoin('usg.DatServidor s')
                        ->innerJoin('s.DatGestorDatServidorbd gs')
                        ->innerJoin('gs.DatGestor g')
                        ->where('g.gestor=? and g.puerto=? and s.ip=? and usg.idusuario=?',
                                array($gestor,$puerto,$ip,$idusuario))->execute();
        return $cantidad;
    }
    
    static public function getUsuario_Servidores_Gestores_BD(){
        $query = Doctrine_Query::create();
        $roles=$query->select('usg.idusuario, usg.idservidor, usg.idgestor,s.ip, g.gestor, g.puerto, u.nombreusuario')
                  ->from('SegUsuarioDatServidorDatGestor usg, usg.DatServidor s, usg.DatGestor g,usg.SegUsuario u')
                  ->where("usg.idservidor=s.idservidor and usg.idgestor=g.idgestor and usg.idusuario=u.idusuario")
                  ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                  ->execute();
  
            return $roles;
    }
    static public function getUsuario_Servidores_Gestores_BDByIDUser($id){
        $query = Doctrine_Query::create();
        $roles=$query->select('usg.idusuario, usg.idservidor, usg.idgestor,s.ip, g.gestor, g.puerto, u.nombreusuario')
                  ->from('SegUsuarioDatServidorDatGestor usg, usg.DatServidor s, usg.DatGestor g,usg.SegUsuario u')
                  ->where("usg.idservidor=s.idservidor and usg.idgestor=g.idgestor and usg.idusuario=?",$id)
                  ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                  ->execute();
  
            return $roles;
    }

    static public function CleanTable(){
       $query = Doctrine_Query::create();
       $query->delete()->from("SegUsuarioDatServidorDatGestor")->execute();
    }
    
    static public function Find($idusuario,$idservidor,$idgestor){
       $encontrado=Doctrine::getTable("SegUsuarioDatServidorDatGestor")->find(array($idusuario,$idservidor,$idgestor));
       return $encontrado;
    }
}

