-- Notice: Please use IDE `replace` function to replace `{prefix}` to what you want

CREATE TABLE `nicefo_rbac_route` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `route` VARCHAR(128) NOT NULL COMMENT 'route',
  `type` INT NOT NULL DEFAULT 0 COMMENT '路由的类型: type == 0: 普通; type == 1: 自定义',
  `ext` TEXT,
  PRIMARY KEY (`id`),
  KEY (`type`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci COMMENT '路由表';
-- if type == 1: ext=json_encode(['method'=>'', 'params' => ['k1' => 'v1', 'k2' => 'v2', ...]]);

CREATE TABLE `nicefo_rbac_permission` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL COMMENT '权限名称',
  `ext` TEXT,
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci COMMENT '权限表';

CREATE TABLE `nicefo_rbac_permission_assign` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `permission_id` INT NOT NULL COMMENT '权限id',
  `route_id` INT NOT NULL COMMENT '路由id',
  PRIMARY KEY (`id`),
  KEY (`permission_id`, `route_id`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci COMMENT '权限-路由 分配表';

CREATE TABLE `nicefo_rbac_role` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL COMMENT '',
  `ext` TEXT,
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci COMMENT '角色表';

CREATE TABLE `nicefo_rbac_role_assign` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `role_id` INT NOT NULL,
  `permission_id` INT NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY (`role_id`, `permission_id`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci COMMENT '角色-[角色, 权限, 路由] 分配表';

CREATE TABLE `nicefo_rbac_user_assign` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `role_id` INT NOT NULL DEFAULT '',
  `system_id` INT NOT NULL DEFAULT 0,
  `time_create` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY (`user_id`, `role_id`),
  KEY (`time_create`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci COMMENT '用户分配表';