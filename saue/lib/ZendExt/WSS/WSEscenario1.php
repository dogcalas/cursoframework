<?PHP


/*
  include('WSSoapClient.class.php');
  $url = "the WSDL address";
  $client = new WSSoapClient($url);
  $client->__setUsernameToken('user','passw');
  $params=array(); //Put the service parameters here
  $result=$client->__soapCall('method_name',$params);
  print_r($result);//The easyest way to see the result.
*/

class ZendExt_WSS_Escenario1 {


  function obtenerUSerPass(){
        $global = ZendExt_GlobalConcept::getInstance();
        $user = $global->Perfil->usuario;
        $a = new SegUsuario();
        $array = $a->obtenerpass($user);
        foreach ($array as $key => $value) {
            if($value[nombreusuario]==$user){
                $pass = $value[contrasenna];
                break;
            }
        }
        $result[0]= $user;
        $result[1]= $pass;
    }

/*Generates de WSSecurity header*/
public function wssecurityHeader($user,$pass){
    
    
    $created = date("Y-m-d\TH:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))) . "Z";
   
    // definición de los namespaces  
    $ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    $wsu = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';

    // creando el  elemento UsernameToken  
    $token = new stdClass;
    $token->Username = new SOAPVar($user, XSD_STRING, null, null, null, $ns);
    $token->Password = new SOAPVar($pass, XSD_STRING, null, null, null, $ns);
       

    // creando el  elemento Security  
    $wsec = new stdClass;
    $wsec->UsernameToken = new SoapVar($token, SOAP_ENC_OBJECT, null, null, null, $ns);
    
    // creando el  header  
    $headers = new SOAPHeader($ns, 'Security', $wsec);
   


    return $headers;
}
public function wssecurityHeaderEsc2($user,$pass){
    //codificando usuario y pass
    $userCod = base64_encode($user);
    $passCod = base64_encode($pass);
    $created = date ("Y-m-d\TH:i:s", mktime (date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")))."Z";  
       
    // definición de los namespaces  
    $ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';  
    $wsu = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';  
       
    // creando el  elemento UsernameToken  
    $token = new stdClass;  
    $token->Username = new SOAPVar($userCod, XSD_STRING, null, null, null, $ns);  
    $token->Password = new SOAPVar($passCod, XSD_STRING, null, null, null, $ns);  
       
    // creando el  elemento Timestamp  
    $timestamp = new stdClass;  
    $timestamp->Created = new SOAPVar($created, XSD_STRING, null, null, null, $wsu);  
       
    // creando el  elemento Security  
    $wsec = new stdClass;  
    $wsec->UsernameToken = new SoapVar($token,     SOAP_ENC_OBJECT, null, null, null, $ns);  
    $wsec->Timestamp     = new SoapVar($timestamp, SOAP_ENC_OBJECT, null, null, null, $wsu);  
       
    // creando el  header  
    $headers = new SOAPHeader($ns, 'Security', $wsec); 
   


    return $headers;
}


public function validarEscenario1($user,$pass){
    
    $integrator = ZendExt_IoC::getInstance();
    $usuarios = $integrator->seguridad->obtenerUsers();
    $password = md5($pass);   
    foreach ($usuarios as $key => $value) {
        if($value[nombreusuario]==$user){
            if($value[contrasenna]==$password){
            return true;
            break;
        }
      }
    }
}
    public function validarEscenario2($user,$pass){
        $usuarioDec = base64_decode($user);
        $passwordDec = base64_decode($pass);
        $password = md5($passwordDec); 
        $integrator = ZendExt_IoC::getInstance();
        $usuarios = $integrator->seguridad->obtenerUsers();
        
        foreach ($usuarios as $key => $value) {
            if($value[nombreusuario]==$usuarioDec){
                if($value[contrasenna]==$password){
                 return true;
                 break;
                 
                 }
            }
        }
    }

}
?>
