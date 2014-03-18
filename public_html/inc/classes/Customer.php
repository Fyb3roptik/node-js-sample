<?php
require_once 'User.php';

/**
 * Class that handles everything about a customer.
 */
class Customer extends User {
	protected $_table = 'customers';
	protected $_table_id = 'customer_id';

	protected $_default_vals = array(
						'account_type' => 'Personal',
						'name' => 'Guest');

	protected $_set_hooks = array('default_shipping' => '_setDefaultAddress', 'default_billing' => '_setDefaultAddress');

	protected $_user_type = User::TYPE_CUSTOMER;

	public function getShippingOptions() {
		$options = array();
		$CSO = new Customer_Shipping_Option();
		if(true == $this->exists()) {
			$options = $CSO->find('customer_id', $this->ID);
		}
		return $options;
	}

	public function getHandlingFee($order_total)
	{
		$options = $this->getShippingOptions();
		if(!empty($options))
		{
			$fee = Custom_Fee_Finder::findFee($order_total);
		} else {
			$fee = 0;
		}

		return $fee;
	}

	protected function _setDefaultAddress($address_id) {
		$address_id = abs(intval($address_id));
		$CA = new Customer_Address($address_id);
		if($CA->customer_id != $this->ID) {
			$address_id = null;
		}
		return $address_id;
	}

	public function getCreditCards() {
		$cc_list = array();
		if(true == $this->exists()) {
			$CC = new Credit_Card();
			$cc_list = $CC->find('customer_id', $this->ID);
		}
		return $cc_list;
	}

	public function getAddressBook($datadump = false) {
		$address_book = array();
		if(true == $this->exists()) {
			$A = new Customer_Address();
			$address_book = $A->find('customer_id', $this->_ID);
		}

		if(true == $datadump) {
			$address_data = array();
			foreach($address_book as $i => $book) {
				$address_data[] = $book->dataDump();
			}
			$address_book = $address_data;
		}

		return $address_book;
	}

	public function getSalesRep() {
		return Object_Factory::OF()->newObject('Sales_Rep', $this->sales_rep);
	}

	public function getSessionToken() {
		return parent::getToken();
	}

	/**
	 * Returns a customer's wishlists.
	 */
	public function getWishlists() {
		$W = new Wishlist();
		return $W->find('customer_id', $this->_ID, 'name');
	}
	
	public function getStageName() {
		$stage_name = $this->name;
		
		if($this->stage_name != "") {
			$stage_name = $this->stage_name;
		}
		
		return $stage_name;
	}

