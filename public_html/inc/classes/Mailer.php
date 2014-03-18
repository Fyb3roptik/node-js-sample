<?php
require_once 'PHPMailer/class.phpmailer.php';

/**
 * This is our abstraction class for PHPMailer.
 */
class Mailer {
	private $_mailer;

	public function __construct() {
		$this->_mailer = new PHPMailer();
		$this->_mailer->AddReplyTo('do-not-reply@siing.co', 'siing.co');
		$this->_mailer->SetFrom('do-not-reply@siing.co', 'siing.co');
	}

	public function addTo($to_email, $to_name = null) {
		$this->_mailer->AddAddress($to_email, $to_name);
	}

	public function setSubject($new_subject = null) {
		$this->_mailer->Subject = $new_subject;
	}

	public function setBody($body) {
		$this->_mailer->Body = $body;
	}

	public function send() {
		try {
			$this->_mailer->send();
		} catch(phpmailerException $e) {
			throw new Exception($e->errorMessage());
		} catch(Exception $e) {
			throw new Exception($e->errorMessage());
		}
	}
}
?>