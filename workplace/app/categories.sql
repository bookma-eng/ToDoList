//todos
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


//categories
CREATE TABLE `categories` (
`id` int auto_increment NOT NULL,
`category_name` varchar(255) NOT NULL,
`created_at` datetime NOT NULL COMMENT '',
PRIMARY KEY(`id`)
)CHARSET=utf8 COMMENT='categories';



INSERT INTO `categories` (`category_name`, `created_at`)
VALUES (`仕事`,now());

ALTER TABLE `todos` ADD INDEX `fk_category_id`(`category_id`);
ALTER TABLE `todos` ADD FOREIGN KEY `category_id` REFERENCES `categories`('id');
ALTER TABLE `todos` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;



