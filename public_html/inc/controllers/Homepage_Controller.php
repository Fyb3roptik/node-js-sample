<?php 
class Homepage_Controller extends Controller {
	
	public function index() {
		$this->_configure();
		$MISSION = Mission::getCurrentMission();
		$mission_version = Mission::getVersions();
		$ABOUT = About::getCurrentAbout();
		$about_version = About::getVersions();
		$V = new View('homepage_index.php');
		$V->bind('MISSION', $MISSION);
		$V->bind('mission_version', $mission_version);
		$V->bind('ABOUT', $ABOUT);
		$V->bind('about_version', $about_version);
		$this->_setView($V);
	}
	
	public function saveMission() {		
		//Set all missions to inactive
		$sql = "UPDATE mission SET active = '0'";
		db_query($sql);
		
		//Perform form submission
		$MISSION = new Mission();
		
		$MISSION->text = urlencode($_POST['mission']);
		$MISSION->active = 1;
		$MISSION->date = time();
		$MISSION->write();
		
		redirect('/admin/homepage/');
	}
	
	public function saveAbout() {
		//Set all missions to inactive
		$sql = "UPDATE about SET active = '0'";
		db_query($sql);
		
		//Perform form submission
		$ABOUT = new About();
		
		$ABOUT->text = urlencode($_POST['about']);
		$ABOUT->active = 1;
		$ABOUT->date = time();
		$ABOUT->write();
		
		redirect('/admin/homepage/');
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