<?php
/********************
  Class for creating Left Nav
*********************/
class Nav {

	public function getCategories($user = null)
	{
		$cat_list = array();
		$sql = SQL::get()
			->select("category_id, parent_id, active, name, url, nav")
			->from("categories")
			->where("parent_id = '0'")
			->where("active = '1'")
			->where("sales_only = '0'")
			->orderBy("sort_order");
		if(false == is_a($user, 'Sales_Rep')) {
			$sql->where("sales_only = '0'");
		}
		$query = db_query($sql);

		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$cat_list[] = $rec;
		}
		return $cat_list;
	}

	public function getCategoryID($name)
	{
    	$sql = SQL::get()->select("category_id")->from("categories")->where("parent_id = '0'")->where("active = '1'")->where("name = '".$name."'");
		$query = db_query($sql);
        $rec = $query->fetch_assoc();
		return $rec['category_id'];
	}

	public function getNavCategories($user = null)
	{
		$CATS = $this->getCategories($user);

		$html .= "<div class='nav-search-left'>";
        
        foreach($CATS as $C)
		{
			$html .= "<div class='nav-category'><img src='/images/Arrow.jpg'><a class='nav-category-link' href='/category/".$C['url']."'>&nbsp;&nbsp;".$C['name']."</a><br />";
            $html .= "<div id='cat_".$C['category_id']."' class='nav-cat-ajax' style='display:none'>";
	        $html .= "</div>";
			$html .= "</div>";
        }
		$html .= "</div>";

		echo $html;
	}

	public function getNavSubCategories($ID="")
	{
		$sub_cat_list = array();
		$sql = SQL::get()->select("category_id, name, url")->from("categories")->where("parent_id = '".$ID."'")->where("active = '1'")->orderBy("sort_order");
		$query = db_query($sql);
        while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$sub_cat_list[] = $rec;
		}

		foreach ($sub_cat_list as $sub)
		{
			$html .= "<div class='nav-sub-header'><span class='nav-sub-header-text' onclick=\"javascript:getNavSubCategoriesSub('".$sub['category_id']."', '".$sub['url']."', '".$ID."');\">".$sub['name']."</span></div>";
			$html .= "<div id='sub_cat_".$ID."' class='nav-cat-ajax' style='display:none'>";
	    	$html .= "</div>";
		}

		echo $html;

	}

	public function getNavSubCategoriesSub($ID, $url)
	{
    	$sub_cat_list = array();
		$sql = SQL::get()->select("name, url")->from("categories")->where("parent_id = '".$ID."'")->where("active = '1'")->orderBy("sort_order");
		$query = db_query($sql);
        while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$sub_cat_list[] = $rec;
		}

		if(!empty($sub_cat_list))
		{
			foreach ($sub_cat_list as $sub)
			{
				$html .= "<div class='nav-facet-value'><span class='nav-facet-value-text'><a class='nav-facet-value-text' href='/category/".$sub['url']."'>".$sub['name']."</a></span></div>";
			}
		} else {
			$html = $url;
		}

		echo $html;
	}

	public function checkCats($url)
	{
    	$sql = SQL::get()->select("category_id, parent_id")->from("categories")->where("url = '".$url."'")->where("active = '1'");
		$query = db_query($sql);
        $rec = $query->fetch_assoc();
		echo json_encode($rec);
	}

}
?>