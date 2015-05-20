<?php

/**
 * This page shows a username/password login form, and passes information from it
 * to the sspmod_core_Auth_UserPassBase class, which is a generic class for
 * username/password authentication.
 *
 * @author Olav Morken, UNINETT AS.
 * @package simpleSAMLphp
 * @version $Id$
 */

if (!array_key_exists('AuthState', $_REQUEST)) {
	throw new SimpleSAML_Error_BadRequest('Missing AuthState parameter.');
}
$authStateId = $_REQUEST['AuthState'];
$tipo = $_REQUEST['tipo'];
/* Retrieve the authentication state. */
$state = SimpleSAML_Auth_State::loadState($authStateId, sspmod_core_Auth_UserPassBase::STAGEID);


$source = SimpleSAML_Auth_Source::getById($state[sspmod_core_Auth_UserPassBase::AUTHID]);

if ($source === NULL) {
	throw new Exception('Could not find authentication source with id ' . $state[sspmod_core_Auth_UserPassBase::AUTHID]);
}


if (array_key_exists('username', $_REQUEST)) {
	$username = $_REQUEST['username'];
} elseif (isset($state['core:username'])) {
	$username = (string)$state['core:username'];
} else {
	$username = '';
}

if (array_key_exists('password', $_REQUEST)) {
	$password = $_REQUEST['password'];
} else {
	$password = '';
}

if (!empty($_REQUEST['username']) || !empty($password)) {
	/* Either username or password set - attempt to log in. */

	if (array_key_exists('forcedUsername', $state)) {
		$username = $state['forcedUsername'];
	}
        $_SESSION['username_transaction']=$username;
        $_SESSION['password_transaction']=$password;

	$errorCode = sspmod_core_Auth_UserPassBase::handleLogin($authStateId, $username, $password,$tipo);
} else {
	$errorCode = NULL;
}

$globalConfig = SimpleSAML_Configuration::getInstance();
if($tipo ==1){
	
 $t = new SimpleSAML_XHTML_Template($globalConfig, 'core:loginface.php');

$t->data['stateparams'] = array('AuthState' => $authStateId);
if (array_key_exists('forcedUsername', $state)) {
	$t->data['username'] = $state['forcedUsername'];
	$t->data['forceUsername'] = TRUE;
} else {
	$t->data['username'] = $username;
	$t->data['forceUsername'] = FALSE;
}
$t->data['links'] = $source->getLoginLinks();
$t->data['errorcode'] = $errorCode;

if (isset($state['SPMetadata'])) {
	$t->data['SPMetadata'] = $state['SPMetadata'];
} else {
	$t->data['SPMetadata'] = NULL;
}
//echo '<pre>';print_r($t);die;
$t->show();
exit();   
    
    
    
}
$t = new SimpleSAML_XHTML_Template($globalConfig, 'core:loginuserpass.php');

$t->data['stateparams'] = array('AuthState' => $authStateId);
if (array_key_exists('forcedUsername', $state)) {
	$t->data['username'] = $state['forcedUsername'];
	$t->data['forceUsername'] = TRUE;
} else {
	$t->data['username'] = $username;
	$t->data['forceUsername'] = FALSE;
}
$t->data['links'] = $source->getLoginLinks();
$t->data['errorcode'] = $errorCode;

if (isset($state['SPMetadata'])) {
	$t->data['SPMetadata'] = $state['SPMetadata'];
} else {
	$t->data['SPMetadata'] = NULL;
}
//echo '<pre>';print_r($t);die;
$t->show();
exit();


?>