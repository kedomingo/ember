-- Create syntax for TABLE 'users'
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `group_id` int(11) NOT NULL DEFAULT '1',
  `email` varchar(255) DEFAULT NULL,
  `last_login` varchar(25) DEFAULT NULL,
  `previous_login` varchar(25) NOT NULL DEFAULT '0',
  `login_hash` varchar(255) DEFAULT NULL,
  `nonce` varchar(40) DEFAULT NULL COMMENT 'account verification nonce key',
  `user_id` int(11) unsigned DEFAULT NULL,
  `receive_coupons` tinyint(1) NOT NULL DEFAULT '1',
  `points` int(11) NOT NULL DEFAULT '0',
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'users_group_permissions'
CREATE TABLE IF NOT EXISTS `users_group_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL,
  `perms_id` int(11) unsigned NOT NULL,
  `actions` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`group_id`,`perms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'users_group_roles'
CREATE TABLE IF NOT EXISTS `users_group_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`,`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'users_groups'
CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` int(11) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'users_legacy'
CREATE TABLE IF NOT EXISTS `users_legacy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `facebook_id` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '1',
  `email` varchar(255) NOT NULL,
  `kind` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `myouji_kanji` varchar(255) NOT NULL,
  `namae_kanji` varchar(255) NOT NULL,
  `myouji_kana` varchar(255) NOT NULL,
  `namae_kana` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `cell_number` varchar(255) NOT NULL,
  `postal_code1` varchar(255) NOT NULL,
  `postal_code2` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `building_name` varchar(255) NOT NULL,
  `cell_email` varchar(255) NOT NULL,
  `how_know` varchar(255) NOT NULL,
  `receive_coupons` int(11) NOT NULL,
  `point` int(11) NOT NULL DEFAULT '0',
  `register_token` varchar(100) NOT NULL,
  `forgot_token` varchar(255) NOT NULL,
  `register_url` varchar(255) NOT NULL,
  `current_order` text NOT NULL,
  `last_login` int(11) NOT NULL,
  `previous_login` datetime DEFAULT NULL,
  `login_hash` varchar(255) NOT NULL,
  `profile_fields` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `registered_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_email` (`username`,`email`)
) ENGINE=MyISAM AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'users_metadata'
CREATE TABLE IF NOT EXISTS `users_metadata` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `key` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created_at` int(11) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'users_permissions'
CREATE TABLE IF NOT EXISTS `users_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `area` varchar(128) NOT NULL DEFAULT '',
  `permission` varchar(128) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `actions` text,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` int(11) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`),
  KEY `area` (`area`),
  KEY `permission` (`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'users_role_permissions'
CREATE TABLE IF NOT EXISTS `users_role_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `perms_id` int(11) NOT NULL,
  `actions` text,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`,`perms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'users_roles'
CREATE TABLE IF NOT EXISTS `users_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `filter` enum('','A','D','R') NOT NULL DEFAULT '',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created_at` int(11) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'users_user_permissions'
CREATE TABLE IF NOT EXISTS `users_user_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `perms_id` int(11) unsigned NOT NULL,
  `actions` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'users_user_roles'
CREATE TABLE IF NOT EXISTS `users_user_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;