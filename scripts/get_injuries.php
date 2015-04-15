<?php
require_once dirname(__FILE__) . '/../public_html/inc/global.php';

$response = json_decode(file_get_contents('http://mlb.mlb.com/fantasylookup/json/named.wsfb_news_injury.bam'), true);

$sql = "UPDATE players SET injury_status = '', injury_info = ''";
db_query($sql);

foreach($response['wsfb_news_injury']['queryResults']['row'] as $player) {
  $P = new Player($player['player_id'], "mlb_id");
  $P->injury_status = $player['injury_status'];
  $P->injury_info = $player['injury_update'];
  $P->write();
}
  
?>
