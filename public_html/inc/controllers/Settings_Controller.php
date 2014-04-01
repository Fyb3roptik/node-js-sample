<?php
require_once 'Controller.php';

/**
 * Controller for Settings and what not.
 */
class Settings_Controller extends Controller {
    public function score() {
        $this->_configure();
        $V = new View('settings_score.php');
		$this->_setView($V);
		$MS = new Message_Stack();
		
		$SETTINGS = Score_Settings::getSettings();
		$V->bind('SETTINGS', $SETTINGS);
		
		$LAYOUT_TITLE = "Beast Franchise | Manage Score Settings";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
		
		$V->bind('TITLE', 'Manage Score Settings');
		$V->bind('MS', $MS);
        
    }
    
    public function saveScoreSettings() {
        $this->_configure();
        
        $MS = new Message_Stack();

        foreach($_POST as $k => $v) {
            $value = post_var($k);
            $S = new Score_Settings($k, 'key');
            $S->value = $value; 
            $S->write(); 
        }
        
        redirect('/admin/settings/score/');
		exit;
    }
    
    /**
	 * Sets up our template / bindings.
	 */
	private function _configure() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('ADMIN', $this->_user);
	}
}
?>