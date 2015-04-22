<?php
require_once dirname(__FILE__) . '/../public_html/inc/global.php';

$cache = new Cache();

$MATCHES = Match::getActiveMatches(true, false);

foreach($MATCHES as $match) {
    $MP = new Match_Price($match->match_price_id);
    
    if($MP->prize > 0) {
        
        //Find the winner
        $winner = Team::getAllTeams($match->ID, true);

        //Give em ze money Lebowski
        $C = new Customer($winner[0]->customer_id);
        $C->funds += ($MP->prize * 100);
        $C->write();
        
    }
    
    $match->active = 2;
    $match->write();
}

$cache->delete('teams');
$cache->delete('scores');
$cache->delete('games');
?>