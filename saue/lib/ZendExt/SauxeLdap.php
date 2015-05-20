<?php

class ZendExt_SauxeLdap {

    var $conexion;
    var $server_ldap;    
    var $user;
    var $solapin;
    var $correo;
    var $category;
    var $nombre1;
    var $nombre2;
    var $apellido1;
    var $apellido2;
    var $ci;
    var $sexo;
    var $municipio;
    var $provincia;
    var $idProvincia;    
    var $facultad;
    var $grupo;
    var $anno_cursa;


    function __construct() {
        $this->dn_ldap = 'cn=ad search, ou=Systems, ou=UCI Domain Impersonals, dc=uci, dc=cu';
        $this->clave_ldap = 'uF2SODWAHiW0eJboFFQEAvVzJ';
        $this->server_ldap = '10.0.0.3';
        $this->connect();
    }
    function connect() {
        $this->conexion = ldap_connect($this->server_ldap);
        ldap_set_option($this->conexion, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->conexion, LDAP_OPT_REFERRALS, 0);
        $resultado = ldap_bind($this->conexion, $this->dn_ldap, $this->clave_ldap);
    }

//BUSCAR ESTUDIANTE DADO EL USUARIO
    public function findUsers1($string) {
        $sr = ldap_search($this->conexion, $string, "CN=*");

        $result = ldap_get_entries($this->conexion, $sr);
//                print_r($result); die;
        $stre='';
        for ($i = 0; $i < $result["count"]; $i++) {
            $ahora = split('@', $result[$i]['userprincipalname'][0]);
            if (count($ahora) > 1) {
//            print_r(count(split(',',$result[$i]['distinguishedname'][0])));
//                $datos[] = array(
////                 "name" => $result[$i]['samaccountname'][0],
////             "dn" => $result[$i]['dn'],
//                    "user" => $ahora[0]                      
//                );
                $stre.=';'.$ahora[0];
            }
        }
        $stre = trim($stre, ';');
        
//        print_r($stre); die; 
        return $stre;
//        return $datos;
    }
   public function findUsers($string,$numero) {
//       $area = "afsdfs,dsfsdf";
//            
//            print_r(count(split(",", $area))); die; 
       
      // $sr = ldap_search($this->conexion, "OU=Workers,OU=UCI Domain Users,DC=uci,DC=cu", "OU=*");
        $sr = ldap_search($this->conexion, $string, "OU=*");
        
        $result = ldap_get_entries($this->conexion, $sr); 
        
//       print_r($result); die;
       
        for ($i = 0; $i < $result["count"]; $i++) {
//            print_r(count(split(',',$result[$i]['distinguishedname'][0])));
            if (count(split(',',$result[$i]['dn']))==$numero)
                $datos[] = array(
                    "name" => $result[$i]['ou'][0],
                    "dn" => $result[$i]['dn']
//                    "mail" => $result[$i]['mail'][0],
//                    "solapin" => $result[$i]['postofficebox'][0],
//                    "categoria" => $result[$i]["title"][0],                        
                );
        }
//        print_r($datos); die; 
        return $datos;
    }

//SELECCIONAR USUARIO CORRECTO
    public function SeleccionarUsuarioCorrecto($string) {
        $arreglo = $this->findUsers($string);
        $usuario = split("@",$arreglo[0]["mail"]);

        if (!empty($arreglo)) {
            foreach ($arreglo as $a) {
                if ($usuario[0] === $string) {                    
                    $this->user = $usuario[0];
                    $this->correo = $a["mail"];
                    $this->solapin = $a["solapin"];
                    $this->category = $a["categoria"];
                    $this->Datos();
                    $this->SeleccionarProvincia();
                    break;
                }
            }
        }
    }

//SELECCIONAR PROVINCIA
    public function SeleccionarProvincia() {        
        $provincias = $this->ObtenerProvincias();
        $this->provincia = $provincias[$this->IdProvincia];        
    }


//UTILIZARLOOOOOOO PARA LLENAR EL ARREGLO DE PROVINCIAS
//----------------------------------------------------------------
    //OBTENER TODA LAS PROVINCIAS
    public function ObtenerProvincias(){
	$soap = new SoapClient('http://assets.uci.cu/servicios/v4/AssetsWS.wsdl');
        $resultado = $soap->ObtenerProvincias();
        foreach ($resultado as $r) {            
                $datos[$r->IdProvincia] = $r->NombreProvincia;
        }
	return $datos;
    }

//----------------------------------------------------------------
    //OBTENER LA FOTO DE USUARIO CREADO
    public function DameFoto() {
        $soap = new SoapClient('http://identificacion.uci.cu/servicios/v5/servicios.php?wsdl');
        $foto = $soap->ObtenerFotoDadoIdExpediente($this->getSolapin())->valorFoto;
        return $foto;
    }

//----------------------------------------------------------------
    //OBTENER DATOS DE LOS ESTUDIANTES
    public function Datos() {
        $soap = new SoapClient('http://akademos2.uci.cu/servicios/v4/AkademosWS.wsdl');
        $datos = $soap->ObtenerEstudianteDadoIdExpediente($this->getSolapin());
        $this->nombre1 = $datos->PrimerNombre;
        $this->nombre2 = $datos->SegundoNombre;
        $this->apellido1 = $datos->PrimerApellido;
        $this->apellido2 = $datos->SegundoApellido;
        $this->ci = $datos->CI;
        $this->sexo = $datos->Genero;
        $this->municipio = $datos->Municipio->NombreMunicipio;
        $this->IdProvincia = $datos->Municipio->IdProvincia;
        $this->facultad = $datos->Area->NombreArea;
        $this->grupo = $datos->Grupo->NombreGrupo;
        $this->anno_cursa = $datos->Nivel->IdNivel;
    }
    
        //OBTENER DATOS DE Areas
    public function Areas() {
        $soap = new SoapClient('http://assets.uci.cu/servicios/v4/AssetsWS.wsdl');
        $resultado = $soap->ObtenerAreas();
        foreach ($resultado as $r) {            
                $datos[$r->IdArea] = $r->NombreArea;
        }
	return $datos;
    }
        public function PersonasAreas() {
        $soap = new SoapClient('http://assets.uci.cu/servicios/v4/AssetsWS.wsdl');
        $resultado = $soap->ObtenerPersonasDadoIdArea();
        foreach ($resultado as $r) {            
                $datos[$r->IdArea] = $r->NombreArea;
        }
	return $datos;
    }


//----------------------------------------------------------------
    //METODOS GET DE LAS VARIABLES

    public function getUser() {
        return $this->user;
    }

    public function getSolapin() {
        return $this->solapin;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function getNombre1() {
        return $this->nombre1;
    }

    public function getNombre2() {
        return $this->nombre2;
    }

    public function getApellido1() {
        return $this->apellido1;
    }

    public function getApellido2() {
        return $this->apellido2;
    }

    public function getCi() {
        return $this->ci;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function getMunicipio() {
        return $this->municipio;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getFacultad() {
        return $this->facultad;
    }

    public function getGrupo() {
        return $this->grupo;
    }

    public function getAnno_cursa() {
        return $this->anno_cursa;
    }

    public function getProvincia() {
        return $this->provincia;
    }

}
?>
