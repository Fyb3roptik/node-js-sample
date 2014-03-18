<?php
require_once dirname(__FILE__) . '/../../inc/classes/Plans_Permissions_Register.php';

//User Permissions
Plans_Permissions_Register::addPermission('User', 'Rating_Controller', 'index', 'rating_manage', 'User can rate DJ\'s / Clubs.');

//DJ Permissions
Plans_Permissions_Register::addPermission('DJ', 'Upload_Controller', 'index', 'upload_manage', 'User can upload playlists.');
Plans_Permissions_Register::addPermission('DJ', 'Requests_Controller', 'index', 'requests_manage', 'User can manage incoming song requests.');

?>
