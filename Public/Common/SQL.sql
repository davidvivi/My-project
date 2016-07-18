-- ----------------------------
-- think_auth_rule，规则表，
-- id:主键，name：规则唯一标识, title：规则中文名称 status 状态：为1正常，为0禁用，condition：规则表达式，为空表示存在就验证，不为空表示按照条件验证
-- ----------------------------
 DROP TABLE IF EXISTS `think_auth_rule`;
CREATE TABLE `think_auth_rule` (  
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,  
    `name` char(80) NOT NULL DEFAULT '',  
    `title` char(20) NOT NULL DEFAULT '',  
    `type` tinyint(1) NOT NULL DEFAULT '1',    
    `status` tinyint(1) NOT NULL DEFAULT '1',  
    `condition` char(100) NOT NULL DEFAULT '',  # 规则附件条件,满足附加条件的规则,才认为是有效的规则
    PRIMARY KEY (`id`),  
    UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- ----------------------------
-- think_auth_group 用户组表， 
-- id：主键， title:用户组中文名称， rules：用户组拥有的规则id， 多个规则","隔开，status 状态：为1正常，为0禁用
-- ----------------------------
 DROP TABLE IF EXISTS `think_auth_group`;
CREATE TABLE `think_auth_group` ( 
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT, 
    `title` char(100) NOT NULL DEFAULT '', 
    `status` tinyint(1) NOT NULL DEFAULT '1', 
    `rules` char(80) NOT NULL DEFAULT '', 
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- ----------------------------
-- think_auth_group_access 用户组明细表
-- uid:用户id，group_id：用户组id
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_group_access`;
CREATE TABLE `think_auth_group_access` (  
    `uid` mediumint(8) unsigned NOT NULL,  
    `group_id` mediumint(8) unsigned NOT NULL, 
    UNIQUE KEY `uid_group_id` (`uid`,`group_id`),  
    KEY `uid` (`uid`), 
    KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- think_img_turn 轮播图管理表
-- 
-- ----------------------------
DROP TABLE IF EXISTS `think_img_turn`;
CREATE TABLE `think_img_turn` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `reorder` int NOT NULL comment '轮播图排序顺序,从小到大的排序',
  `imgurl` varchar(200) NOT NULL comment '轮播图的地址',
  `category_id` int NOT NULL comment '商品类型的id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



-- admin 表
CREATE TABLE IF NOT EXISTS `think_admin`(
    `id` INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(32) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `grade` TINYINT(1) UNSIGNED NOT NULL DEFAULT 3,
    `pic` VARCHAR(100) NOT NULL,
    `tel` INT(11) UNSIGNED NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `addtime` INT NOT NULL,
    `type` ENUM('0','1') NOT NULL DEFAULT 1 COMMENT '0:禁用 1：使用中'
)ENGINE=INNODB DEFAULT CHARSET=UTF8;


--user 表
CREATE TABLE IF NOT EXISTS `think_user`(
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(32) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `addtime` INT(30) NOT NULL,
    `tel` VARCHAR(20) NOT NULL,
    `status` TINYINT(1) NOT NULL
)ENGINE=INNODB DEFAULT CHARSET=UTF8;
	
	
-- 订单表order
CREATE TABLE IF NOT EXISTS `think_order`(
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`uid` INT NOT NULL COMMENT '用户id',
	`num_id` INT NOT NULL COMMENT '订单号',
	`buy` DOUBLE(8,2) NOT NULL COMMENT '总金额',
	`written` VARCHAR(255) COMMENT '留言',
	`emailno` INT(6) NOT NULL COMMENT '邮编',
	`address` VARCHAR(100) NOT NULL COMMENT '地址',
	`tel` VARCHAR(32) NOT NULL,
	`num` INT UNSIGNED NOT NULL COMMENT '购买数量',
	`state` TINYINT(1) NOT NULL DEFAULT 1,
	`addtime` INT(30) NOT NULL
)ENGINE=INNODB DEFAULT CHARSET=UTF8;


-- --------------------------------------
--
--   订单详情表 orderdetail
-- ---------------------------------------
CREATE TABLE IF NOT EXISTS `think_orderdetail`(
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`oid` INT NOT NULL COMMENT '订单id',
	`uid` INT NOT NULL COMMENT '用户id',
	`gid` INT NOT NULL COMMENT '商品id',
	`pic` VARCHAR(255) NOT NULL COMMENT '商品图',
	`num` INT NOT NULL COMMENT '数量',
	`price` DOUBLE(8,2) NOT NULL COMMENT '单价',
	`guige` VARCHAR(255) NOT NULL COMMENT '规格',
	`state` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '评价状态'
)ENGINE = INNODB DEFAULT CHARSET=UTF8;