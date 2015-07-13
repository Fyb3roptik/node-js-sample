<?php

class Cache {
    
    private $_cache;
    
    public function __construct() {
        $this->connect();
    }
    
    public function connect() {
        $this->_cache = memcache_connect("beastfranchise.hwjrez.cfg.usw2.cache.amazonaws.com", 11211);
    }
    
    public function add($object, $key, $compression, $expire) {
        $this->_cache->add($object, $key, $compression, $expire);
    }
    
    public function get($key) {
        return $this->_cache->get($key);
    }
    
    public function set($key, $object, $compression, $expire) {
        $this->_cache->set($key, $object, $compression, $expire);
    }
    
    public function delete($key) {
        $this->_cache->delete($key);
    }
    
}
?>
