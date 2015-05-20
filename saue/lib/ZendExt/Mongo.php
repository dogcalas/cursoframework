<?php
    /*@author Miguel la llave 
	 *@nombre ZendExt_Mongo
     *@clase para realizar consultas al gestor MOngoDB
     */
class ZendExt_Mongo {

    var $conexion;
    var $db;
    var $collection;

    function __construct() {
      // try {
        $this->conexion = new Mongo();
        $this->db = $this->conexion->compartimentacion;
        $this->collection = $this->db->acl;
  //     }
  //      catch (Exception $e) {
  //     throw new ZendExt_Exception('ECR06');
		// }
        //$this->db->dropCollection($this->collection);
        //$this->db = $this->conexion->compartimentacion;        
        //$this->collection = $this->db->acl;
         //print_r( $this->collection);die;
    }

    /*@author Miguel la llave 
	 *@nombre insertacl
     *@funcion para eliminar un permiso a un usuario sobre un recurso
     *@return void
     *@parametros 
     */
    function insertacl($acl) {

        $this->collection->insert($acl);
    }

    /*@author Miguel la llave 
	 *@nombre Save
     *@funcion para salvar los cambios de una acl o insetrtar si no existe
     *@return void
     *@parametros 
     */
    function Save($acl) {
        $this->collection->save($acl);
    }
	 /*@author Miguel la llave 
	 *@nombre modificartacl
     *@funcion para modificar acl no esta usandose
     *@return void
     *@parametros 
     */

    function modificartacl($criterio, $newacl) {
        $acl = array('$set' => $newacl);
        $todos = array("multiple" => true);
        $this->collection->update($criterio, $acl, $todos);
    }

   /*@author Miguel la llave 
	 *@nombre eliminaracl
     *@funcion para eliminar una acl
     *@return void
     *@parametros 
     */
    function eliminaracl($acl) {

        $this->collection->remove(array('_id' => "$acl"), array("justOne" => true));
    }

   /*@author Miguel la llave 
	 *@nombre estaAcl
     *@funcion para verificar si existe una acl dado el id
     *@return boolean
     *@parametros 
     */
    function estaAcl($idacl) {
        $cursor = $this->collection->find();
        foreach ($cursor as $obj)
            if ($obj['_id'] == $idacl)
                return true;

        return false;
    }

   

   /*@author Miguel la llave 
	 *@nombre buscarPorIdAcl
     *@funcion para cargar la acl por el id
     *@return datos de la acl
     *@parametros 
     */
    function buscarPorIdAcl($idacl) {

        $cursor = $this->collection->findOne(array('_id' => "$idacl"));
        $result = array();
        $temporal = array();
        $c = 0;
        foreach ($cursor as $ind) {
            if (is_array($ind)) {
                $temporal[] = $ind;
            }
        }
        foreach ($temporal as $key) {
            $err['rolacl'] = $key['rolacl'];
            $err['permiso'] = $key['permiso'];
            $err['recurso'] = $key['recurso'];
            $err['nombreregla'] = $key['nombreregla'];
            $err['campo'] = $key['campo'];
            $err['operador'] = $key['operador'];
            $err['valor'] = $key['valor'];
            $err['table_name'] = $key['table_name'];

            $result[] = $err;
        }


        return $result;
    }

   /*@author Miguel la llave 
	 *@nombre mostraraclColleccion
     *@funcion para cargar todos los datos de la acl , esta no esta en uso
     *@return void
     *@parametros 
     */
    function mostraraclColleccion() {
        $cursor = $this->collection->find();

        $result = array();
        $c = 0;
        foreach ($cursor as $obj) {
            $err['_id'] = $obj['_id'];
            foreach ($obj as $col) {
                if ($col != 0) {
                    $err['rolacl'] = $col['rolacl'];
                    $err['permiso'] = $col['permiso'];
                    $err['recurso'] = $col['recurso'];
                    $err['nombreregla'] = $col['nombreregla'];
                    $err['campo'] = $col['campo'];
                    $err['operador'] = $col['operador'];
                    $err['valor'] = $col['valor'];
                    $err['table_name'] = $col['table_name'];
                    //$c++;
                    $result[] = $err;
                }
            }
        }

        return $result;
    }
       
	/*@author Miguel la llave 
	 *@nombre modificarFila
     *@funcion modificar una tupla dentro de la acl, no esta en uso
     *@return void
     *@parametros 
     */
    function modificarFila($idacl, $fila, $filanew) {


        $cursor = $this->collection->findOne(array('_id' => "$idacl"));
        $result = array();
        $temporal = array();

        foreach ($cursor as $ind) {
            if (is_array($ind)) {
                $temporal[] = $ind;
            }
        }
        foreach ($temporal as $key) {
            $err['rolacl'] = $key['rolacl'];
            $err['permiso'] = $key['permiso'];
            $err['recurso'] = $key['recurso'];
            $err['nombreregla'] = $key['nombreregla'];
            $err['campo'] = $key['campo'];
            $err['operador'] = $key['operador'];
            $err['valor'] = $key['valor'];
            $err['table_name'] = $key['table_name'];

            $result[] = $err;
        }
        if (count($result) != 0) {
            
            
            for ($i = 0; $i < count($result); $i++) {

                if (is_array($result[$i])) {                    
                    if ($result[$i]['rolacl'] == $fila['rolacl']
                            && $result[$i]['permiso'] == $fila['permiso']
                            && $result[$i]['recurso'] == $fila['recurso']
                            ) {
                        
                        $result[$i] = $filanew;
                    }
                }
            }
            $result['_id'] = $idacl;
            $this->Save($result);
        }
    }
	
