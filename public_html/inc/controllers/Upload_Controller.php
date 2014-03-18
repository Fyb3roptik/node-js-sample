<?php 
class Upload_Controller extends Controller {
	public function index() {
		$this->_config(true);
		$UUID = uniqid();
		$V = new View('upload_playlist.php');
		$V->bind('UUID', $UUID);
		$this->_setView($V);	
	}
	
	public function process($progress_id = "") {
		global $CUSTOMER;
		if($progress_id != "") {
			echo json_encode(uploadprogress_get_info($progress_id));
			exit;
		}
		$this->_config(true);
		
		$tempFile = $_FILES['file_upload']['tmp_name'];
		$targetPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
		$targetFile = str_replace('//','/',$targetPath) . $_FILES['file_upload']['name'];
		
		move_uploaded_file($tempFile,$targetFile);
		
		//Check File extension and do the right thing
		$allowedExtensions = array("txt","csv");
		
		//Create Database Table
		
		$sql = "CREATE TABLE IF NOT EXISTS `".$CUSTOMER->ID."_playlist` (`".$CUSTOMER->ID."_playlist_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,`title` VARCHAR( 255 ) NOT NULL,`artist` VARCHAR( 255 ) NOT NULL) ENGINE = MYISAM";
		
		db_query($sql);
		
		$handle = fopen($targetFile, "r");
		
		if(in_array(end(explode(".", strtolower($_FILES['file_upload']['name']))), $allowedExtensions)) {
			if(in_array(end(explode(".", strtolower($_FILES['file_upload']['name']))), "txt")) {
				while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
					$sql = "SELECT * FROM ".$CUSTOMER->ID."_playlist WHERE title = '".addslashes($data[0])."' AND artist = '".addslashes($data[1])."'";
					$rs = db_arr($sql);
					
					if(empty($rs[0])) {
						$import="INSERT INTO ".$CUSTOMER->ID."_playlist (title,artist) values('".addslashes($data[0])."','".addslashes($data[1])."')";
						db_query($import);
					}
				}
			} else {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$sql = "SELECT * FROM ".$CUSTOMER->ID."_playlist WHERE title = '".addslashes($data[0])."' AND artist = '".addslashes($data[1])."'";
					$rs = db_arr($sql);
					
					if(empty($rs[0])) {
						$import="INSERT INTO ".$CUSTOMER->ID."_playlist (title,artist) values('".addslashes($data[0])."','".addslashes($data[1])."')";
						db_query($import);
					}
				}
			}
			
			fclose($handle);
			
			unlink($targetFile);
			
			//Drop fulltext if exists
			$sql = "SELECT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE
					`TABLE_CATALOG` IS NULL AND `TABLE_SCHEMA` = 'karaoke_vibe' AND
					`TABLE_NAME` = '".$CUSTOMER->ID."_playlist'";
			$rs = db_arr($sql);

			if(!empty($rs[1])) {
				$sql = "ALTER TABLE `".$CUSTOMER->ID."_playlist` DROP INDEX `search_".$CUSTOMER->ID."`";
				db_query($sql);
			}
			
			$sql = "ALTER TABLE `".$CUSTOMER->ID."_playlist` ADD FULLTEXT `search_".$CUSTOMER->ID."` (`title` ,`artist`)";
			db_query($sql);
			
			$CUSTOMER->has_playlist = '1';
			$CUSTOMER->write();
			
			$this->redirect("/".$CUSTOMER->username."/playlist/");
		}
		
		exit;
	}
	
	private function _config($require_login = false) {
		if(true == $require_login) {
			$this->_checkPermissions();
		}
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
		$REDIR = sanitize_string(exists('go', $_GET));
		global $LAYOUT_TITLE;
		$this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE .= ' | Upload Playlist');
	}
	
	private function _checkPermissions() {
		if(false == $this->_user->exists()) {
			$_SESSION['login_redirect'] = $_SERVER['REDIRECT_URL'];
			$this->redirect(LOC_LOGIN);
			exit;
		}
	}
}
?>