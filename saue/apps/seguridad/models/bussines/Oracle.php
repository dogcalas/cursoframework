<?php
class Oracle {
	
	protected $RSA;
	
	function __construct() {
		$this->RSA = new ZendExt_RSA_Facade();
	}
	
	private function getStringConex($HOST, $PORT, $SID) {
		return "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$HOST)(PORT=$PORT)))(CONNECT_DATA = (SID = $SID)))";
		}

	public function getConexion($USER, $PASSW, $HOST, $PORT, $SID){
		return oci_connect("$USER","$PASSW",$this->getStringConex($HOST, $PORT, $SID));
		}
		
	public function getOracleDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto, $SID) {
	    $key = 0;	    
		$bdArr = array();
		$conn = $this->getConexion($user, $pass, $ipgestorbd, $puerto, $SID);
		$query = 'select name from sys.v_$database';
		$BD = oci_parse($conn, $query);
		oci_execute($BD);
	    while ($row = oci_fetch_array($BD, OCI_ASSOC)) {
			$bdArr[$key]['id'] = $row['NAME'] . '-' . $idnodo;
			$bdArr[$key]['text'] = $row['NAME'];
			$bdArr[$key]['namebd'] = $row['NAME'];
			$bdArr[$key]['gestor'] = $gestor;
			$bdArr[$key]['ipgestorbd'] = $ipgestorbd;
			$bdArr[$key]['puerto'] = $puerto;
			$bdArr[$key]['user'] = $user;
			$bdArr[$key]['passw'] = $this->RSA->encrypt($pass);
			$bdArr[$key]['idgestor'] = $idgestor;
			$bdArr[$key]['idservidor'] = $idservidor;
			$bdArr[$key]['icon'] = '../../views/images/server.PNG';
			$bdArr[$key]['type'] = 'oracle';
			$bdArr[$key]['leaf'] = true;
			$key++;
		}
	    oci_close($conn);
        return	$bdArr;
	}

	public function getOracleUsers($gestor, $user, $passw, $ipgestorbd, $puerto, $SID, $limit, $start) {
		$key = 0;
        $usuarios = array();
        $pass = $this->RSA->decrypt($passw);
		$conn = $this->getConexion($user, $pass, $ipgestorbd, $puerto, $SID);
        $query="select username,USER_ID,ACCOUNT_STATUS,CREATED,EXPIRY_DATE,LOCK_DATE,PASSWORD from (
            select rownum parte, username, USER_ID, ACCOUNT_STATUS, CREATED, EXPIRY_DATE, LOCK_DATE, PASSWORD
            from ( select username,USER_ID,ACCOUNT_STATUS,CREATED,EXPIRY_DATE,LOCK_DATE,PASSWORD from dba_users )
            )
            where parte between $start and $limit";
        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
        		$radiobutton3 = off;
                $radiobutton2 = off;
                $radiobutton1 = off;
                $usuarios[$key]['user']      =    $row['USERNAME'];
                $usuarios[$key]['id']        =    $row['USER_ID'];
                $usuarios[$key]['estado']    =    $row['ACCOUNT_STATUS'];
                $usuarios[$key]['creado']    =    $row['CREATED'];
                $usuarios[$key]['expira']    =    $row['EXPIRY_DATE'];
                $usuarios[$key]['bloqueado'] =    $row['LOCK_DATE'];
                $usuarios[$key]['password']  =    $row['PASSWORD'];
                if($row['PASSWORD'] == "GLOBAL")
                    $radiobutton3 = on;
                else if($row['PASSWORD'] == "EXTERNAL")
                    $radiobutton2 = on;
                else 
                    $radiobutton1 = on;
                $usuarios[$key]['radiobutton3'] = $radiobutton3;
                $usuarios[$key]['radiobutton2'] = $radiobutton2;
                $usuarios[$key]['radiobutton1'] = $radiobutton1;
                $key++;
            }
            $consult_cant = "SELECT count (*) as cantidad FROM dba_users";
            $CANT = oci_parse($conn, $consult_cant);
            oci_execute($CANT);
            $row = oci_fetch_array($CANT, OCI_ASSOC);
            $cantidad = $row['CANTIDAD'];
            oci_close($conn);
			$array = array('datos'=>$usuarios, 'cantidad_filas'=>$cantidad);
			return $array;
	}
	
	public function loadOracleRole($gestor, $user, $passw, $ipgestorbd, $puerto, $SID, $limit, $start) {
            $key = 0;
            $roles = array();
            $array = array();
            $pass = $this->RSA->decrypt($passw);;
            $conn = $this->getConexion($user, $pass, $ipgestorbd, $puerto, $SID);
            $query = "SELECT ROLE, PASSWORD_REQUIRED FROM ( SELECT rownum parte, ROLE, PASSWORD_REQUIRED FROM (
            SELECT ROLE, PASSWORD_REQUIRED FROM dba_roles )
            )
            WHERE parte between $start AND $limit";
            $ROL = oci_parse($conn, $query);
            oci_execute($ROL);
            while ($row = oci_fetch_array($ROL, OCI_ASSOC)) {
                $roles[$key]['rol']       =    $row['ROLE'];
                $roles[$key]['pidepass']  =    $row['PASSWORD_REQUIRED'];
				$radiobutton4 = off;
                $radiobutton7 = off;
                $radiobutton6 = off;
                $radiobutton5 = off;
                if($row['PASSWORD_REQUIRED'] == "GLOBAL")
                    $radiobutton7 = on;
                else if($row['PASSWORD_REQUIRED'] == "EXTERNAL")
                    $radiobutton6 = on;
                else if($row['PASSWORD_REQUIRED'] == "YES")
                    $radiobutton5 = on;
                else 
                    $radiobutton4 = on;
                $roles[$key]['radiobutton7'] = $radiobutton7;
                $roles[$key]['radiobutton6'] = $radiobutton6;
                $roles[$key]['radiobutton5'] = $radiobutton5;
                $roles[$key]['radiobutton4'] = $radiobutton4;
                $key++;
            }
            $consult_cant = "SELECT count (*) as cantidad FROM dba_roles";
            $cant = oci_parse($conn, $consult_cant);
            oci_execute($cant);
            $row = oci_fetch_array($cant, OCI_ASSOC);
            $cantidad = $row['CANTIDAD'];
            oci_close($conn);
			$array = array('datos'=>$roles, 'cantidad_filas'=>$cantidad);
			return $array;
	}

	public function getUserData($NAME, $conn) {
	$query = "SELECT username, USER_ID, ACCOUNT_STATUS, CREATED, EXPIRY_DATE, LOCK_DATE, PASSWORD FROM dba_users WHERE username = '$NAME'";
        $stid = oci_parse($conn, $query);
        oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
        		$radiobutton3 = off;
                $radiobutton2 = off;
                $radiobutton1 = off;
                $usuarios[$key]['user']      =    $row['USERNAME'];
                $usuarios[$key]['id']        =    $row['USER_ID'];
                $usuarios[$key]['estado']    =    $row['ACCOUNT_STATUS'];
                $usuarios[$key]['creado']    =    $row['CREATED'];
                $usuarios[$key]['expira']    =    $row['EXPIRY_DATE'];
                $usuarios[$key]['bloqueado'] =    $row['LOCK_DATE'];
                $usuarios[$key]['password']  =    $row['PASSWORD'];
                if($row['PASSWORD'] == "GLOBAL")
                    $radiobutton3 = on;
                else if($row['PASSWORD'] == "EXTERNAL")
                    $radiobutton2 = on;
                else 
                    $radiobutton1 = on;
                $usuarios[$key]['radiobutton3'] = $radiobutton3;
                $usuarios[$key]['radiobutton2'] = $radiobutton2;
                $usuarios[$key]['radiobutton1'] = $radiobutton1;
                $key++;
            }
             oci_close($conn);
             return $usuarios;
	}
	
	public function exist($NAME, $conn) {
		if ($this->verifyRole($NAME, $conn) || $this->verifyUser($NAME, $conn))
			return true;
		else 
			return false;
    }
	
	private function verifyUser($NAME, $conn) {
			$query = "SELECT username FROM dba_users WHERE username = '$NAME'";
            $ociParse = oci_parse($conn, $query);
            oci_execute($ociParse);
            $valor = oci_fetch_array($ociParse, OCI_ASSOC);
            if($valor != NULL)
            	return true;
            else 
            	return false;
	}
	
	private function verifyRole($NAME, $conn) {
			$query = "SELECT * FROM dba_roles WHERE ROLE = '$NAME'";
            $ociParse = oci_parse($conn, $query);
            oci_execute($ociParse);
            $valor = oci_fetch_array($ociParse, OCI_ASSOC);
            if($valor != NULL)
            	return true;
            else 
            	return false;
	}
}

?>