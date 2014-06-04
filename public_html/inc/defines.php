<?php
//define things in here, I dare you.

/**********************************************************************
 * The convention here is to use LOC_* for constants that are locations
 * for redirecting to.
 *
 * NOTE: Don't forget to update the .htaccess file!
 **********************************************************************/
define('LOC_ACCOUNT_HOME', '/myaccount/', false);
define('LOC_ACCOUNT_EDIT', '/myaccount/edit/', false);
define('LOC_ADDRESS_BOOK', '/myaccount/addressbook/', false);
define('LOC_ADDRESS_BOOK_NEW', '/myaccount/addressbook/new/', false);
define('LOC_ALL_PRODUCTS', '/category/', false);
define('LOC_CART', '/cart.php', false);
define('LOC_CHECKOUT', LOC_CART, false);
define('LOC_CHECKOUT_SUCCESS', '/checkout_success/', false);
define('LOC_CHECKOUT_SUCCESS_QUOTE', '/quote_submitted/', false);
define('LOC_COMPARE_PRODUCTS', '/compare/', false);
define('LOC_HOME', '/', false);
define('LOC_LOGIN', '/login/', false);
define('LOC_LOGOUT', '/logout/', false);
define('LOC_ORDER_HISTORY', '/report/', false);
define('LOC_PRINT_ORDER', '/print_order.php', false);
define('LOC_RECOVER_PASSWORD', '/recover_password/', false);
define('LOC_RESET_PASSWORD', '/reset_password/', false);
define('LOC_SALES', '/sales/', false);
define('LOC_SALES_LOGIN', '/sales_login.php', false);
define('LOC_SALES_LOGIN_RESET_PASSWORD', '/sales_login.php?action=recover_password', false);
define('LOC_WISHLIST', '/myclosets/', false);
define('LOC_FAQ', 'faq.php', false);

define('LOC_CUST_FROM', current_page_url(), false);

define('MIN_PASSWORD_LENGTH', 6, false); //minimum password length

define('ORDER_STATUS_PENDING', 0, false);
define('ORDER_STATUS_QUOTE_PENDING', 1, false);

define('PASSWORD_TOKEN_EXPIRATION', (24 * 60 * 60), false); //number in seconds from "now" to expire a password token

define('VIEW_404', 'error_404.php', false);

define('VIEW_HANDLER', 'inc/view_handler.php', false);

define('DEFAULT_PRODUCT_IMAGE', 'images/no-photo.jpg',false);
?>