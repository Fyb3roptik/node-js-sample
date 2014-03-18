<?php
/* These are functions for cryptography. Hooray. */

/**
 * Encrypts some text with a given key. Salts the key value with the value of ENCRYPTION_KEY or if null
 * just uses the value of ENCRYPTION_KEY.
 */
function encrypt($text, $key = null) {
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, salt_key($key), $text, MCRYPT_MODE_ECB, $iv);
}

/**
 * Decrypts some text with a given key.
 */
function decrypt($crypttext, $key = null) {
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, salt_key($key), $crypttext, MCRYPT_MODE_ECB, $iv));
}

/**
 * Salts a key with the value of ENCRYPTION_KEY and then hashes the whole thing out.
 */
function salt_key($key = null) {
	return hash('sha256', ENCRYPTION_KEY . $key, true);
}
?>