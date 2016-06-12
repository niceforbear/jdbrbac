-- Notice: Please use IDE `replace` function to replace `{prefix}` to what you want

CREATE TABLE `{prefix}_rbac_route` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) COMMENT 'route name',
  `route` VARCHAR(128) NOT NULL COMMENT 'route',
  `system` INT UNSIGNED NOT NULL COMMENT 'The system which the route belongs to',
  `time_create` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_update` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ext` TEXT,
  PRIMARY KEY (`id`)
)ENGINE = InnoDB AUTO_INCREMENT=600 DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci COMMENT 'Route table';

CREATE TABLE `{prefix}_rbac_permission` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL COMMENT 'permission name',
  `time_create` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_update` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ext` TEXT,
  PRIMARY KEY (`id`)
)ENGINE = InnoDB AUTO_INCREMENT=500 DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci COMMENT 'Permission base table';

CREATE TABLE `{prefix}_rbac_permission_assign` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `permission_id` INT UNSIGNED NOT NULL COMMENT 'rbac_permission.id',
  `route_id` INT UNSIGNED NOT NULL COMMENT 'rbac_route.id',
  `time_create` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY (`permission_id`)
)ENGINE = InnoDB AUTO_INCREMENT=400 DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci COMMENT 'Permission - route table';

CREATE TABLE `{prefix}_rbac_role` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL COMMENT '',
  `time_create` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_update` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ext` TEXT,
  PRIMARY KEY (`id`)
)ENGINE = InnoDB AUTO_INCREMENT=300 DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci COMMENT 'Role name table';

CREATE TABLE `{prefix}_rbac_role_assign` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` INT UNSIGNED NOT NULL,
  `permission_id` INT UNSIGNED NOT NULL,
  `time_create` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY (`role_id`)
)ENGINE = InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci COMMENT 'Role - perm assign table';

CREATE TABLE `{prefix}_rbac_user_assign` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `role_id` INT UNSIGNED NOT NULL,
  `time_create` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY (`user_id`, `role_id`)
)ENGINE = InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci COMMENT 'User - role assign table';