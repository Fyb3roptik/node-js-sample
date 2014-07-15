<?php
require_once dirname(__FILE__) . '/../public_html/inc/global.php';

$LINEUP = new Lineup();
$cache = new Cache();

$cities = array("Angels" => "LAA", "Diamondbacks" => "ARI", "Braves" => "ATL", "Orioles" => "BAL", "Red Sox" => "BOS", "Cubs" => "CHC", "White Sox" => "CWS", "Reds" => "CIN", "Indians" => "CLE", "Rockies" => "COL", "Tigers" => "DET", "Marlins" => "MIA", "Astros" => "HOU", "Royals" => "KC", "Dodgers" => "LAD", "Brewers" => "MIL", "Twins" => "MIN", "Mets" => "NYM", "Yankees" => "NYY", "Athletics" => "OAK", "Phillies" => "PHI", "Pirates" => "PIT", "Padres" => "SD", "Giants" => "SF", "Mariners" => "SEA", "Cardinals" => "STL", "Rays" => "TB", "Rangers" => "TEX", "Blue Jays" => "TOR", "Nationals" => "WSH");

foreach($cities as $team => $city) {
    $LIST[$city] = $LINEUP->getCurrentLineups($city);
}

$cache->delete('lineups');
$cache->set('lineups', $LIST, 0, 0);

var_dump($cache->get('lineups'));
?>