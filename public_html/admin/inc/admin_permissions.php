<?php
require_once dirname(__FILE__) . '/../../inc/classes/Admin_Permission_Register.php';

//Export permissions
//Admin_Permission_Register::addPermission('Catalog_Export_Controller', 'index', 'export_products', 'Admin can export products.');

//Import permissions
//Admin_Permission_Register::addPermission('Import_Controller', 'index', 'import_products', 'Admin can import products from a legacy csv.');

//Coupon permissions
//Admin_Permission_Register::addPermission('Coupon_Controller', 'index', 'edit_coupons', 'Admin can manage coupons and coupon codes.');

//Page and content permissions
//Admin_Permission_Register::addPermission('Page_Controller', 'index', 'page_manage', 'Admin can manage static pages.');

//Admin_Permission_Register::addPermission('Content_Controller', 'editFooter', 'edit_footer', 'Admin can manage the global footer.');

//Global config permissions
//Admin_Permission_Register::addPermission('Sales_Tax_Controller', 'index', 'edit_tax', 'Admin can edit sales tax rates.');
//Admin_Permission_Register::addPermission('Shipping_Controller', 'index', 'manage_shipping', 'Admin can manage global shipping options.');
//Admin_Permission_Register::addPermission('Widget_Controller', 'index', 'manage_widgets', 'Admin can manage widgets (left column content).');
//Admin_Permission_Register::addPermission('Payment_Terms_Controller', 'index', 'payment_terms', 'Admin can manage payment terms.');
//Admin_Permission_Register::addPermission('Fudge_Controller', 'index', 'global_product_overhead', 'Admin can manage global product overhead.');

//Player Permissions
Admin_Permission_Register::addPermission('Player_Controller', 'index', 'player_manage', 'Admin can manage players.');

//Match Permissions
Admin_Permission_Register::addPermission('Match_Controller', 'index', 'match_manage', 'Admin can manage matches.');

//User permissions
Admin_Permission_Register::addPermission('Admin_Controller', 'index', 'admin_manage', 'Admin can manage administrative user accounts.');
Admin_Permission_Register::addPermission('Admin_Controller', 'dropAdmin', 'drop_admin', 'Admin can delete other administrative user accounts.');
//Admin_Permission_Register::addPermission('Sales_Rep_Controller', 'index', 'sales_rep_manage', 'Admin can manage Sales Rep user accounts.');
Admin_Permission_Register::addPermission('Customer_Controller', 'index', 'customer_manage', 'Admin can manage customer accounts.');
?>
