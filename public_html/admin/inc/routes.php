<?php
//routes are defined in here.
Router::aliasController('batch', 'Batch_Edit_Controller');
Router::aliasAction('Category_Controller', 'new', 'newCategory');
Router::aliasController('exporter', 'Catalog_Export_Controller');
Router::aliasController('categories', 'Category_Controller');
Router::aliasController('pages', 'Page_Controller');
Router::aliasController('products', 'Product_Controller');
Router::aliasAction('Page_Controller', 'new', 'newPage');
Router::aliasController('bover', 'Box_Override_Controller');
Router::aliasAction('Box_Override_Controller', 'new', 'newBox');
Router::aliasController('tab', 'Product_Tab_Controller');
Router::aliasController('gtab', 'Global_Tab_Controller');
Router::aliasController('fbox', 'Freight_Box_Controller');
Router::aliasController('fover', 'Freight_Override_Controller');
Router::aliasController('excludes', 'Freight_Override_Excludes_Controller');
Router::aliasController('salesrep', 'Sales_Rep_Controller');
Router::aliasController('ubd', 'UBD_Controller');
Router::aliasAction('Freight_Box_Controller', 'new', 'newBox');
Router::aliasAction('Freight_Override_Controller', 'new', 'newOverride');
Router::aliasAction('Freight_Override_Excludes_Controller', 'new', 'newExcludeOverride');
Router::aliasController('terms', 'Payment_Terms_Controller');
Router::aliasAction('Product_Controller', 'new', 'newProduct');
Router::aliasAction('Product_Tab_Controller', 'new', 'newTab');
Router::aliasController('custom_shipping', 'Custom_Shipping_Controller');
Router::aliasController('custom_handling', 'Custom_Shipping_Fee_Controller');
Router::aliasController('shipping_discount', 'Custom_Shipping_Discount_Controller');
Router::aliasController('sales_rep_goals', 'Sales_Rep_Goals_Controller');
Router::add('/ubd/', array('controller' => 'UBD_Controller', 'action' => 'admin_index', 'id' => null));
Router::aliasController('search_export', 'Search_Export_Controller');
Router::aliasController('misc_charges', 'Misc_Charge_Controller');
?>
