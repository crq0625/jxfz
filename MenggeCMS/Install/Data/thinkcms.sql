/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50524
Source Host           : 127.0.0.1:3306
Source Database       : thinkcms

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2014-10-21 17:10:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `glb_admin`
-- ----------------------------
DROP TABLE IF EXISTS `glb_admin`;
CREATE TABLE `glb_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL COMMENT '用户名',
  `password` char(32) DEFAULT NULL COMMENT '密码',
  `email` varchar(100) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态：1.可登录，0.不能登录',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  `lastlogin` varchar(45) DEFAULT NULL,
  `lastlogintime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of glb_admin
-- ----------------------------
