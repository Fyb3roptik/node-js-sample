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
    
    public function matchPrice() {
        $this->_configure();
        $V = new View('settings_matchPrice.php');
		$this->_setView($V);
		$MS = new Message_Stack();
		
		$PRICES = Match_Price::getPrices(false);
		$V->bind('PRICES', $PRICES);
		
		$LAYOUT_TITLE = "Beast Franchise | Manage Match Prices";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
		
		$V->bind('TITLE', 'Manage Match Prices');
		$V->bind('MS', $MS);
    }
    
    public function saveMatchPrice() {
        $match_price_id = post_var('match_price_id');
        
        $MP = new Match_Price($match_price_id);
        
        $match['price'] = post_var('price');
        $match['profit'] = post_var('profit');
        $match['prize'] = post_var('prize');
        $match['active'] = post_var('active', 0);
        $match['promotion_eligible'] = post_var('promotion_eligible', 0);
        
        $MP->load($match);
        $MP->write();
        
        redirect("/admin/settings/matchPrice");
    	exit;
    }
    
    public function deleteMatchPrice($match_price_id) {
    	$MP = new Match_Price($match_price_id);
    	$MP->delete();
    	
    	redirect("/admin/settings/matchPrice");
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