	/**
	 * Return the orders placed by this Customer.
	 */
	public function getOrders($limit = 0) {
		$order_list = array();

		$sql = SQL::get()
			->select('order_id')
			->from('orders')
			->where("customer_id = '@customer_id'")
			->where("type = '@order_type'")
			->bind('customer_id', $this->ID)
			->bind('order_type', Order::TYPE_ORDER)
			->orderBy('date_purchased', SQL_Select::DESC);
		if(abs(intval($limit)) > 0) {
			$sql->limit(abs(intval($limit)));
		}
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$order_list[] = new Order($rec['order_id']);
		}
		return $order_list;
	}
	
	public function searchPlaylist($q) {
		$search_results = array();
		
		$playlist = $this->ID.'_playlist';
		if($this->has_playlist == 0) {
			$playlist = 'default_playlist';
		}
		$default = "default";
		if($this->has_playlist == 1) {
			$default = $this->ID;
		}
		$sql = "SELECT DISTINCT *
			FROM (SELECT ".$default."_playlist_id, title, artist, MATCH( artist, title ) AGAINST('".$q."') AS relevance FROM ".$playlist." WHERE MATCH( artist, title ) AGAINST('".$q."')) AS T1
			GROUP BY T1.title, T1.artist
			ORDER BY T1.relevance DESC
			LIMIT 200";
		$rs = db_arr($sql);
		foreach($rs as $r) {
			if($this->has_playlist == 1) {
				$search_results[] = new Playlist($this->ID."_playlist", $this->ID."_playlist_id", $r[$this->ID."_playlist_id"]);
			} else {
				$search_results[] = new Playlist("default_playlist", "default_playlist_id", $r["default_playlist_id"]);
			}
		}
		return $search_results;
	}
	
	public function getRequests() {
		$requests = array();
		
		$sql = SQL::get()
			->select('customer_request_id')
			->from('customer_requests')
			->where("dj_id = '".$this->ID."'")
			->where("active = '1'")
			->orderBy('request_time', 'ASC');
			
		$rs = db_arr($sql);
		
		foreach($rs as $r) {
			$requests[] = new Customer_Requests($r['customer_request_id']);
		}
		
		return $requests;
	}
	
	public function getLatestRequests($latest_id) {
		$requests = array();
		
		$sql = SQL::get()
			->select('customer_request_id, song_id, customer_id, request_time')
			->from('customer_requests')
			->where("dj_id = '".$this->ID."'")
			->where("customer_request_id > '".$latest_id."'")
			->where("active = '1'")
			->orderBy('request_time', 'ASC');
			
		$rs = db_arr($sql);
		
		$requests[0]['empty'] = true;
		
		if(!empty($rs)) {
			$requests[0]['empty'] = false;
			foreach($rs as $k => $r) {
				
				if($this->has_playlist == 0) {
					$PLAYLIST = new Playlist("default_playlist", "default_playlist_id", $r['song_id']);
				} else {
					$PLAYLIST = new Playlist($this->ID."_playlist", $this->ID."_playlist_id", $r['song_id']);
				}
				
				$requests[$k]['artist'] = $PLAYLIST->artist;
				$requests[$k]['title'] = $PLAYLIST->title;
				
				$USER = new Customer($r['customer_id']);
				$requests[$k]['customer'] = $USER->getStageName();
				$requests[$k]['request_time'] = date("F d, Y g:i A", $r['request_time']);
				$requests[$k]['id'] = $r['customer_request_id'];
				$requests[$k]['playlist_id'] = $PLAYLIST->ID;
				$requests[$k]['disk_id'] = $PLAYLIST->disk_id;
			}
		}		
		return json_encode($requests);
	}
	
	public function getDjs() {
		$DJS = array();
		
		$sql = SQL::get()
			->select('dj_id')
			->from('customer_dj')
			->where("customer_id = '".$this->ID."'");
			
		$rs = db_arr($sql);
		
		foreach($rs as $r) {
			$DJS[] = new Customer($r['dj_id']);
		}
		
		return $DJS;
	}
	
	public function getDjsJSON() {
		$DJS = array();
		$return = array();
		
		$sql = SQL::get()
			->select('dj_id')
			->from('customer_dj')
			->where("customer_id = '".$this->ID."'");
	
		$rs = db_arr($sql);
		
		foreach($rs as $k => $r) {
			$DJS = new Customer($r['dj_id']);
			
			$return[$k]['username'] = $DJS->username;
			$return[$k]['name'] = $DJS->name;
		}
		
		return $return;
		exit;
	}
	
	public function getClubsJSON() {
		$CLUBS = array();
		$return = array();
		
		$sql = SQL::get()
			->select('club_id')
			->from('customer_clubs_fav')
			->where("customer_id = '".$this->ID."'");
	
		$rs = db_arr($sql);
		
		foreach($rs as $k => $r) {
			$CLUBS = new Clubs($r['club_id']);
			
			$return[$k]['club_id'] = $CLUBS->ID;
			$return[$k]['name'] = htmlspecialchars_decode($CLUBS->name);
		}
		
		return $return;
		exit;
	}
	
	public function requestSong(Customer $DJ, $song_id) {
		$CR = new Customer_Requests();
		
		$CR->dj_id = $DJ->ID;
		$CR->customer_id = $this->ID;
		$CR->song_id = $song_id;
		$CR->active = '1';
		$CR->request_time = time();
		
		$CR->default_playlist = 1;
		
		if($DJ->has_playlist == 1) {
			$CR->default_playlist = 0;
		}
		
		$CR->write();
		
		return "Success";
	}
	
	public function getClubs() {
		$clubs = array();
		
		$sql = SQL::get()
			->select('customer_club_id')
			->from('customer_clubs')
			->where("customer_id = '".$this->ID."'");

		$rs = db_arr($sql);
		
		foreach($rs as $r) {
			$clubs[] = new Customer_Clubs($r['customer_club_id']);
		}
		return $clubs;
	}
	
	public function getClubTimes($customer_clubs_id) {
		$times = array();

		$sql = SQL::get()
			->select('customer_clubs_times_id')
			->from('customer_clubs_times')
			->where("customer_clubs_id = '".$customer_clubs_id."'");
		$rs = db_arr($sql);
		
		foreach($rs as $r) {
			$times[] = new Customer_Clubs_Times($r['customer_clubs_times_id']);
		}
		return $times;
	}

	/**
	 * Set the account type.
	 */
	public function setAccountType($account_type) {
		$account_type = trim($account_type);
		$accepted_values = array('Personal', 'Business');
		if(true == in_array($account_type, $accepted_values)) {
			parent::__call('setAccountType', $account_type);
		}
	}

	/**
	 * Returns a Cart object for this User.
	 */
	public function getCart() {
		$C = new Cart($this->_ID);
		return $C;
	}
	
	public function hasPermission($action_code) {
		$allowed = false;
		$PP = new Plans_Permission();
		$permission_list = $PP->find('plan_id', $this->plan_id);
		foreach($permission_list as $P) {
			if($action_code == $P->code) {
				$allowed = (bool)$P->allowed;
				break;
			}
		}
		return $allowed;
	}
}
?>
