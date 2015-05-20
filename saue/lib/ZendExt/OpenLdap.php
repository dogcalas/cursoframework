<?php
	class ZendExt_OpenLdap implements Zend_Auth_Adapter_Interface {
		private $usernameadmin;
		private $passadmin;
		private $dominio; 
		private $user;
		private $pass;
		/**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
		public function __construct($usernameadmin, $passadmin, $dominio, $user, $pass) {
			$this->usernameadmin = $usernameadmin;
			$this->passadmin = $passadmin;
			$this->dominio = $dominio;
			$this->user = $user;
			$this->pass = $pass;
		}
		
		public function authenticate() {			
			$ds = @ldap_connect($this->dominio,'389');
			@ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
			$r	= @ldap_bind($ds, $this->usernameadmin, $this->passadmin);
			if(!$r) {
				$code = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
				$messages[] = "$this->usernameadmin authentication failed";
				return new Zend_Auth_Result($code, '', $messages);}
			if($ds)
			{				
				$sr=@ldap_search($ds,LDAP_BASE_DN, LDAP_CAMPO_BUSCAR."=".$this->user) or die("<b>Error... </b> Search not fount");
				
				if(@ldap_count_entries($ds,$sr)<1) {	
					$code = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
					$messages[0] = "$this->user failed";
					return new Zend_Auth_Result($code, '', $messages);}
					
				$usuarioBuscado = @ldap_get_entries($ds, $sr);	
				$dn_usuario	= $usuarioBuscado[0]['dn'];
				
				if(!@ldap_bind($ds, $dn_usuario, $this->pass)) {
					$code = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
					$messages[0] = "An unexpected failure occurred";
					return new Zend_Auth_Result($code, '', $messages);}
					
				$info = @ldap_get_entries($ds, $sr);
				$this->nombre 		= $info[0][LDAP_ATRIBUTO_NOMBRE][0];
				$this->usuario 		= $info[0][LDAP_ATRIBUTO_USUARIO][0];
				$this->mail 		= $info[0][LDAP_ATRIBUTO_CORREO][0];
				$this->tipo_usuario = $info[0][LDAP_ATRIBUTO_OCUPACION][0];
			 	$this->area			= $info[0][LDAP_ATRIBUTO_AREA][0];				
				
				$messages[1] = "$this->user authentication successful";
				return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->user, $messages);
			}
			else
			{
				$code = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
				$messages[0] = "An unexpected failure occurred";
				return new Zend_Auth_Result($code, '', $messages);
			}
		}
	}