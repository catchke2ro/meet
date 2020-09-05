ALTER TABLE `yii2tst`.`meet__t__questions`
	ADD COLUMN `is_active` TINYINT(4) NOT NULL DEFAULT 1 AFTER `order`;

ALTER TABLE `yii2tst`.`meet__t__commitments`
	ADD COLUMN `is_active` TINYINT(4) NOT NULL DEFAULT 1 AFTER `order`;