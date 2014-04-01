<?php

/**
 * Active Record for Score Settings information.
 */
class Score_Settings extends Object {
	protected $_table = 'score_settings';
	protected $_table_id = 'score_settings_id';


    public static function getSettings() {
        $settings = array();
        
        $sql = "SELECT score_settings_id FROM score_settings";
        $results = db_arr($sql);
        
        foreach($results as $ss) {
            $settings[] = new Score_Settings($ss['score_settings_id']);
        }
        
        return $settings;
    }
}
?>