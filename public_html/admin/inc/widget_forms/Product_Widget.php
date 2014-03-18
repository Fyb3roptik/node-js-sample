<?php
$sql = "SELECT product_id, name
	  FROM `products`
	  ORDER by name";
$query = db_query($sql);
$product_options = array();
while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
	$name = trim($rec['name']);
	if(false == empty($name)) {
		$product_options[$rec['product_id']] = $rec['name'];
	}
}
?>
<div>
	Select Product: <?php echo draw_select('config[product_id]', $product_options, $WB->configure('product_id')); ?>
</div>