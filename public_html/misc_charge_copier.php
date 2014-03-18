<?php
require_once dirname(__FILE__) . '/inc/global.php';

$sql = SQL::get()
	->select('CommentCode, Comment')
	->from("`" . DB_IMPORT . "`.`TblSoStdCom`");
$query = db_query($sql);

while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
	$MC = new Misc_Charge($rec['CommentCode'], 'comment_code');
	$MC->comment_code = $rec['CommentCode'];
	$MC->description = $rec['Comment'];
	$MC->write();
}
?>
