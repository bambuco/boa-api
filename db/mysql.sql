-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `boaapi_queries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catalog` varchar(255) NOT NULL,
  `query` varchar(1023) NOT NULL,
  `size` int(10) unsigned NOT NULL DEFAULT 0,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `boaapi_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `module` varchar(31) NOT NULL,
  `operation` varchar(31) NOT NULL,
  `data` varchar(1023) NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `boaapi_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(127) NOT NULL,
  `password` varchar(33) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(127) NULL,
  `lastname` varchar(127) NULL,
  `lang` VARCHAR( 15 ) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `updated_by` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_boaapi_users_username` (`username`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `boaapi_roles_assigned` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role` varchar(31) NOT NULL,
  `context` varchar(31) DEFAULT NULL,
  `elementid` int(10) unsigned DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `updated_by` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_boaapi_roles_assigned_all` (`user_id`, `role`, `context`, `elementid`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `boaapi_registries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(63) NOT NULL,
  `password` varchar(127) DEFAULT NULL,
  `username` varchar(31) DEFAULT NULL,
  `displayname` varchar(127) DEFAULT NULL,
  `token` varchar(127) DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `updated_by` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_boaapi_registries_uuid` (`uuid`)
) DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `boaapi_tracers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `registry_id` int(10) unsigned NOT NULL,
  `level` tinyint(2) unsigned NOT NULL,
  `challenge` tinyint(2) unsigned NOT NULL,
  `duration` int(11) unsigned NOT NULL,
  `score` decimal(4, 3) unsigned NOT NULL,
  `win_score` decimal(4, 3) unsigned NOT NULL,
  `correct_answers` varchar(2047) NOT NULL,
  `user_answers` varchar(2047) NOT NULL,
  `answer_at` int(11) unsigned NOT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `updated_by` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

