<?php
class Mission extends Object {
	protected $_table = 'mission';
	protected $_table_id = 'mission_id';
	
	public static function getCurrentMission() {
		$mission = array();
		$sql = SQL::get()
			->select('mission_id')
			->from('mission')
			->where("active = '1'");
		$rs = db_arr($sql);
		
		if(!empty($rs)) {
			foreach($rs as $r) {
				$mission = new Mission($r['mission_id']);
			}
		}
		
		return $mission;
	}
	
	public function getVersions() {
		$dates = db_arr("SELECT mission_id, date FROM mission ORDER BY date DESC");
		$version = array();
	
		foreach($dates as $k=>$v)
		{
			$version[$k]['mission_id'] = $dates[$k]['mission_id'];
			$version[$k]['date'] = date("Y-m-d h:i", $dates[$k]['date']); 
			
		}
		
		return $version;
	}
}
?>
