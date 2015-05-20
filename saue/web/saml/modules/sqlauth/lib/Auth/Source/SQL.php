<?php


/**
 * Simple SQL authentication source
 *
 * This class is an example authentication source which authenticates an user
 * against a SQL database.
 *
 * @package simpleSAMLphp
 * @version $Id$
 */
class sspmod_sqlauth_Auth_Source_SQL extends sspmod_core_Auth_UserPassBase {

	/**
	 * The DSN we should connect to.
	 */
	private $dsn;
	/**
	 * The username we should connect to the database with.
	 */
	private $username;
	/**
	 * The password we should connect to the database with.
	 */
	private $password;
	/**
	 * The query we should use to retrieve the attributes for the user.
	 *
	 * The username and password will be available as :username and :password.
	 */
	private $query;

	/**
	 * Constructor for this authentication source.
	 *
	 * @param array $info  Information about this authentication source.
	 * @param array $config  Configuration.
	 */
	public function __construct($info, $config) {
		assert('is_array($info)');
		assert('is_array($config)');

		/* Call the parent constructor first, as required by the interface. */
		parent :: __construct($info, $config);

		/* Make sure that all required parameters are present. */
		foreach (array (
				'dsn',
				'username',
				'password',
				'query'
			) as $param) {
			if (!array_key_exists($param, $config)) {
				throw new Exception('Missing required attribute \'' . $param .
				'\' for authentication source ' . $this->authId);
			}

			if (!is_string($config[$param])) {
				throw new Exception('Expected parameter \'' . $param .
				'\' for authentication source ' . $this->authId .
				' to be a string. Instead it was: ' .
				var_export($config[$param], TRUE));
			}
		}

		$this->dsn = $config['dsn'];
		$this->username = $config['username'];
		$this->password = $config['password'];
		$this->query = $config['query'];
	}

	/**
	 * Create a database connection.
	 *
	 * @return PDO  The database connection.
	 */
	private function connect() {
		try {
			$db = new PDO($this->dsn, $this->username, $this->password);
		} catch (PDOException $e) {
			throw new Exception('sqlauth:' . $this->authId . ': - Failed to connect to \'' .
			$this->dsn . '\': ' . $e->getMessage());
		}

		$db->setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);

		$driver = explode(':', $this->dsn, 2);
		$driver = strtolower($driver[0]);

		/* Driver specific initialization. */
		switch ($driver) {
			case 'mysql' :
				/* Use UTF-8. */
				$db->exec("SET NAMES 'utf8'");
				break;
			case 'pgsql' :
				/* Use UTF-8. */
				$db->exec("SET NAMES 'UTF8'");
				break;
		}

