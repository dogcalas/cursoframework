<?php

class DatObjetobd extends BaseDatObjetobd
{

    public function setUp()
    {
        parent :: setUp ();
        $this->hasMany ('DatAccionDatObjetobd', array ('local' => 'idobjetobd', 'foreign' => 'idobjetobd'));
        $this->hasMany ('DatServicioObjetobd', array ('local' => 'idobjetobd', 'foreign' => 'idobjetobd'));
        $this->hasMany ('DatServidor', array ('local' => 'idservidor', 'foreign' => 'idservidor'));
        $this->hasMany ('DatGestor', array ('local' => 'idgestor', 'foreign' => 'idgestor'));
        $this->hasMany ('DatBd', array ('local' => 'idbd', 'foreign' => 'idbd'));
        $this->hasMany ('DatEsquema', array ('local' => 'idesquema', 'foreign' => 'idesquema'));
        $this->hasMany ('SegRolesbd', array ('local' => 'idrolesbd', 'foreign' => 'idrolesbd'));
    }
    
    static public function obtenerPermisos($objeto,$idesquema,$idservidor,$idgestor,$idbd,$idaccion,$idrol) {
        $query = Doctrine_Query::create();
        $objetos = $query->select('o.idservidor,o.idgestor,o.idbd,o.idesquema,ao.idaccion, o.objeto, o.idobjeto,ao.privilegios,o.idrolesbd')
                        ->from('DatObjetobd o, o.DatAccionDatObjetobd ao')
                        ->where
                ("o.idobjetobd = ao.idobjetobd and o.objeto = ? and o.idesquema = ? and o.idservidor = ? and o.idgestor = ? and o.idbd = ? and ao.idaccion = ? and o.idrolesbd= ?",
                                  array($objeto,$idesquema,$idservidor,$idgestor,$idbd,$idaccion,$idrol))
                ->setHydrationMode( Doctrine :: HYDRATE_ARRAY )       
                ->execute();
        return $objetos;
    }
    static public function obtenerPermisosServicios($objeto,$idesquema,$idservidor,$idgestor,$idbd,$idservicio,$idrol) {
        $query = Doctrine_Query::create();
        $objetos = $query->select('o.idservidor,o.idgestor,o.idbd,o.idesquema,ao.idservicio, o.objeto, o.idobjeto,ao.privilegios,o.idrolesbd')
                        ->from('DatObjetobd o, o.DatServicioObjetobd ao')
                        ->where
                ("o.idobjetobd = ao.idobjetobd and o.objeto = ? and o.idesquema = ? and o.idservidor = ? and o.idgestor = ? and o.idbd = ? and ao.idservicio = ? and o.idrolesbd= ?",
                                  array($objeto,$idesquema,$idservidor,$idgestor,$idbd,$idservicio,$idrol))
                ->setHydrationMode( Doctrine :: HYDRATE_ARRAY )       
                ->execute();
        return $objetos;
    }
    
     //Modificar cuando cambie la tablaaaa
    static public function ObtenerPermisosXAcciones($IdsAcciones) {
        $query = Doctrine_Query::create();
        $permisos = $query->select('DISTINCT (a.objeto) a.objeto as objeto, a.idservidor as idservidor,'.
                                    ' a.idobjetobd,a.idobjeto,a.idbd,ao.idaccion, ao.privilegios,'.
                                    ' a.idesquema as esquema, a.idgestor as idgestor, a.idrolesbd as idrol,'.
                                    ' s.ip as ip, g.gestor as gestor, g.puerto as puerto, b.denominacion as bd,'.
                                    'e.denominacion as esquema, srbd.nombrerol as user, srbd.passw as pass')
                        ->from('DatObjetobd a')
                        ->innerJoin('a.DatAccionDatObjetobd ao')
                        ->innerJoin('a.DatServidor s')
                        ->innerJoin('a.DatGestor g')
                        ->innerJoin('a.DatBd b')
                        ->innerJoin('a.DatEsquema e')
                        ->innerJoin('a.SegRolesbd srbd')
                        ->whereIn("ao.idaccion",$IdsAcciones)
                        ->orderBy('a.idservidor, a.idgestor, a.idbd')
                        ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                        ->execute();
        
        return $permisos;
    }
    
    static public function ObjetosRelacionadosConServiciosFromAcciones($IdsAcciones){
             $query = Doctrine_Query::create();
             $objetos=$query->select('ao.idaccion,ao.idservicio,s.idservicio,so.idservicio,so.idobjetobd,'.
                                     'so.privilegios,o.objeto as objeto, o.idservidor as idservidor, o.idobjetobd,o.idbd,'.
                                     'o.idesquema as esquema, o.idgestor as idgestor, o.idrolesbd as idrol,o.idobjeto as idobjeto,'.
                                    ' serv.ip as ip, g.gestor as gestor, g.puerto as puerto, b.denominacion as bd,'.
                                    'e.denominacion as esquema, srbd.nombrerol as user, srbd.passw as pass ')
                            ->from('DatObjetobd o')
                            ->innerJoin('o.DatServicioObjetobd so')
                            ->innerJoin('o.DatServidor serv')
                            ->innerJoin('o.DatGestor g')
                            ->innerJoin('o.DatBd b')
                            ->innerJoin('o.DatEsquema e')
                            ->innerJoin('o.SegRolesbd srbd')
                            ->innerJoin('so.DatServicioioc s')
                            ->innerJoin('s.DatAccionDatServicioioc ao')
                            ->whereIn("ao.idaccion",$IdsAcciones)
                            ->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
                            ->execute();
             return $objetos;
            
         }
    static public function ObjetosRelacionadosConServiciosFromAccionesIdServicio($idaccion,$idservicio){
             $query = Doctrine_Query::create();
             $objetos=$query->select('ao.idaccion,ao.idservicio,s.idservicio,so.idservicio,so.idobjetobd,'.
                                     'so.privilegios,o.objeto as objeto, o.idservidor as idservidor, o.idobjetobd,o.idbd,'.
                                     'o.idesquema as esquema, o.idgestor as idgestor, o.idrolesbd as idrol,o.idobjeto as idobjeto,'.
                                    ' serv.ip as ip, g.gestor as gestor, g.puerto as puerto, b.denominacion as bd,'.
                                    'e.denominacion as esquema, srbd.nombrerol as user, srbd.passw as pass ')
                            ->from('DatObjetobd o')
                            ->innerJoin('o.DatServicioObjetobd so')
                            ->innerJoin('o.DatServidor serv')
                            ->innerJoin('o.DatGestor g')
                            ->innerJoin('o.DatBd b')
                            ->innerJoin('o.DatEsquema e')
                            ->innerJoin('o.SegRolesbd srbd')
                            ->innerJoin('so.DatServicioioc s')
                            ->innerJoin('s.DatAccionDatServicioioc ao')
                            ->where('ao.idaccion=?',$idaccion)
                            ->whereIn("s.idservicio",$arrayIdServicios)
                            ->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
                            ->execute();
             return $objetos;
            
         }
     static public function getById($idobjetobd){
         return Doctrine::getTable('DatObjetobd')->find(array($idobjetobd));
     }

}

