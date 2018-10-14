ALTER TABLE `survey` ADD `create_time` INT NULL AFTER `description`, ADD `update_time` INT NULL AFTER `create_time`, ADD `delete_time` INT NULL AFTER `update_time`;
ALTER TABLE `question_option` CHANGE `poll` `poll` INT(11) NOT NULL DEFAULT '0' COMMENT '票数';
ALTER TABLE `survey` ADD `status` TINYINT NOT NULL DEFAULT '0' COMMENT '状态：0-未发布，1-已发布' AFTER `description`;
ALTER TABLE `survey` ADD `sheet_count` INT NOT NULL DEFAULT '0' COMMENT '答卷数' AFTER `status`;

ALTER TABLE `answer` DROP `answer`;
CREATE TABLE `survey`.`answer_option` ( `id` INT NOT NULL AUTO_INCREMENT , `answer_id` INT NOT NULL , `option_id` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `survey`.`answer_content` ( `id` INT NOT NULL AUTO_INCREMENT , `answer_id` INT NOT NULL , `content` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `survey` ADD `integral` INT NOT NULL DEFAULT '0' COMMENT '求助分' AFTER `description`;
ALTER TABLE `user` CHANGE `integral` `integral` INT(11) UNSIGNED NULL DEFAULT '0' COMMENT '积分';

ALTER TABLE `question` ADD `count_poll` INT NOT NULL DEFAULT '0' COMMENT '总票数，用于统计' AFTER `sort`;
ALTER TABLE `question` ADD `count_participant` INT NOT NULL DEFAULT '0' COMMENT '参与者人数' AFTER `count_poll`;
CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;