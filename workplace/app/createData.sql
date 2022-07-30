--todos
CREATE TABLE `todos` (
`id` int auto_increment NOT NULL,
`title` varchar(255) NOT NULL COMMENT '',
`deteil` text NOT NULL COMMENT '',
`status` tinyint NOT NULL DEFAULT 0 COMMENT '(0: 1: )',
`completed_at` datetime NOT NULL COMMENT '',
`created_at` datetime NOT NULL COMMENT '',
`updated_at` datetime NOT NULL COMMENT '',
`deleted_at` datetime NOT NULL COMMENT '',
PRIMARY KEY(`id`)
)CHARSET=utf8 COMMENT='todos';


--categories
CREATE TABLE `categories` (
`id` int auto_increment NOT NULL,
`category_name` varchar(255) NOT NULL,
`created_at` datetime NOT NULL COMMENT '',
PRIMARY KEY(`id`)
)CHARSET=utf8 COMMENT='categories';



INSERT INTO `categories` (`category_name`, `created_at`)
VALUES (`仕事`,now());

ALTER TABLE `todos` ADD INDEX `fk_user_id`(`user_id`);
ALTER TABLE `todos` ADD FOREIGN KEY `user_id` REFERENCES `users`('id');
ALTER TABLE `todos` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--テストデータ作成()
INSERT INTO `users` (`email`,`password`,`created_at`,`updated_at`) VALUES ('dummy-xe@gmail.com','yyyy',now(),now());

--ステータス更新
UPDATE `todos` SET `status`=0 WHERE `id`=5;

