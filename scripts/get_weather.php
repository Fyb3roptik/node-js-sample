<?php
require_once dirname(__FILE__) . '/../public_html/inc/global.php';

$cache = new Cache();  

$cities = array("Angels" => "33.8352932,-117.9145036", "D-backs" => "33.4483771,-112.0740373", "Braves" => "33.7489954,-84.3879824", "Orioles" => "39.2903848,-76.6121893", "Red Sox" => "42.3600825,-71.0588801", "Cubs" => "41.8781136,-87.6297982", "White Sox" => "41.8781136,-87.6297982", "Reds" => "39.1031182,-84.5120196", "Indians" => "41.49932,-81.6943605", "Rockies" => "39.7392358,-104.990251", "Tigers" => "42.331427,-83.0457538", "Marlins" => "25.7616798,-80.1917902", "Astros" => "29.7604267,-95.3698028", "Royals" => "39.0997265,-94.5785667", "Dodgers" => "34.0522342,-118.2436849", "Brewers" => "43.0389025,-87.9064736", "Twins" => "44.977753,-93.2650108", "Mets" => "40.75,-73.86666699999999", "Yankees" => "40.8447819,-73.8648268", "Athletics" => "37.8043637,-122.2711137", "Phillies" => "39.9525839,-75.1652215", "Pirates" => "40.44062479999999,-79.9958864", "Padres" => "32.715738,-117.1610838", "Giants" => "37.7749295,-122.4194155", "Mariners" => "47.6062095,-122.3320708", "Cardinals" => "38.6270025,-90.19940419999999", "Rays" => "27.773056,-82.64", "Rangers" => "32.735687,-97.10806559999999", "Blue Jays" => "43.653226,-79.3831843", "Nationals" => "38.9071923,-77.03687069999999");

$games = $cache->get('games');
$game_times = $cache->get('game_times');

$Forecast = new Forecast('a421d09c3fefb30dba1be54479c2aa50');

$weather_forecast = array();

foreach($games as $game) {
  foreach($game_times as $time => $teams) {
    if(in_array($game['home_team'], $teams['teams'])) {
      $time = strtotime(date("m/d/Y", time()) . $time);
      $latlng = explode(',', $cities[$game['home_team']]);
      $forecast = $Forecast->get($latlng[0], $latlng[1], $time);
      $weather_forecast[$game['home_team']] = $forecast['currently'];
    }
  }
  
}

var_dump($weather_forecast);

$cache->delete('weather_forecast');
$cache->set('weather_forecast', $weather_forecast, 0, 0);
  
?>