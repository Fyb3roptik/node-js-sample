CREATE TABLE `order_cancel_reasons` (
	order_cancel_reason_id int(11) NOT NULL AUTO_INCREMENT,
	reason_code varchar(64) NOT NULL,
	reason_text varchar(128) NOT NULL,
	PRIMARY KEY (`order_cancel_reason_id`)
);

INSERT INTO `order_cancel_reasons` (`reason_code`, `reason_text`)
VALUES
	('99', 'Test Cancel Reason'),
	('1337', 'Soooo leet')
;