	/*@author Miguel la llave 
	 *@nombre EstaPermiso
     *@funcion para verificar si existe un permiso sobre un recurso asociado al rol de la acl
     *@return void
     *@parametros identificador de la acl,permiso a verificar,recurso, y rol de la acl(idusuario_idrol_identidad)
     */    
    public function EstaPermiso($idacl,$permiso,$recurso,$rolacl){
      $cursor = $this->collection->findOne(array('_id' => "$idacl"));
        
        $temporal = array();

        foreach ($cursor as $ind) {
            if (is_array($ind)) {
                $temporal[] = $ind;
            }
        }
        foreach ($temporal as $key) {
            
            if($key['permiso']==$permiso && $key['recurso']==$recurso && $key['rolacl']==$rolacl)
                return true;
            }
            
         return false;  
        
    }
    
	/*@author Miguel la llave 
	 *@nombre eliminarPermisodeRol
     *@funcion para eliminar un permiso a un rol de la acl sobre un recurso
     *@return void
     *@parametros identificador de la acl,permiso a verificar,recurso, y rol de la acl(idusuario_idrol_identidad)
     */
    public function eliminarPermisodeRol($idacl,$rolacl,$permiso,$recurso){
        $cursor = $this->collection->findOne(array('_id' => "$idacl"));
        
        $temporal = array();
        $result=array();
        $newacl=array();
        foreach ($cursor as $ind) {
            if (is_array($ind)) {
                $temporal[] = $ind;
            }
        }
        
        foreach ($temporal as $key) {
            $err['rolacl'] = $key['rolacl'];
            $err['permiso'] = $key['permiso'];
            $err['recurso'] = $key['recurso'];
            $err['nombreregla'] = $key['nombreregla'];
            $err['campo'] = $key['campo'];
            $err['operador'] = $key['operador'];
            $err['valor'] = $key['valor'];
            $err['table_name'] = $key['table_name'];

            $result[] = $err;
        }
        $eliminar=array();
        //print_r($rolacl.'--'.$permiso.'--'.$recurso.'--'.$temporal);die;
        for ($i = 0; $i < count($result); $i++) {

                if (is_array($result[$i])) {
                    if((string)$rolacl==(string)$result[$i]['rolacl'] && 
                       (string)$result[$i]['permiso'] == (string)$permiso &&
                         (string)$result[$i]['recurso'] == (string)$recurso
                            ){
                    $eliminar[]=$result[$i];
                   // print_r($result[$i]);die;
                    }                   
                    
                }
            }
            
            $newacl['_id']=$idacl;
            foreach($result as $tup){
                if(!in_array($tup, $eliminar))
                        $newacl[]=$tup;
            }
            if(count($result)+1> count($newacl)){            
            
            $this->Save($newacl);
    }
        
    }
    
    
    
    /*@author Miguel la llave 
	 *@nombre buscarReglasPermisoRolAcl
     *@funcion para buscar las reglas que tiene asociada un rol de la acl con repecto a un recurso dado, en dependencia del permiso
     *@return void
     *@parametros identificador de la acl,permiso a verificar,nombre de la tabla asociada al recurso y rol de la acl(idusuario_idrol_identidad)
     */
    
    function buscarReglasPermisoRolAcl($idacl,$rolacl,$permiso,$tabla){
        $reglas=array();
        
        $cursor = $this->collection->findOne(array('_id' => "$idacl"));
        
        $temporal = array();

        foreach ($cursor as $ind) {
            if (is_array($ind)) {
                $temporal[] = $ind;
            }
        }
        foreach ($temporal as $key) {
            
            if($key['permiso']==$permiso && $key['table_name']==$tabla && $key['rolacl']==$rolacl)
               if(isset($key['nombreregla'])){
               $reg['campo']=$key['campo'];
               $reg['operador']=$key['operador'];
               $reg['valor']=$key['valor'];
               $reglas[]=$reg;
               }
            }
            
          return $reglas;
        
    }
    
 function getcon() {
    return "asas";
       
        //$this->conexion ;
        

        //$this->db->dropCollection($this->collection);
        //$this->db = $this->conexion->compartimentacion;        
        //$this->collection = $this->db->acl;
         //print_r( $this->collection);die;
    }

    
    

}

?>
