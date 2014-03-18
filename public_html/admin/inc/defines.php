<?php
//You can define stuff in here.

define('DIR_ROOT_FUNCTIONS', DIR_ROOT . 'inc/functions/', false);
define('DIR_ROOT_CLASSES', DIR_ROOT . 'inc/classes/', false);

define('LOC_ADMIN', '/admin/', false);
define('LOC_ADMIN_MANAGE', LOC_ADMIN . 'admin/', false);
define('LOC_ATTRIBUTES', LOC_ADMIN . 'attribute/', false);
define('LOC_CATALOG_UPLOAD', LOC_ADMIN . 'catalog_import.php', false);
define('LOC_CATEGORIES', LOC_ADMIN . 'categories/', false);
define('LOC_COUPONS', LOC_ADMIN . 'coupons.php', false);
define('LOC_DEFAULT', '/admin/index.php', false);
define('LOC_EXPORTER', '/admin/exporter/', false);
define('LOC_LOGIN', LOC_ADMIN . 'login.php', false);
define('LOC_MANAGE_WIDGETS', LOC_ADMIN . 'widget', false);
define('LOC_PAGES', LOC_ADMIN . 'pages/', false);
define('LOC_PRODUCTS', LOC_ADMIN . 'products/', false);
define('LOC_RECOVER_PASSWORD', 'login.php?action=recover_password', false);
define('LOC_RESET_PASSWORD', 'login.php?action=reset_password', false);
define('LOC_SALES_REPS', LOC_ADMIN . 'salesrep/', false);

define('MIN_PASSWORD_LENGTH', 6, false); //minimum password length

define('PASSWORD_TOKEN_EXPIRATION', (24 * 60 * 60), false); //number in seconds from "now" to expire a password token

define('VIEW_404', 'error_404.php', false);
define('VIEW_HANDLER', 'inc/view_handler.php', false);

define('DIR_BATCH_IMAGE_UPLOAD', DIR_ROOT . 'admin/image_upload/', false);
?>