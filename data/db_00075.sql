DROP VIEW IF EXISTS `order_totals`;

CREATE VIEW `order_totals` AS
	SELECT `order_id`, sum(unit_price * quantity) AS `total`
	FROM `order_line_items`
	GROUP BY `order_id`;
