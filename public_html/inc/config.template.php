<?php
//let's configure stuff.
define('DB_HOST', '__DB_HOST__', false);
define('DB_USER', '__DB_USER__', false);
define('DB_PASS', '__DB_PASS__', false);
define('DB_NAME', '__DB_NAME__', false);

//path to the root of the web app
define('DIR_ROOT', '__DIR_ROOT__', false);

//where the change scripts used to create the database are located
define('DIR_SCHEMA', dirname(DIR_ROOT) . '/data/', false);

//Sets flag for forcing https
define('FORCE_SSL', '__FORCE_SSL__', true);

//where can people find this particular install of the site?
//DO NOT add a trailing slash (http://siing.co instead of http://siing.co/)
define('SITE_URL', '__SITE_URL__', true);
define('SITE_SECURE_URL', '__SITE_SECURE_URL__', true);

define('ENCRYPTION_KEY', '__ENCRYPTION_KEY__', false);

define('MAINTANENCE_MODE', '__MAINTANENCE_MODE__', false);

define('GOOGLE_ANALYTICS_CODE', '__GOOGLE_ANALYTICS_CODE__', true);

define('BUILD', '__BUILD__', false);
?>
