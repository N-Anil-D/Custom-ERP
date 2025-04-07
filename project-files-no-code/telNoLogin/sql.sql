ALTER TABLE `users` ADD `tel_no` VARCHAR(100) NULL DEFAULT NULL AFTER `email`;
ALTER TABLE `rdglobal_portalapp`.`users` ADD UNIQUE `users_tel_no_unique` (`tel_no`);
ALTER TABLE `users` DROP INDEX `users_email_unique`;
ALTER TABLE `users` CHANGE `email` `email` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL;

