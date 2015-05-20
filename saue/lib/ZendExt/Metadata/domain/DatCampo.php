<?php 
class DatCampo extends BaseDatCampo
 { 
   public function setUp() 
    {   parent::setUp(); 
         $this->hasOne('DatTabla', array('local'=>'idtabla','foreign'=>'idtabla')); 
         $this->hasOne('NomTipoDatoNomGestor', array('local'=>'idtipodatogestor','foreign'=>'idtipodatogestor')); 
         $this->hasOne('DatComponente', array('local'=>'idcampo','foreign'=>'idcampo')); 
         $this->hasMany('DatRelacion as Origen', array('local'=>'idcampo','foreign'=>'campo_origen')); 
         $this->hasMany('DatRelacion as Destino', array('local'=>'idcampo','foreign'=>'campo_destino')); 
    }
	public function cargarCampos($idtabla)
	{
		$q = Doctrine_Query::create();
		return $q->select('c.idcampo,c.denominacion,c.longitud,c.permite_nulo,c.llave_primaria,c.idtabla,tdg.*,td.*')->from ('DatCampo c')->innerJoin('c.NomTipoDatoNomGestor tdg')->innerJoin('tdg.NomTipoDato td')->where('c.idtabla = ?',$idtabla)->execute ()->toArray(true);
	}
	
	public function existeCampo($idtabla,$campo) {
		$q = Doctrine_Query :: create ();
		return $q->select('COUNT(c.idcampo) as total')
				 ->from ('DatCampo c')
				 ->where ('c.idtabla = ? AND c.denominacion = ?',array($idtabla,$campo))
                                 ->setHydrationMode(Doctrine :: HYDRATE_RECORD)
                                 ->execute()
                                 ->getFirst()
                                 ->total;
	}
	
	public function obtenerCampoPorDenominacion($idtabla,$campo) {
		$q = Doctrine_Query :: create ();
		return $q->select('c.idcampo')
				 ->from ('DatCampo c')
				 ->where ('c.idtabla = ? AND c.denominacion = ?',array($idtabla,$campo))
                                 ->setHydrationMode(Doctrine :: HYDRATE_RECORD)
                                 ->execute()
                                 ->getFirst();
	}

    public function obtenerCamposConRepresentacion($idtabla, $allmeta=false) {
		$q = Doctrine_Query :: create ();

		$data = $q->from ('DatCampo f')
				  ->innerJoin('f.DatComponente cp')
				  ->innerJoin('f.NomTipoDatoNomGestor tdg')
				  ->innerJoin('tdg.NomTipoDato td');
        if($allmeta){
              $data = $data->leftJoin('cp.DatCampoNumerico cn')
						   ->leftJoin('cp.DatCampoTexto ct')
						   ->leftJoin('cp.DatChequeo dc')
						   ->leftJoin('cp.DatFecha df')
						   ->select('cn.limite_inferior, cn.limite_superior, cn.decimal, cn.precision, ct.idexpresionregular, dc.valor, df.fecha_inicio, df.fecha_fin');
        }
        $data = $data->select ('f.idcampo, f.denominacion, f.longitud, cp.etiqueta, tdg.idtipodatogestor, td.denominacion, cp.idtipocomponente,cp.orden')
		             ->where ('f.idtabla = ?', $idtabla)
				     ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
				     ->execute();
		return $data;
	}
	
	public function obtenerLLavePrimaria($idtabla) {
		$q = Doctrine_Query :: create ();
		return $q->from('DatCampo c')
				 ->where('c.idtabla = ? AND c.llave_primaria = 1',$idtabla)
				 ->setHydrationMode(Doctrine :: HYDRATE_RECORD)
				 ->execute()
				 ->getFirst() ;
	}

        public function getTipoDatoGestor ($tipodato, $gestor) {
            $q = Doctrine_Query :: create (ZendExt_Metadata_Sql_Manager::getConnection());
            return $q->from ('NomTipoDatoNomGestor tdg')->innerJoin('tdg.NomGestor ng')->where('ng.denominacion = ? AND tdg.idtipodato = ?',array($gestor,$tipodato))->setHydrationMode (Doctrine :: HYDRATE_RECORD)->execute()->getFirst ()->idtipodatogestor;
        }


        public function cargarCmbCampos ($tabla) {
            $q = Doctrine_Query :: create ();
            return $q->from ('DatCampo f')
                      ->leftJoin('f.DatComponente cp')
                    //->innerJoin('cp.NomTipoComponente ct')
                      ->select ('f.idcampo, f.denominacion, f.longitud, cp.etiqueta, ct.tabla, f.llave_primaria')
                      ->where ('f.idtabla = ?', $tabla)
                      ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
                      ->execute();
        }

        public function obtenerUltimoOrden($idtabla) {
		$q = Doctrine_Query :: create ();
		return $q->select('COUNT(c.idcomponente)+1 as total')
                                 ->from ('DatComponente c')
				 ->innerJoin ('c.DatCampo f')
                                 ->innerJoin ('f.DatTabla t')
				 ->where ('t.idtabla = ? ',$idtabla)
                                 ->setHydrationMode(Doctrine :: HYDRATE_RECORD)
                                 ->execute()
                                 ->getFirst()
                                 ->total;
	}
 
 
}//fin clase


