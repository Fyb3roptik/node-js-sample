<?php

/**
 * Active Record for Date Settings information.
 */
class Date_Settings extends Object {
	protected $_table = 'date_settings';
	protected $_table_id = 'date_settings_id';
	
	public static function hasSeasonStarted() {
    	$DS = new Date_Settings("opening_day", "key");
    	
    	if($DS->date <= time()) {
        	return true;
    	} else {
        	return false;
    	}
	}
}
?>