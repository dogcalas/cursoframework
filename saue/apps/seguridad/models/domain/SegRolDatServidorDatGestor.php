<?php

class SegRolDatServidorDatGestor extends BaseSegRolDatServidorDatGestor
{

    public function setUp()
    {
        parent :: setUp ();
        $this->hasOne ('DatServidor', array ('local' => 'idservidor', 'foreign' => 'idservidor'));
        $this->hasOne ('SegRol', array ('local' => 'idrol', 'foreign' => 'idrol'));
        $this->hasOne ('DatGestor', array ('local' => 'idgestor', 'foreign' => 'idgestor'));
        $this->hasOne ('DatBd', array ('local' => 'idbd', 'foreign' => 'idbd'));
        $this->hasOne ('SegRolesbd', array ('local' => 'idrolbd', 'foreign' => 'idrolesbd'));
    }
    
    static public function Existe_rol_servidor_gestor($denominacion, $idServidor, $idGestor){
        $query = Doctrine_Query::create();
        $cant=$query->select('count(denominacion,idservidor,idgestor)')
                  ->from('SegRolDatServidorDatGestor')
                  ->where("denominacion=? and idservidor=? and idgestor =? ",
                          array($denominacion, $idServidor, $idGestor))
                  ->execute()->toArray();
  
            return $cant[0]['count'];
    }
    static public function CantidadRolServidorGestor($idrol,$ip,$gestor,$puerto){
        $query = Doctrine_Query::create();
        $cantidad=$query->select('count(rsg.idrol)')
                        ->from('SegRolDatServidorDatGestor rsg')
                        ->innerJoin('rsg.DatServidor s')
                        ->innerJoin('s.DatGestorDatServidorbd gs')
                        ->innerJoin('gs.DatGestor g')
                        ->where('g.gestor=? and g.puerto=? and s.ip=? and rsg.idrol=?',
                                array($gestor,$puerto,$ip,$idrol))->execute();
        return $cantidad;
    }
    static public function rol_servidor_gestorXrolSistema($rol){
        $query = Doctrine_Query::create();
        $roles=$query->select('idrol,idservidor,idgestor,denominacion,idbd,idrolbd')
                  ->from('SegRolDatServidorDatGestor')
                  ->where("idrol=? ",array($rol))
                  ->execute()->toArray();
  
            return $roles;
    }
    
    static public function getRoles_Servidores_Gestores_BD(){
        $query = Doctrine_Query::create();
        $roles=$query->select('rsgb.idrol, rsgb.idservidor, rsgb.idgestor, rsgb.denominacion, rsgb.idbd, rsgb.idrolbd, '.
                              's.ip, g.gestor, g.puerto, b.denominacion, r.nombrerol, r.passw')
                  ->from('SegRolDatServidorDatGestor rsgb, rsgb.DatServidor s, rsgb.DatGestor g, rsgb.DatBd b, rsgb.SegRolesbd r')
                  ->where("rsgb.idservidor=s.idservidor and rsgb.idgestor=g.idgestor and rsgb.idbd=b.idbd and rsgb.idrolbd=r.idrolesbd")
                  ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                  ->execute();  
            return $roles;
    }
    
    static public function getRoles_Servidores_Gestores_BDByIdsRoles($idsRoles){
        $query = Doctrine_Query::create();
        $roles=$query->select('rsgb.idrol, rsgb.idservidor, rsgb.idgestor, rsgb.denominacion, rsgb.idbd, rsgb.idrolbd, '.
                              's.ip, g.gestor, g.puerto, b.denominacion, r.nombrerol, r.passw')
                  ->from('SegRolDatServidorDatGestor rsgb, rsgb.DatServidor s, rsgb.DatGestor g, rsgb.DatBd b, rsgb.SegRolesbd r')
                  ->where("rsgb.idservidor=s.idservidor and rsgb.idgestor=g.idgestor and rsgb.idbd=b.idbd and rsgb.idrolbd=r.idrolesbd")
                  ->whereIn('rsgb.idrol',$idsRoles)
                  ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                  ->execute();  
            return $roles;
    }
    
    static public function CleanTable(){
       $query = Doctrine_Query::create();
       $query->delete()->from("SegRolDatServidorDatGestor")->execute();
    }
    static public function CleanTableByIDRoles($idsRoles){
       $query = Doctrine_Query::create();
        $roles=$query->delete()->from("SegRolDatServidorDatGestor")->whereIn('idrol',$idsRoles)->execute();
    }
    
    static public function findBy($idrol){
        $query = Doctrine_Query::create();
        $roles=$query->select('idrol,idservidor,idgestor,denominacion, idbd,idrolbd')
                  ->from('SegRolDatServidorDatGestor')
                  ->where("idrol=?",$idrol)
                  ->execute();
  
            return $roles;
    }
    


}

