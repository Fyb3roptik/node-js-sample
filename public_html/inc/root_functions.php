<?php
function current_page_url(){
    $page_url   = 'http';
    if((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "on") || (isset($_SERVER['HTTP_CF_VISITOR']) && $_SERVER['HTTP_CF_VISITOR'] != "{\"scheme\":\"https\"}")){
        $page_url .= 's';
    }
    return $page_url.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}
?>