		return $db;
	}

	/**
	 * Attempt to log in using the given username and password.
	 *
	 * On a successful login, this function should return the users attributes. On failure,
	 * it should throw an exception. If the error was caused by the user entering the wrong
	 * username or password, a SimpleSAML_Error_Error('WRONGUSERPASS') should be thrown.
	 *
	 * Note that both the username and the password are UTF-8 encoded.
	 *
	 * @param string $username  The username the user wrote.
	 * @param string $password  The password the user wrote.
	 * @return array  Associative array with the users attributes.
	 */
	protected function login($username, $password) {
//Direccion de la servidora
		$dir_index = __FILE__;

		//Direccion del fichero de configuracion
		$config_file = substr($dir_index, 0, strrpos($dir_index, 'web')) . 'apps/comun/config.php';

		if (!file_exists($config_file)) //Si no existe el fichero de configuracion
			{
			//Se dispara una excepcion
			throw new Exception('El fichero de configuracion no existe');
		}
		elseif (!is_readable($config_file)) //Si no se puede leer
		{
			//Se dispara una excepcion
			throw new Exception('No se pudo leer el fichero de configuracion. Acceso denegado.');
		} else //Si existe el fichero y se puede leer
			{
			//Se inicializa la variable de configuración
			$config = array ();

			//Se incluye el fichero
			include_once ($config_file);

			if (!isset ($config['include_path']))
				throw new Exception('El framework no est&aacute; configurado correctamente.');

			//Se inicializa el include path de php a partir de la variable de configuracion
			set_include_path($config['include_path']);

			//Se inicia la carga automatica de clases y ficheros
			$loader_file = 'Zend/Loader/Autoloader.php';
			if (!@ include_once ($loader_file))
				throw new Exception('El framework no est&aacute; configurado correctamente.');
			$autoloader = Zend_Loader_Autoloader :: getInstance();
			$autoloader->setFallbackAutoloader(true);
			$app = new ZendExt_App();
			$app->initSaml($config);
		}

		$integrator = ZendExt_IoC :: getInstance();
		$res = $integrator->seguridad->AutenticarUsuario($username, $password);

		//Zend_Session::writeClose();
		switch ($res) {
			case 0 :
				throw new SimpleSAML_Error_Error('BLOCKUSER');
				break;
			case 1 :
				throw new SimpleSAML_Error_Error('WRONGHOST');
				break;
			case 2 :
				{

					if (isset ($_COOKIE[$username])) {

						if ($_COOKIE[$username] < 2)
							setcookie($username, $_COOKIE[$username] + 1);
						else
							if (!$integrator->seguridad->blockUser($username))
								throw new SimpleSAML_Error_Error('WRONGUSERPASS');
					} else {
						setcookie($username, 1);
					}

					throw new SimpleSAML_Error_Error('WRONGUSERPASS');

				}
				break;
			case 3 :
				throw new SimpleSAML_Error_Error('EXPIRE');
			default :
				break;
		}
		$autoloader->setFallbackAutoloader(false);
		$attr['certificado'][] = $res;
		return $attr;
	}

	protected function loginface($username, $password) {
		//Direccion de la servidora
		$dir_index = __FILE__;

		//Direccion del fichero de configuracion
		$config_file = substr($dir_index, 0, strrpos($dir_index, 'web')) . 'apps/comun/config.php';

		if (!file_exists($config_file)) { //Si no existe el fichero de configuracion
			//Se dispara una excepcion
			throw new Exception('El fichero de configuracion no existe');
		}
		elseif (!is_readable($config_file)) { //Si no se puede leer
			//Se dispara una excepcion
			throw new Exception('No se pudo leer el fichero de configuracion. Acceso denegado.');
		} else { //Si existe el fichero y se puede leer
			//Se inicializa la variable de configuración
			$config = array ();

			//Se incluye el fichero
			include_once ($config_file);

			if (!isset ($config['include_path']))
				throw new Exception('El framework no esta configurado correctamente.');

			//Se inicializa el include path de php a partir de la variable de configuracion
			set_include_path($config['include_path']);

			//Se inicia la carga automatica de clases y ficheros
			$loader_file = 'Zend/Loader/Autoloader.php';
			if (!@ include_once ($loader_file))
				throw new Exception('El framework no esta configurado correctamente.');
			$autoloader = Zend_Loader_Autoloader :: getInstance();
			$autoloader->setFallbackAutoloader(true);
			$app = new ZendExt_App();
			$app->initSaml($config);
		}

		$integrator = ZendExt_IoC :: getInstance();

		$rec = $integrator->seguridad->loginFace($username);

		$reconoci = new Rec();
		// print_r($rec);die;
		if ($rec['metodorec'] == 'PCA') {

			$id = $reconoci->reconocimientoPCA($rec['img'], $rec['xml'], $rec['mdist'], $rec['cantimg'], $rec['knn']);
		} else {

			$id = $reconoci->reconocimientoWPCA($rec['img'], $rec['xml'], $rec['mdist'], $rec['cantimg'], $rec['knn']);
		}

		if ($id != $rec['idusuario']) {

			if (isset ($_COOKIE[$username])) {

				if ($_COOKIE[$username] < 2)
					setcookie($username, $_COOKIE[$username] + 1);
				else {

					if (!$integrator->seguridad->blockUser($username))
						throw new SimpleSAML_Error_Error('WRONRECOGNITION');
				}
			} else {
				setcookie($username, 1);
			}
			$res = $integrator->seguridad->AutenticarUsuario($username, $password);

			if ($res == 0)
				throw new SimpleSAML_Error_Error('BLOCKUSER');

			throw new SimpleSAML_Error_Error('WRONRECOGNITION');
		} else {
			$res = $integrator->seguridad->AutenticarUsuario($username, $password);

			if ($res == 0)
				throw new SimpleSAML_Error_Error('BLOCKUSER');

			$autoloader->setFallbackAutoloader(false);
			$attr['certificado'][] = $res;
		}

		return $attr;
	}

}
?>
