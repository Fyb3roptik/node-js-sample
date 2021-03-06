CREATE TABLE `product_meta_tags` LIKE `meta_tags`;
ALTER TABLE `product_meta_tags` CHANGE `meta_tag_id` `meta_tag_id` INT( 11 ) NOT NULL;
ALTER TABLE `product_meta_tags` DROP PRIMARY KEY;
ALTER TABLE `product_meta_tags` ADD `product_meta_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
ALTER TABLE `product_meta_tags` DROP INDEX product_id_2;
ALTER TABLE `product_meta_tags` DROP `name`;
ALTER TABLE `product_meta_tags` DROP `content`;
INSERT INTO `product_meta_tags` (`meta_tag_id`, `product_id`) SELECT `meta_tag_id`, `product_id` FROM `meta_tags`;
ALTER TABLE `meta_tags` DROP INDEX `product_id_2`;
ALTER TABLE `meta_tags` DROP INDEX `product_id`;
ALTER TABLE `meta_tags` DROP `product_id`;
