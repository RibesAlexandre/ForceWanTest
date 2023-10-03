CREATE TABLE `fw_events` (
     `id` int NOT NULL AUTO_INCREMENT,
     `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
     `session` text,
     `status` enum('good','bad') DEFAULT NULL,
     `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
     `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;