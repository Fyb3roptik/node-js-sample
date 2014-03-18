<?php
/**
 * This class very rudimentarily paginates an SQL query.
 */
class Page_Killer {
	private $_sql;
	private $_query;
	private $_current_page;
	private $_results_per_page;
	private $_total_rows;
	private $_total_pages;

	public function __construct($sql, $results_per_page = 10, $current_page = 1) {
		$this->_sql = $sql;
		$this->_current_page = intval($current_page) - 1;
		$this->_results_per_page = intval($results_per_page);
	}

	/**
	 * Returns the count of all records returned by the query.
	 */
	public function getTotalRows() {
		return intval($this->_total_rows);
	}

	/**
	 * Queries the database and returns the right result set for the given
	 * "items per page" and "current page" values.
	 */
	public function query() {
		$result_set = array();
		$results = db_query($this->_sql);

		if($results->num_rows > 0) {
			$this->_total_rows = $results->num_rows;
			$this->_total_pages = ceil($this->_total_rows / $this->_results_per_page);

			if($this->_current_page > $this->_total_pages) {
				$this->_current_page = $this->_total_pages;
			}

			$offset = $this->_results_per_page * $this->_current_page;
			$results->data_seek($offset);
			while($results->num_rows > 0 && $rec = $results->fetch_assoc()) {
				if(count($result_set) < $this->_results_per_page) {
					$result_set[] = $rec;
				} else {
					break;
				}
			}
		}
		return $result_set;
	}

	/**
	 * Returns the paginator links.
	 */
	public function getLinks($attributes = null, $total="") {
		$attribute_string = null;
		if(true == is_array($attributes) && false == is_null($attributes)) {
			foreach($attributes as $key => $value) {
				$value = trim($value);
				if(false == empty($value)) {
					$attribute_string .= '&amp;' . $key . '=' . $value;
				}
			}
		}
        if($total != "")
		{
			$this->_total_rows = $total;
			$this->_total_pages = $this->_total_pages = ceil($this->_total_rows / $this->_results_per_page);
		}
		if($this->_total_pages <= 10) {
			$link_list = array();
			for($i=1;$i<=$this->_total_pages;$i++) {
				if($i != ($this->_current_page+1)) {
					$link_list[] = '<span class="pNumber"><a href="?page=' . $i . $attribute_string . '">' . $i . '</a></span>';
				} else {
					if($this->_total_pages != 1) {
						$link_list[] = '<span class="pNumber_Selected">' . $i . '</span>';
					}
				}
			}
			$links .= implode(' ', $link_list);
		} else {
			//TODO: make this suck a lot less.
			if(1 != $this->_current_page+1) {
				$links .= '<a href="?page=' . $this->_current_page . $attribute_string . '"><img src="/images/prev_arrow.png"> previous&nbsp;&nbsp;</a> ';
			}
			$page_links = array();
			$page_links[] = $this->_current_page+1;
			for($i = $this->_current_page; $i > 0; $i--) {
				$page_links[] = $i;
				if(count($page_links) >= 4) {
					break;
				}
			}

			for($i = $this->_current_page+2; $i <= $this->_total_pages; $i++) {
				$page_links[] = $i;
				if(count($page_links) >= 7) {
					break;
				}
			}

			sort($page_links);
			foreach($page_links as $i => $page_number) {
				if(0 == $i && $page_number != 1) {
					$links .= ' ...';
				}
				if($page_number != $this->_current_page+1) {
					$links .= ' <span class="pNumber"><a href="?page=' . $page_number . $attribute_string . '">' . $page_number . '</a></span> ';
				} else {
					if($this->_total_pages != 1) {
						$links .= ' <span class="pNumber_Selected">' . $page_number . '</span> ';
					}
				}
				if($i == (count($page_links) - 1) && $page_number != $this->_total_pages) {
					$links .= '... ';
				}
			}

			if($this->_current_page+1 != $this->_total_pages) {
				$links .= ' <a href="?page=' . $this->_total_pages . $attribute_string . '">'.$this->_total_pages.'</a>';
				$links .= ' <a href="?page=' . ($this->_current_page + 2) . $attribute_string . '">&nbsp;&nbsp;next <img src="/images/next_arrow.png"></a>';
			}
		}
		return $links;
	}
}
?>