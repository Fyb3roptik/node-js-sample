ALTER TABLE `order_change_history`
	ADD `cc_trans_id` varchar(4) NULL,
	ADD `cc_auth_code` varchar(16) NULL;
