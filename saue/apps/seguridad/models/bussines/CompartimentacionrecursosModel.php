<?php
/*
 *Componente para compartimentar recursos.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Mileidy Sarduy Perez    
 * @author Miguel La LLave
 * @version 1.0-0
 */
class CompartimentacionrecursosModel extends ZendExt_Model
	{
    public function CompartimentacionrecursosModel() {
          parent::ZendExt_Model();
        }
        public function cargardominioUsuario($idusuario){ 
        $permisos = SegCompartimentacionusuario::cargardominioUsuario($idusuario);
        return $permisos; 
        }
        
        public function cargarUsuariosconpermisosaDominios($filtroDominio) {
        $usuariosconpermisosadominios = SegUsuario::cargarUsuariosconpermisosaDominios($filtroDominio);
        return $usuariosconpermisosadominios; 
        }
        
        public function cargarUsuariosDominios($iddominio){ 
        $usuariosdelDominio = SegUsuarioNomDominio::cargarUsuariosDominios($iddominio);
        return $usuariosdelDominio;
        }
        
        public function usuariosSinDominio(){ 
        $usuariosSinDominio = SegUsuario::usuariosSinDominio();
        return $usuariosSinDominio; 
        }
        
        public function buscarUsuarioPorNombre($nombreusuario, $arrayresult, $iddominio, $limit, $start){ 
        $datosusuario = SegUsuario::buscarUsuarioPorNombre($nombreusuario, $arrayresult, $iddominio, $limit, $start);
        return $datosusuario; 
        }
        
        public function cantidadFilasUsuariosConNombre($nombreusuario, $arrayresult, $iddominio) {
        $cantf = SegUsuario::cantidadFilasUsuariosConNombre($nombreusuario, $arrayresult, $iddominio);
        return $cantf;
        }
        
        public function cargarGridUsuario($arrayresult, $limit, $start){ 
        $datosusuario = SegUsuario::cargarGridUsuario($arrayresult, $limit, $start);
        return $datosusuario; 
        }
        
        public function cantidadFilas($arrayresult) {
        $cantf = SegUsuario::cantidadFilas($arrayresult);
        return $cantf; 
        }
        
        public function obtenerrolBuscado($iddominio, $rolbuscado, $limit, $start) {
        $rolesencontrados = SegRol::obtenerrolBuscado($iddominio, $rolbuscado, $limit, $start);
        return $rolesencontrados;
        }
        
        public function comprobarExisteRol($idusuario, $idrol) {
        $existe = DatEntidadSegUsuarioSegRol::comprobarExisteRol($idusuario, $idrol);
        return $existe; 
        }
        
        public function obtenerrolesusuario($idusuario) {
        $datos = SegRol::obtenerrolesusuario($idusuario);
        return $datos;
        }
        
        public function cargarentidadesusuariorol($idusuario, $idrol) {
        $arrayEstructuras = DatEntidadSegUsuarioSegRol::cargarentidadesusuariorol($idusuario, $idrol);
        return $arrayEstructuras;
        }
        
         /* @author Miguel la llave 
         * @nombre cargaTableNameXml
         * @funcion para cargar nombre de la tabla dado el recurso
         * @return array ([iduser-1,nombreuser-1],...[iduser-n,nombreuser-n])
         * @parametros  alias de un recurso del xml de recursos
         */
        public function cargaTableNameXml($recurso) {
        $xml = ZendExt_FastResponse::getXML('recursos');
        $table_name = '';
        foreach ($xml->children() as $recu) {
            if ((string) $recu['alias'] == (string) $recurso) {
                $table_name = (string) $recu['table_name'];
            }
        }
        return $table_name;
    }
       
	}













?>
