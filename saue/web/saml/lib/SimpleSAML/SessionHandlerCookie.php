<?php

/**
 * This file is part of SimpleSAMLphp. See the file COPYING in the
 * root of the distribution for licence information.
 *
 * This file defines a base class for session handlers that need to store
 * the session id in a cookie. It takes care of storing and retrieving the
 * session id.
 *
 * @author Olav Morken, UNINETT AS. <andreas.solberg@uninett.no>
 * @package simpleSAMLphp
 * @abstract
 * @version $Id: SessionHandlerCookie.php 2494 2010-08-09 08:51:38Z olavmrk $
 */
abstract class SimpleSAML_SessionHandlerCookie
extends SimpleSAML_SessionHandler {

	/* This variable contains the current session id. */
	protected $session_id = NULL;



	/* This constructor initializes the session id based on what
	 * we receive in a cookie. We create a new session id and set
	 * a cookie with this id if we don't have a session id.
	 */
	protected function __construct() {
		/* Call the constructor in the base class in case it should
		 * become necessary in the future.
		 */
		parent::__construct();

		/* Attempt to retrieve the session id from the cookie. */
		if(array_key_exists('SimpleSAMLSessionID', $_COOKIE)) {
			$this->session_id = $_COOKIE['SimpleSAMLSessionID'];
		}

		/* We need to create a new session. */

		if (headers_sent()) {
			throw new SimpleSAML_Error_Exception('Cannot create new session - headers already sent.');
		}

		/* Check if we have a valid session id. */
		if(self::isValidSessionID($this->session_id)) {
			/* We are done now if it was valid. */
			return;
		}

		/* We don't have a valid session. Create a new session id. */
		$this->session_id = self::createSessionID();

		$this->setCookie('SimpleSAMLSessionID', $this->session_id);
	}


	/**
	 * Retrieve the session id of saved in the session cookie.
	 *
	 * @return string  The session id saved in the cookie.
	 */
	public function getCookieSessionId() {
		return $this->session_id;
	}


	/* This static function creates a session id. A session id consists
	 * of 32 random hexadecimal characters.
	 *
	 * Returns:
	 *  A random session id.
	 */
	private static function createSessionID() {
		return SimpleSAML_Utilities::stringToHex(SimpleSAML_Utilities::generateRandomBytes(16));
	}


	/* This static function validates a session id. A session id is valid
	 * if it only consists of characters which are allowed in a session id
	 * and it is the correct length.
	 *
	 * Parameters:
	 *  $session_id  The session id we should validate.
	 *
	 * Returns:
	 *  TRUE if this session id is valid, FALSE if not.
	 */
	private static function isValidSessionID($session_id) {
		if(!is_string($session_id)) {
			return FALSE;
		}

		if(strlen($session_id) != 32) {
			return FALSE;
		}

		if(preg_match('/[^0-9a-f]/', $session_id)) {
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Check whether the session cookie is set.
	 *
	 * This function will only return FALSE if is is certain that the cookie isn't set.
	 *
	 * @return bool  TRUE if it was set, FALSE if not.
	 */
	public function hasSessionCookie() {

		return array_key_exists('SimpleSAMLSessionID', $_COOKIE);
	}

}

?>