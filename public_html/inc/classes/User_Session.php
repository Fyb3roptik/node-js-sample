<?php
require_once 'Object.php';

/**
 * This class handles User sessions and acts as a factory to build the right type of User subclass based on a token.
 */
class User_Session extends Object {
	protected $_table = 'user_sessions';
	protected $_table_id = 'user_session_id';

	protected $_set_hooks = array('user_type' => '_setUserType');

	protected $_default_vals = array('user_type' => User::TYPE_CUSTOMER);

	protected function _setUserType($user_type) {
		$user_type = trim($user_type);
		$good_types = array(User::TYPE_CUSTOMER, User::TYPE_SALES, User::TYPE_ADMIN);
		if(true == in_array($user_type, $good_types, true)) {
			return $user_type;
		} else {
			throw new Exception('Bad user type!');
		}
	}

	/**
	 * Takes a session token and returns the appropriate User-type object based on it.
	 *
	 * @param token Session token we need to look up.
	 * @return Returns a User-type object based on what was found in the token.
	 */
	public function tokenFactory($token, $default_type = User::TYPE_CUSTOMER) {
		$US = new User_Session($token, 'token');
		if(false == $US->exists()) {
			$US->user_type = $default_type;
		}
		switch($US->user_type) {
			case User::TYPE_CUSTOMER: {
				return Object_Factory::OF()->newObject('Customer', $US->user_id);
				break;
			}

			case User::TYPE_SALES: {
				return Object_Factory::OF()->newObject('Sales_Rep', $US->user_id);
				break;
			}

			case User::TYPE_ADMIN: {
				return Object_Factory::OF()->newObject('Admin', $US->user_id);
				break;
			}

			default: {
				throw new Exception("ERROR: User_Session can't determine what kind of User we should build for this token.");
				break;
			}
		}
	}

	/**
	 * Generates a new unique token, sets it internally, and returns it.
	 *
	 * @return Returns the token it generated.
	 */
	public function generateToken() {
		$user_id = intval($this->ID);
		$token_components = array(intval($user_id), time(), session_id(), date('Y-m-d'), microtime());
		$good_token = false;
		$token = '';
		while(false == $good_token) {
			shuffle($token_components);
			$string = implode($token_components);
			$token = sha1($string);
			$good_token = $this->_tokenIsUnique($token);
		}
		$this->token = $token;
		return $token;
	}

	/**
	 * Determines whether or not a given session token is unique. This should prevent
	 * session token collision.
	 */
	private function _tokenIsUnique($token) {
		$unique = true;
		$sql = "SELECT token
			FROM `user_sessions`
			WHERE token = '" . db_input($token) . "'";
		$query = db_query($sql);
		if($query->num_rows > 0) {
			$unique = false;
		}
		return $unique;
	}
}
?>
