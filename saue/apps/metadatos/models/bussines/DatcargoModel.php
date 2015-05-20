<?php
class  DatcargoModel extends ZendExt_Model
{
    public function DatcargoModel()
    {
        parent::ZendExt_Model();
        $this->instance = new DatCargo();
    }


    /** -----------------------------------------------------------------------
     * Inserta un nuevo cargo
     *
     * @param int $pIdestructuraop
     * @param int $pIdespecialidad
     * @param int $pIdtipocifra
     * @param int $pIdprefijo
     * @param int $pCtp
     * @param int $pCtg
     * @param int $pOrden
     * @param int $pEstado
     * @param date $pFechaini
     * @param date $pFechafin
     * @return int
     */
    public function insertarCargo($idestructuraop, $idespecialidad, $idtipocifra, $idprefijo, $ctp, $ctg, $orden, $estado, $fechaini, $fechafin)
    {
        $this->Instancia();
        //$idcargo							= $this->buscaridproximo();
        //$this->instance->idcargo			= $idcargo;
        $this->instance->idestructuraop = $idestructuraop;
        $this->instance->idespecialidad = $idespecialidad;
        $this->instance->idtipocifra = $idtipocifra;
        $this->instance->idprefijo = $idprefijo;
        $this->instance->estado = $estado;
        $this->instance->ctp = $ctp;
        $this->instance->ctg = $ctg;
        $this->instance->orden = $orden;
        $this->instance->fechaini = $fechaini;
        $this->instance->fechafin = $fechafin;

        try {
            $this->instance->save();
            return $this->instance->idcargo;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**Pilo
     * /** ---------------------------------------------------------------------
     * Lista los cargos
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function  buscarCargo($limit = 10, $start = 0, $idop)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select("c.*, t.idtipocifra,t.dentipocifra,t.esdescargable,t.escifracargo,mt.idcargomilitar,mt.idgradomilit,mt.salario,mt.escadmando,mt.idcargo,mt.idnomcargomilitar, 'militar' as tipocargo
           						,CONCAT('c',c.idcargo)	as id, 'cargo' as tipo, 1 = 0 as leaf
            					,'cargo 1' as text, 'geticon?icon=2' as icon,ncc.dencargomilitar,ncc.abrevcargomilitar,ncc.idespecialidad,ncc.idprepmilitar,ncc.idgradomilit,ncc.idcargomilitar as eee
            					")
                ->from('DatCargo c')
                ->innerJoin('c.NomTipocifra t')
                ->innerJoin('c.DatCargomtar mt')
                ->innerJoin('mt.NomCargomilitar ncc')
                ->where("c.idestructuraop='$idop'")
                ->limit($limit)
                ->offset($start)
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result1 = $q->select("c.*, t.idtipocifra,t.dentipocifra,t.esdescargable,t.escifracargo,mt.idcargocivil,mt.idcargo,mt.idcargociv,mt.idcategcivil,mt.idgrupocomple,mt.idescalasalarial,mt.idsalario,mt.idclasificacion,ncc.idcargociv,ncc.dencargociv,ncc.abrevcargociv,ncc.idcategocup,ncc.idcalificador,ncc.idgrupocomplejidad,ncc.idnivelutilizacion,ncc.idespecialidad,s.idsalario,s.salario,g.idgrupocomplejidad,g.denominacion,g.abreviatura, 'civil' as tipocargo
            					,CONCAT('c',c.idcargo)	as id, 'cargo' as tipo, 1 = 0 as leaf
            					,'cargo 1' as text, 'geticon?icon=2' as icon,ncc.idcargociv,ncc.dencargociv,ncc.abrevcargociv,ncc.idcategocup,ncc.idcalificador,ncc.idgrupocomplejidad,ncc.idnivelutilizacion,ncc.idespecialidad,ncc.dencargociv as eee
            					")
                ->from('DatCargo c')
                //->innerJoin('c.NomEspecialidad e')
                ->innerJoin('c.NomTipocifra t')
                ->innerJoin('c.DatCargocivil mt')
                ->innerJoin('mt.NomSalario s')
                ->innerJoin('mt.NomCargocivil ncc')
                ->innerJoin('ncc.NomGrupocomple g')
                ->where("c.idestructuraop='$idop'")
                ->limit($limit)
                ->offset($start)
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();

            return array_merge_recursive($result, $result1);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }

    /** ---------------------------------------------------------------------
     * Lista los cargos
     *
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function buscarCargoLineal($limit = 10, $start = 0, $idop)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select("CONCAT('c',c.idcargo) as cargos, 'militar' as tipocargo
           						, 'cargo' as tipo, 1 = 0 as leaf
            					, 'geticon?icon=13' as icon,CONCAT(ncc.abrevcargomilitar,CONCAT(' ',CONCAT(c.ctp,CONCAT(' ',c.ctg))))  as denom
            					")
                ->from('DatCargo c')
                ->innerJoin('c.DatCargomtar mt')
                ->innerJoin('mt.NomCargomilitar ncc')
                ->where("c.idestructuraop='$idop'")
                ->limit($limit)
                ->offset($start)
                ->orderby('c.orden')
                ->setHydrationMode(Doctrine :: HYDRATE_NONE)
                ->execute();

            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q1 = Doctrine_Query::create($conn);

            $result1 = $q1->select("CONCAT('c',c.idcargo) as cargos, 'civil' as tipocargo
           						, 'cargo' as tipo, 1 = 0 as leaf
            					, 'geticon?icon=12' as icon,CONCAT(ncc.abrevcargociv,CONCAT(' ',CONCAT(c.ctp,CONCAT(' ',c.ctg))))  as denom
            					")
                ->from('DatCargo c')
                ->innerJoin('c.DatCargocivil mt')
                ->innerJoin('mt.NomSalario s')
                ->innerJoin('mt.NomGrupocomple p')
                ->innerJoin('mt.NomCargocivil ncc')
                ->innerJoin('ncc.NomGrupocomple g')
                ->where("c.idestructuraop='$idop'")
                ->limit($limit)
                ->offset($start)
                ->orderby('c.orden')
                ->setHydrationMode(Doctrine :: HYDRATE_NONE)
                ->execute();
            //return $result;
            $cargodatos = array_merge_recursive($result, $result1);
            $retorno = array();
            foreach ($cargodatos as $dat)
                $retorno[] = array_combine(array('id', 'tipocargo', 'tipo', 'leaf', 'icon', 'text'), $dat);
            return $retorno;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }


    /*

    /**-------------tipos de cargos---------*/

    public function buscarCargoConPuestos($idop)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select("p.idcargomilitar,mt.salario,c.ctp,c.ctg, 'militar' as tipo")
                ->from('NomCargomilitar p ')
                ->innerJoin('p.DatCargomtar mt')
                ->innerJoin('mt.DatCargo c')
                ->where("c.idestructuraop='$idop'")
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();


            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result1 = $q->select("ncc.idcargociv,mt.idcargociv,s.salario,c.ctp,c.ctg,'civil' as tipo")
                ->from('NomCargocivil ncc')
                ->innerJoin('ncc.DatCargocivil mt')
                ->innerJoin('mt.NomSalario s')
                ->innerJoin('mt.DatCargo c')
                ->where("c.idestructuraop='$idop'")
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();


            return array_merge_recursive($result, $result1);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }


    //--------------------------Metodo para integracion con CH----D,Indterminado--------
    public function buscarCargoPorTipoDet($idop, $isd)
    {
        try {
            if ($isd == 1) {

                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->select("ncc.idcargomilitar as idcargo,ncc.dencargomilitar as denominacion,ncc.abrevcargomilitar  ,mt.idcargo,c.ctp,c.ctg, 'militar' as tipo")
                    ->from('NomCargomilitar ncc ')
                    ->innerJoin('ncc.Asignacion mt')
                    ->innerJoin('mt.DatCargo c')
                    ->innerJoin('c.NomTipocifra t')
                    ->where("c.idestructuraop='$idop' and t.idtipocifra=5")
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();


                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result1 = $q->select("ncc.idcargociv as idcargo,ncc.dencargociv as denominacion ,ncc.abrevcargociv ,mt.idcargo,c.ctp,c.ctg, 'civil' as tipo")
                    ->from('NomCargocivil ncc')
                    ->innerJoin('ncc.Asignacion mt')
                    ->innerJoin('mt.DatCargo c')
                    ->innerJoin('c.NomTipocifra t')
                    ->where("c.idestructuraop='$idop' and t.dentipocifra ='Determinado' ")
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();

                return array_merge_recursive($result, $result1);

            } else {


                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->select("ncc.idcargomilitar as idcargo,ncc.dencargomilitar as denominacion,ncc.abrevcargomilitar  ,mt.idcargo,c.ctp,c.ctg, 'militar' as tipo")
                    ->from('NomCargomilitar ncc ')
                    ->innerJoin('ncc.Asignacion mt')
                    ->innerJoin('mt.DatCargo c')
                    ->innerJoin('c.NomTipocifra t')
                    ->where("c.idestructuraop='$idop' and t.dentipocifra='Indeterminado'")
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();


                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result1 = $q->select("ncc.idcargociv as idcargo,ncc.dencargociv as denominacion ,ncc.abrevcargociv ,mt.idcargo,c.ctp,c.ctg, 'civil' as tipo")
                    ->from('NomCargocivil ncc')
                    ->innerJoin('ncc.Asignacion mt')
                    ->innerJoin('mt.DatCargo c')
                    ->innerJoin('c.NomTipocifra t')
                    ->where("c.idestructuraop='$idop' and t.dentipocifra ='Indeterminado' ")
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();

                return array_merge_recursive($result, $result1);

            }


        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }

    /**-------------tipos de cargos---------*/

    public function buscarCargoPorTipo($idop)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select("mt.idcargo,mt.idcargomilitar,ncc.idcargomilitar,ncc.dencargomilitar as denominacion,ncc.abrevcargomilitar,c.ctp,c.ctg, 'militar' as tipo")
                ->from('DatCargomtar  mt ')
                ->innerJoin('mt.Asignacion ncc')
                ->innerJoin('mt.DatCargo c')
                ->where("c.idestructuraop ='$idop'")
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();


            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result1 = $q->select("mt.idcargocivil,mt.idcargo,ncc.idcargociv ,ncc.dencargociv as denominacion ,ncc.abrevcargociv ,c.ctp,c.ctg, 'civil' as tipo")
                ->from('DatCargocivil mt')
                ->innerJoin('mt.Asignacion ncc')
                //->innerJoin('mt.NomSalario s')
                ->innerJoin('mt.DatCargo c')
                ->where("c.idestructuraop='$idop'")
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();
            /*							;
    // --------Capital Humano
    foreach ($result as $k=>$v)
    {
    $result3[$k]['idcargo'] = $v['idcargomilitar'];
    $result3[$k]['denominacion'] = $v['dencargomilitar'];
    $result3[$k]['salario'] = $v['DatCargomtar'][0]['salario'];
    $result3[$k]['ctp'] = $v['DatCargomtar'][0]['DatCargo']['ctp'];
    $result3[$k]['ctg'] = $v['DatCargomtar'][0]['DatCargo']['ctg'];
                }

    foreach ($result1 as $k=>$v)
    {
    $result3[$k]['idcargo'] = $v['idcargociv'];
    $result3[$k]['denominacion'] = $v['dencargociv'];
    $result3[$k]['salario'] = $v['DatCargocivil'][0]['NomSalario']['salario'];
    $result3[$k]['ctp'] = $v['DatCargocivil'][0]['DatCargo']['ctp'];
    $result3[$k]['ctg'] = $v['DatCargocivil'][0]['DatCargo']['ctg'];
                }
    // --------Capital Humano */
            return array_merge_recursive($result, $result1);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }

    /**
     * Metodo para la integracion con capital humano
     *
     * @param unknown_type $idcargo
     * @param unknown_type $militar
     * @return unknown
     */
    /*
     public function buscarCargoPorTipo($idop)
    {
        try
         {
              $mg = ZendExt_Aspect_TransactionManager::getInstance();
             $conn = $mg->getConnection('metadatos');
             $q = Doctrine_Query::create($conn);

             $result 		= $q->select("ncc.idcargomilitar,ncc.dencargomilitar,mt.idcargomilitar,c.ctp,c.ctg, 'militar' as tipo")
                                 ->from('NomCargomilitar ncc ')
                                 ->innerJoin('ncc.DatCargomtar mt')
                                 ->innerJoin('mt.DatCargo c')
                                 ->where("c.idestructuraop='$idop'")
                                 ->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
                                 ->execute()
                                 ;


              $mg = ZendExt_Aspect_TransactionManager::getInstance();
             $conn = $mg->getConnection('metadatos');
             $q = Doctrine_Query::create($conn);

             $result1 		= $q->select("ncc.idcargociv,ncc.dencargociv,mt.idcargociv,c.ctp,c.ctg ,'civil' as tipo")
                                 ->from('NomCargocivil ncc')
                                 ->innerJoin('ncc.DatCargocivil mt')
                                 ->innerJoin('mt.DatCargo c')
                                 ->where("c.idestructuraop='$idop'")
                                 ->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
                                 ->execute()
                                 ;

            return array_merge_recursive($result,$result1);
         }
         catch(Doctrine_Exception $ee)
         {
             if(DEBUG_ERP)
                 echo (__FILE__.' '.__LINE__.' '.$ee->getMessage());

             return array();
         }
    }
 */
    /*public function BuscarCargosPorTiposSeguridad($idarea)
       {
       try
            {
                $q = Doctrine_Query::create();
                $result = $q->select("ncc.idcargomilitar as id, c.idcargo as pepe,ncc.dencargomilitar as text,ncc.abrevcargomilitar  ,mt.idcargo,c.ctp,c.ctg, 'militar' as tipo, true leaf")
                ->from('mt.NomCargomilitar ncc ')
                ->innerJoin('c.Asignacion mt')
                ->innerJoin('DatCargo c')
                ->where("c.idestructuraop='$idarea'")
                ->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
                ->execute();

                $q = Doctrine_Query::create();
                $result1 = $q->select("ncc.idcargociv as id,ncc.dencargociv as text ,c.idcargo as lolo,ncc.abrevcargociv ,mt.idcargo,c.ctp,c.ctg, 'civil' as tipo,true leaf")
                             ->from('NomCargocivil ncc')
                            ->innerJoin('ncc.Asignacion mt')
                            ->innerJoin('mt.DatCargo c')
                            ->where("c.idestructuraop='$idarea'")
                            ->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
                            ->execute();

               return  array_merge_recursive($result,$result1);
            }
            catch(Doctrine_Exception $ee)
            {
    if(DEBUG_ERP)
    echo (__FILE__.' '.__LINE__.' '.$ee->getMessage());

    return array();   
            }
       }
       */

    public function BuscarCargosPorTiposSeguridad($idop)
    {
        try {
            $q = Doctrine_Query::create();

            $result = $q->select("mt.idcargo as id,mt.idcargomilitar,ncc.idcargomilitar,ncc.dencargomilitar as text,ncc.abrevcargomilitar,c.ctp,c.ctg, 'militar' as tipo")
                ->from('DatCargomtar  mt ')
                ->innerJoin('mt.Asignacion ncc')
                ->innerJoin('mt.DatCargo c')
                ->where("c.idestructuraop ='$idop'")
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();

            $q = Doctrine_Query::create();

            $result1 = $q->select("mt.idcargocivil,mt.idcargo as id,ncc.idcargociv ,ncc.dencargociv as text ,ncc.abrevcargociv ,c.ctp,c.ctg, 'civil' as tipo")
                ->from('DatCargocivil mt')
                ->innerJoin('mt.Asignacion ncc')
                ->innerJoin('mt.DatCargo c')
                ->where("c.idestructuraop='$idop'")
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();
            return array_merge_recursive($result, $result1);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }


    public function datosCargo($idcargo, $militar = false)
    {
        try {
            if ($militar) {
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);
                /*
                                $result 		=  $q->select("c.idcargo,c.idestructuraop,c.idespecialidad,c.ctp,c.ctg,c.idtipocifra,c.idprefijo,c.orden,c.estado,c.fechaini,c.fechafin,c.salario,c.idtecnica,c.idgrupocomplejidad,c.idmodulo,
                                                                t.idtipocifra,t.dentipocifra,t.esdescargable,t.escifracargo,
                                                                mt.idcargomilitar,mt.idgradomilit,mt.salario,mt.escadmando,mt.idcargo,mt.idnomcargomilitar")*/

                $result = $q->from('DatCargo c')
                    //->innerJoin('c.NomEspecialidad e')
                    ->innerJoin('c.NomTipocifra t')
                    ->innerJoin('c.DatCargomtar mt')
                    ->innerJoin('mt.NomCargomilitar ncc')
                    ->where("c.idcargo='$idcargo'")
                    ->limit($limit)
                    ->offset($start)
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();
                //; echo '<pre>';print_r($result);die('as');
                return $result;
            } else {

                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                /*$result 		= $q->select("c.idcargo,c.idestructuraop,c.idespecialidad,c.ctp,c.ctg,c.idtipocifra,c.idprefijo,c.orden,c.estado,c.fechaini,c.fechafin,c.salario,c.idtecnica,c.idgrupocomplejidad,c.idmodulo,
                                            t.idtipocifra,t.dentipocifra,t.esdescargable,t.escifracargo,
                                            mt.idcargocivil,mt.idcargo,mt.idcargociv,mt.idcategcivil,mt.idgrupocomple,mt.idescalasalarial,mt.idsalario,mt.idclasificacion,
                                            s.idsalario,s.salario,
                                            esc.idescalasalarial,esc.denominacion,esc.abreviatura,
                                            n.idcargociv,n.dencargociv,n.abrevcargociv,n.idcategocup,n.idgrupocomplejidad,n.idcategcivil,
                                            g.idgrupocomplejidad,g.denominacion,g.abreviatura,
                                            cat.idcategcivil,cat.dencategcivil,cat.abrevcategcivil,
                                            p.idcategocup,p.dencategocup,p.abreviatura")*/
                $result = $q->from('DatCargo c')

                    //->innerJoin('c.NomEspecialidad e')
                    ->innerJoin('c.NomTipocifra t')
                    ->innerJoin('c.DatCargocivil mt')
                    ->innerJoin('mt.NomSalario s')
                    ->innerJoin('mt.NomEscalasalarial esc')
                    ->innerJoin('mt.NomCargocivil n')
                    ->innerJoin('mt.NomGrupocomple g')
                    ->leftJoin('n.NomCategcivil cat')
                    ->innerJoin('n.NomCategocup p')
                    ->where("c.idcargo='$idcargo'")
                    ->limit($limit)
                    ->offset($start)
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();

                return $result;

            }
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }


    /**-----------------------------------------------------------------
     *Funcion para la integracion
     *Datos de un cargo dado el id
     */
    public function getCargo($pid)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $result = $q->select("ncc.idcargomilitar as idcargomtar,ncc.dencargomilitar as denominacion,ncc.abrevcargomilitar  as abreviatura, 'militar' as tipo,c.ctp as cantPuestostp,c.ctg as cantPuestostg,ncc.*,mt.*,c.*")
                ->from('NomCargomilitar ncc ')
                ->innerJoin('ncc.DatCargomtar mt')
                ->innerJoin('mt.DatCargo c')
                ->where("c.idcargo='$pid'")
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();

            if (count($result) == 0) {
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);
// catego,riaocupacional,grupo escala, salario escala, 
                $result1 = $q->select("ncc.idcargociv as idcargocivil,ncc.dencargociv as denominacion,ncc.abrevcargociv as abreviatura, 'civil' as tipo,c.ctp as cantPuestostp,c.ctg as cantPuestostg,ncc.*,gc.*,co.*,es.*,mt.*
            								")
                    ->from('NomCargocivil ncc')
                    ->innerJoin('ncc.NomGrupocomple gc')
                    ->innerJoin('ncc.NomCategocup co')
                    ->innerJoin('gc.NomEscalasalarial es')
                    ->innerJoin('ncc.DatCargocivil mt')
                    ->innerJoin('mt.DatCargo c')
                    ->where("c.idcargo='$pid'")
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();
                return $result1;

            } else
                return $result;


        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }


    /**-----------------------------------------------------------------
     *Funcion para la integracion
     *Datos de un cargo dado el id
     */
    public function CargoDadoIDSeguridad($idcargo)
    {
        try {
            $q = Doctrine_Query::create();

            $result = $q->select("ncc.idcargomilitar as idcargomtar,ncc.dencargomilitar as denominacion,ncc.abrevcargomilitar  as abreviatura, 'militar' as tipo,c.ctp as cantPuestostp,c.ctg as cantPuestostg,ncc.*,mt.*,c.*")
                ->from('NomCargomilitar ncc ')
                ->innerJoin('ncc.DatCargomtar mt')
                ->innerJoin('mt.DatCargo c')
                ->where("c.idcargo='$idcargo'")
                ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                ->execute();

            if (count($result) == 0) {
                $q = Doctrine_Query::create();
// catego,riaocupacional,grupo escala, salario escala, 
                $result1 = $q->select("ncc.idcargociv as idcargocivil,ncc.dencargociv as denominacion,ncc.abrevcargociv as abreviatura, 'civil' as tipo,c.ctp as cantPuestostp,c.ctg as cantPuestostg,ncc.*,gc.*,co.*,es.*,mt.*
                                            ")
                    ->from('NomCargocivil ncc')
                    ->innerJoin('ncc.NomGrupocomple gc')
                    ->innerJoin('ncc.NomCategocup co')
                    ->innerJoin('gc.NomEscalasalarial es')
                    ->innerJoin('ncc.DatCargocivil mt')
                    ->innerJoin('mt.DatCargo c')
                    ->where("c.idcargo='$idcargo'")
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();
                return $result1;

            } else
                return $result;


        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }


    /** -----------------------------------------------------------------
     * Buscar el proximo id
     *
     * @return int
     */
    public function buscaridproximo()
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);

        // eliminar el campo
        $result = $query->select('max(a.idcargo) as maximo')
            ->from('DatCargo a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;
    }

    /** -----------------------------------------------------------------
     * Verificar si existe el cargo por el id
     *
     * @param unknown_type $pId
     * @return unknown
     */
    public function existeCargo($pId)
    {
        try {
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $q = Doctrine_Query::create($conn);

            $consulta = $q->select('idcargo')->from('DatCargo')->where("idcargo ='$pId'")->execute()->count();
            return $consulta == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    /**  -----------------------------------------------------------------
     *  Elimina un cargo de la base de datos
     *
     * @param int $pId
     * @return bool
     */

    public function eliminarCargo($pId)
    {
        try {
            $this->Instancia();
            $mg = ZendExt_Aspect_TransactionManager::getInstance();
            $conn = $mg->getConnection('metadatos');
            $query = Doctrine_Query::create($conn);

            $result = $query->delete('idcargo')->from('DatCargo')->where("idcargo = '$pId'")->execute();
            return $result == 0 ? false : true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    /** --------------------------------------------
     * Borrar la instancia vieja que poseia el modelo
     *
     */
    public function Instancia()
    {
        $this->instance = null;
        $this->instance = new DatCargo();
    }

    /**
     * Modificar Cargo
     *
     * @param unknown_type $pidcargo
     * @param unknown_type $pidestructuraop
     * @param unknown_type $pidespecialidad
     * @param unknown_type $pidtipocifra
     * @param unknown_type $pidprefijo
     * @param unknown_type $pctp
     * @param unknown_type $pctg
     * @param unknown_type $porden
     * @param unknown_type $pestado
     * @param unknown_type $pfechaini
     * @param unknown_type $pfechafin
     * @return unknown
     */
    public function modificarCargo($pidcargo, $pidespecialidad, $pidtipocifra, $pctp, $pctg, $porden, $pestado, $pfechaini, $pfechafin)
    {

        $this->instance = $this->conn->getTable('DatCargo')->find($pidcargo);
        $this->instance->idespecialidad = $pidespecialidad;
        $this->instance->idtipocifra = $pidtipocifra;
        //$this->instance->idprefijo			= $pidprefijo;
        $this->instance->estado = $pestado;
        $this->instance->ctp = $pctp;
        $this->instance->ctg = $pctg;
        $this->instance->orden = $porden;
        $this->instance->fechaini = $pfechaini;
        $this->instance->fechafin = $pfechafin;

        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    public function buscarCargoPorNombreArea($identidad, $nombrearea, $tipo)
    {
        try {
            /*En este sericio si se pasa 1 en el parametro $tipo devuelve los cargos militares , si pasa 2 , los civiles y si pasa 3 los civiles y militares*/
            $result = array();
            $result1 = array();

            if ($tipo == 1 || $tipo == 3) {
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result = $q->select("mt.idcargo,'militar' as tipo")
                    ->from('DatCargomtar  mt ')
                    ->innerJoin('mt.DatCargo c')
                    ->innerJoin('c.DatEstructuraop op')
                    ->where("op.denominacion ='$nombrearea' and op.idestructura='$identidad'")
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();
            }

            if ($tipo == 2 || $tipo == 3) {
                $mg = ZendExt_Aspect_TransactionManager::getInstance();
                $conn = $mg->getConnection('metadatos');
                $q = Doctrine_Query::create($conn);

                $result1 = $q->select("mt.idcargocivil,'civil' as tipo")
                    ->from('DatCargocivil mt')
                    ->innerJoin('mt.DatCargo c')
                    ->innerJoin('c.DatEstructuraop op')
                    ->where("op.denominacion ='$nombrearea' and op.idestructura='$identidad'")
                    ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                    ->execute();
            }
            return array_merge_recursive($result, $result1);
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return array();
        }
    }

}

?>