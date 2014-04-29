<?php
require_once("inc/global.php");

$cache = new Cache();
$teams = array();

// Clear cache for today
$cache->delete("teams");

// Fetch todays teams
$sql = "SELECT * FROM teams WHERE created_date = '".strtotime('today')."'";
//$sql = "SELECT * FROM teams WHERE team_id = '82'";

$results = db_arr($sql);

foreach($results as $team) {
    $T = new Team($team['team_id']);
    
    $teams[$team['team_id']] = $T->getTeamLineupById();
}

$cache->set("teams", $teams, 0, 0);

var_dump($cache->get("teams"));
exit;
?>

