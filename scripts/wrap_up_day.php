<?php
require_once dirname(__FILE__) . '/../public_html/inc/global.php';

$cache = new Cache();



$cache->delete('teams');
$cache->delete('scores');
$cache->delete('games');
?>