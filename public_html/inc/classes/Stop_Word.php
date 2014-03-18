<?php
require_once 'Object.php';

/**
 * Active Record extension for Stop Words.
 */
class Stop_Word extends Object {
	protected $_table = 'stop_words';
	protected $_table_id = 'word_id';
}
?>