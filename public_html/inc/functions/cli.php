<?php
/**
 * Function for logging things to STDOUT from cron processes.
 */
function cron_log($message, $target = STDOUT) {
	fwrite($target, '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL);
}
?>
