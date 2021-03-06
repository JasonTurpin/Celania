/* Users */
CREATE TABLE IF NOT EXISTS `Users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwHash` varchar(255) NOT NULL,
  `timeZone` varchar(100) NOT NULL,
  `isActive` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` TIMESTAMP,
  `updated_at` TIMESTAMP,
  UNIQUE (`username`),
  UNIQUE (`email`),
  PRIMARY KEY(`user_id`)
);

/*  UsersRoles */
CREATE TABLE IF NOT EXISTS `UsersRoles` (
  `role_id` smallint(5),
  `user_id` int(11),
  `created_at` TIMESTAMP,
  `updated_at` TIMESTAMP,
  PRIMARY KEY(`user_id`, `role_id`)
);

/* Roles */
CREATE TABLE IF NOT EXISTS `Roles` (
  `role_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `isActive` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP,
  `updated_at` TIMESTAMP,
  PRIMARY KEY(`role_id`)
);