CREATE TABLE `admin_permissions` (
	`admin_permission_id` int(11) NOT NULL AUTO_INCREMENT,
	`admin_id` int(11) NOT NULL,
	`code` varchar(64) NOT NULL,
	`allowed` tinyint(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (`admin_permission_id`)
);