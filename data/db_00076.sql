DROP VIEW IF EXISTS `order_subtotals`;

CREATE VIEW `order_subtotals` AS
	SELECT `order_id`, sum(unit_price * quantity) AS `subtotal`
	FROM `order_line_items`
	WHERE `type` = 'product'
	GROUP BY `order_id`;
