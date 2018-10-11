ALTER TABLE `survey` ADD `create_time` INT NULL AFTER `description`, ADD `update_time` INT NULL AFTER `create_time`, ADD `delete_time` INT NULL AFTER `update_time`;
ALTER TABLE `question_option` CHANGE `poll` `poll` INT(11) NOT NULL DEFAULT '0' COMMENT '票数';
ALTER TABLE `survey` ADD `status` TINYINT NOT NULL DEFAULT '0' COMMENT '状态：0-未发布，1-已发布' AFTER `description`;
ALTER TABLE `survey` ADD `sheet_count` INT NOT NULL DEFAULT '0' COMMENT '答卷数' AFTER `status`;

ALTER TABLE `answer` DROP `answer`;
CREATE TABLE `survey`.`answer_option` ( `id` INT NOT NULL AUTO_INCREMENT , `answer_id` INT NOT NULL , `option_id` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `survey`.`answer_content` ( `id` INT NOT NULL AUTO_INCREMENT , `answer_id` INT NOT NULL , `content` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;