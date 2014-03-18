<?php
class Geocoder {
	private $_address;
	private $_lat;
	private $_lng;
	
	public function __construct($address, $city, $state, $zipcode, $country) {
		$this->_address = urlencode($address.", ".$city.", ".$state.", ".$zipcode.", ".$country);
	}
	
	public function geocode() {
		$parsed = array();
		
		$url = curl_init("http://maps.googleapis.com/maps/api/geocode/json?address=".$this->_address."&sensor=false");
        curl_setopt($url, CURLOPT_HEADER, 0);
        curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($url);      
        curl_close($url);
        
        $parsed = $this->_parseOutput($output);
        return $parsed;
	}
	
	private function _parseOutput($output) {
		$results = array();
		
		if(!empty($output)) {
			$decoded = json_decode($output);
			
			$results['lat'] = $decoded->results[0]->geometry->location->lat;
			$results['lng'] = $decoded->results[0]->geometry->location->lng;
		}
		
		return $results;
	}
}
?>