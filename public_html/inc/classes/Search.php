<?php
class Search {
	public static function searchClubs($lat, $lng, $radius) {
		// Start XML file, create parent node
		$dom = new DOMDocument("1.0");
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		
		// Search the rows in the markers table
		$query = "SELECT club_id, address_1, address_2, city, state, zipcode, name, phone, lat, lng, ( 3959 * acos( cos( radians('".$lat."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$lng."') ) + sin( radians('".$lat."') ) * sin( radians( lat ) ) ) ) AS distance FROM clubs HAVING distance < '".$radius."' ORDER BY distance LIMIT 0 , 20";
		$result = db_arr($query);
		
		header("Content-type: text/xml");
		
		// Iterate through the rows, adding XML nodes for each
		foreach($result as $row) {
		  $node = $dom->createElement("marker");
		  $newnode = $parnode->appendChild($node);
		  $newnode->setAttribute("name", $row['name']);
		  $newnode->setAttribute("address", $row['address_1']." ".$row['address_2']."<br />".$row['city'].", ".$row['state']." ".$row['zipcode']);
		  $newnode->setAttribute("lat", $row['lat']);
		  $newnode->setAttribute("lng", $row['lng']);
		  $newnode->setAttribute("distance", $row['distance']);
		}
		
		echo $dom->saveXML();
	}
	
	public static function searchClubsJSON($lat, $lng, $radius) {
		$clubs = array();
		// Search the rows in the markers table
		$query = "SELECT club_id, address_1, address_2, city, state, zipcode, name, phone, lat, lng, ( 3959 * acos( cos( radians('".$lat."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$lng."') ) + sin( radians('".$lat."') ) * sin( radians( lat ) ) ) ) AS distance FROM clubs HAVING distance < '".$radius."' ORDER BY distance LIMIT 0 , 20";
		$result = db_arr($query);
		
		foreach($result as $r) {
			$r['name'] = htmlspecialchars_decode($r['name']);
			$CLUB = new Clubs($r['club_id']);
			
			$TIMES = $CLUB->getShowtimes();
			
			$r['active'] = false;
			foreach($TIMES as $k => $T) {
				$CC = new Customer_Clubs($T->customer_clubs_id);
				
				if($CC->ID != "") {
					$r['hasShows'] = true;
					if($CC->active == "1") {
						$r['active'] = true;
					}
				}
				
			}
			
			$clubs[] = $r;
		}
		
		echo json_encode($clubs);
		exit;
	}
	
	public static function searchClubsAdmin($club_name, $confirmed) {
		$clubs = array();
		
		if($confirmed == "1") {
			$verified_confirm = "OR confirmed = '1'";
		}
		
		$sql = "SELECT club_id, MATCH (name, address_1, address_2, city, state) AGAINST ('".addslashes($club_name)."') AS relevance FROM clubs WHERE MATCH(name, address_1, address_2, city, state) AGAINST('".addslashes($club_name)."') ". $verified_confirm;
		$rs = db_arr($sql);
		
		if(!empty($rs)) {
			foreach($rs as $r) {
				$clubs[] = new Clubs($r['club_id']);
			}
		}
		
		return $clubs;
		
	}
	
	public static function findEverything($query) {
		$search_results = array();
		
		$sql = "SELECT club_id, MATCH (name, address_1, address_2, city, state) AGAINST ('".$query."') AS relevance FROM clubs WHERE MATCH(name, address_1, address_2, city, state) AGAINST('".$query."')";
		$rs = db_arr($sql);
		
		$sql2 = "SELECT customer_id, username, MATCH (name, username) AGAINST ('".$query."') AS relevance FROM customers WHERE user_type = 'dj' AND MATCH(name, username) AGAINST('".$query."')";
		$rs2 = db_arr($sql2);
		
		if(!empty($rs) && !empty($rs2)) {
			$merge = array_merge($rs, $rs2);
			sort($merge, SORT_NUMERIC);
			
			foreach($merge as $r) {
				if(is_null($r['username'])) {
					$search_results[] = new Clubs($r['club_id']);
				} else {
					$search_results[] = new Customer($r['customer_id']);
				}
			}
		} elseif(!empty($rs)) {
			foreach($rs as $r) {
				$search_results[] = new Clubs($r['club_id']);
			}
		} elseif(!empty($rs2)) {
			foreach($rs2 as $r) {
				$search_results[] = new Customer($r['customer_id']);
			}
		}
		
		return $search_results;
	}
	
	public static function searchDJSJSON($q) {
		$djs = array();
		
		$sql = "SELECT customer_id, username, name, MATCH (name, username) AGAINST ('".$q."') AS relevance FROM customers WHERE user_type = 'dj' AND MATCH(name, username) AGAINST('".$q."')";
		$rs = db_arr($sql);
		
		if(empty($rs)) {
			$sql = "SELECT customer_id, username, name FROM customers WHERE user_type = 'dj' AND (username = '".$q."' OR name = '".$q."')";
			$rs = db_arr($sql);
		}
		
		if(!empty($rs)) {
			foreach($rs as $r) {
				$djs[] = array("dj_id" => $r['customer_id'], "username" => $r['username'], "name" => $r['name']);
			}
		}
		
		return $djs;
	}
}
?>