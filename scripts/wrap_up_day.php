<?php
require_once dirname(__FILE__) . '/../public_html/inc/global.php';

$cache = new Cache();

$MATCHES = Match::getActiveMatches(true, false);

foreach($MATCHES as $match) {
    if($match->entry_fee > 0) {
        
        //Find the winner
        $winner = Team::getAllTeams($match->ID, true);
        
        //Determine Prize Money
        $prize = (($match->entry_fee * $match->getTotalTeams()) - $match->match_fee) * 100;

        //Give em ze money Lebowski
        $C = new Customer($winner[0]->customer_id);
        $C->funds += $prize;
        $C->write();
        
    }
    
    $match->active = 2;
    $match->write();
}

$cache->delete('teams');
$cache->delete('scores');
$cache->delete('games');
?>