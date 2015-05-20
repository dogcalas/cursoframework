<?php 
class DatTabla extends BaseDatTabla
 { 
	public function setUp() 
        {   parent::setUp();
         $this->hasMany('DatCampo', array('local'=>'idtabla','foreign'=>'idtabla')); 

        }

	public function existeTabla($esquema,$tabla) {
		$q = Doctrine_Query :: create ();
		return $q->select('t.idtabla,t.importada')
                         ->from ('DatTabla t')
                         ->where ('t.esquema = ? AND t.tabla = ?',array($esquema,$tabla))
                         ->setHydrationMode(Doctrine :: HYDRATE_RECORD)
                         ->execute()
                         ->getFirst();
	}
	
	function obtenerTablasDestinoDe($idtable) {            
            $q = Doctrine_Query :: create ();			
            return $q->select('d.idcampo, r.campo_destino, t.idtabla')
			->from ('DatRelacion r')
			->innerJoin('r.Destino d')
                        ->innerJoin('r.Origen o')
                        ->innerJoin('d.DatTabla t')						
			->innerJoin('o.DatTabla to')
			->where('to.idtabla = ?',array($idtable))                        
			->execute ()
			->toArray (true);
    } 

        function cargarTablas ($limit, $start) {
            $q = Doctrine_Query :: create ();
            return $q->from ('DatTabla t')->limit ($limit)->offset($start)->execute ()->toArray ();
        }

        function obtenerTablasDadoEsquema ($esquema) {
            $q = Doctrine_Query :: create ();
            return $q->from ('DatTabla t')->where('t.esquema = ?',$esquema)->execute ()->toArray ();
        }

        function cargarNomencladores () {
            $q = Doctrine_Query :: create ();
            return $q->select('t.idtabla as id, t.tabla as text, true as leaf, t.alias as alias, t.arbol as tree')->from ('DatTabla t')->where('t.importada != 1')->setHydrationMode(Doctrine :: HYDRATE_ARRAY)->execute () ;
        }

        function contarTablas () {
            $q = Doctrine_Query :: create ();
            return $q->select ('COUNT(idtabla) as total')->from ('DatTabla t')->execute ()->getFirst ()->total;
        }   
        
        function buscarEnTablas ($query, $limit, $start) {            
            $q  = Doctrine_Query :: create ();
            $comun = $q->from ('DatTabla t')
					   ->where('t.esquema LIKE ? OR t.tabla LIKE ? OR t.alias LIKE ?', array($query, $query, $query));
		    $data->total = count($comun->execute ()->toArray ());  
			$data->data = $comun ->limit ($limit)
								  ->offset($start)
								  ->execute ()->toArray ();
			return $data;
        }   
        
 
}//fin clase


