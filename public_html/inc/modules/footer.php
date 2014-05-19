<!-- Footer -->
<footer>
    &copy; <?php echo date('Y', time()); ?> Beast Fantasy Sports Inc | All Rights Reserved
</footer>
<!-- /Footer -->
<?php
FB::group("Performance");
FB::log(number_format((memory_get_peak_usage() / 1024 / 1024), 2) . 'MB', "Peak Memory Usage");
FB::log((microtime(true) - $START_TIME), "Script Runtime");
FB::groupEnd();
?>
