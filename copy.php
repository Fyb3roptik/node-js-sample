<?php
require_once dirname(__FILE__) . '/public_html/inc/global.php';

$sql = "SELECT mlb_id FROM players";
$arr = db_arr($sql);

foreach($arr as $a) {
  copy("http://gdx.mlb.com/images/gameday/mugshots/mlb/".$a['mlb_id'].".jpg", "/var/www/dev.beastfranchise.com/public_html/img/player-image/".$a['mlb_id'].".jpg"); 
}  
?>