/*
Navicat MySQL Data Transfer

Source Server         : tianma
Source Server Version : 50717
Source Host           : 47.93.7.208:3306
Source Database       : fy_film

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2018-05-12 20:30:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for film_admin
-- ----------------------------
DROP TABLE IF EXISTS `film_admin`;
CREATE TABLE `film_admin` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `phone` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机号',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `unionid` varchar(32) NOT NULL DEFAULT '' COMMENT '微信唯一id',
  `openid` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '微信id',
  `username` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户名',
  `avatar` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '头像',
  `usergroup` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否超级管理员',
  `admingroup` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否管理员',
  `session_key` varchar(255) NOT NULL DEFAULT '' COMMENT 'session',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`),
  KEY `openid` (`openid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of film_admin
-- ----------------------------
INSERT INTO `film_admin` VALUES ('1', '1234561', '30e22097071af764de15ee6f859da841', '', '', 'admin', '', '0', '1', '', '2018-02-26 17:20:10', '2018-03-30 16:40:51', '1');

-- ----------------------------
-- Table structure for film_answer
-- ----------------------------
DROP TABLE IF EXISTS `film_answer`;
CREATE TABLE `film_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `qid` int(11) NOT NULL DEFAULT '0' COMMENT '问题id',
  `answer` text NOT NULL COMMENT '答案',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '提交时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COMMENT='问卷答案';

-- ----------------------------
-- Records of film_answer
-- ----------------------------
INSERT INTO `film_answer` VALUES ('14', '2', '8', '{\"1\":{\"q\":\"多选题\",\"type\":2,\"a\":[\"选项1\"]},\"2\":{\"q\":\"单选题\",\"type\":1,\"a\":[\"选项1\"]},\"3\":{\"q\":\"多选题\",\"type\":2,\"a\":[\"选项1\"]},\"4\":{\"q\":\"你最喜欢哪个明星？\",\"type\":3,\"a\":[\"7\"]},\"5\":{\"q\":\"单选题\",\"type\":1,\"a\":[\"选项1\"]}}', '2018-04-25 19:36:00', '2018-04-25 19:36:00', '2018-04-25 19:36:00');
INSERT INTO `film_answer` VALUES ('16', '2', '8', '{\"1\":{\"q\":\"多选题\",\"type\":2,\"a\":[\"选项1\"]},\"2\":{\"q\":\"单选题\",\"type\":1,\"a\":[\"选项1\"]},\"3\":{\"q\":\"多选题\",\"type\":2,\"a\":[\"选项1\"]},\"4\":{\"q\":\"你最喜欢哪个明星？\",\"type\":3,\"a\":[\"3\"]},\"5\":{\"q\":\"单选题\",\"type\":1,\"a\":[\"选项1\"]}}', '2018-04-25 19:38:42', '2018-04-25 19:38:42', '2018-04-25 19:38:42');
INSERT INTO `film_answer` VALUES ('17', '2', '8', '{\"1\":{\"q\":\"多选题\",\"type\":2,\"a\":[\"选项1\",\"选项1\"]},\"2\":{\"q\":\"单选题\",\"type\":1,\"a\":[\"选项1\"]},\"3\":{\"q\":\"多选题\",\"type\":2,\"a\":[\"选项1\"]},\"4\":{\"q\":\"你最喜欢哪个明星？\",\"type\":3,\"a\":[\"999999999999999999\"]},\"5\":{\"q\":\"单选题\",\"type\":1,\"a\":[\"选项1\"]}}', '2018-04-25 19:49:54', '2018-04-25 19:49:54', '2018-04-25 19:49:54');
INSERT INTO `film_answer` VALUES ('18', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123\"]}}', '2018-04-27 20:19:39', '2018-04-27 20:19:39', '2018-04-27 20:19:39');
INSERT INTO `film_answer` VALUES ('19', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123\"]}}', '2018-04-27 20:19:45', '2018-04-27 20:19:45', '2018-04-27 20:19:45');
INSERT INTO `film_answer` VALUES ('20', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123\"]}}', '2018-04-27 20:19:47', '2018-04-27 20:19:47', '2018-04-27 20:19:47');
INSERT INTO `film_answer` VALUES ('21', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123\"]}}', '2018-04-27 20:19:48', '2018-04-27 20:19:48', '2018-04-27 20:19:48');
INSERT INTO `film_answer` VALUES ('22', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123\"]}}', '2018-04-27 20:19:48', '2018-04-27 20:19:48', '2018-04-27 20:19:48');
INSERT INTO `film_answer` VALUES ('23', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123\"]}}', '2018-04-27 20:19:48', '2018-04-27 20:19:48', '2018-04-27 20:19:48');
INSERT INTO `film_answer` VALUES ('24', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123\"]}}', '2018-04-27 20:19:49', '2018-04-27 20:19:49', '2018-04-27 20:19:49');
INSERT INTO `film_answer` VALUES ('25', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123\"]}}', '2018-04-27 20:19:49', '2018-04-27 20:19:49', '2018-04-27 20:19:49');
INSERT INTO `film_answer` VALUES ('26', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123\"]}}', '2018-04-27 20:19:50', '2018-04-27 20:19:50', '2018-04-27 20:19:50');
INSERT INTO `film_answer` VALUES ('27', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123\"]}}', '2018-04-27 20:19:50', '2018-04-27 20:19:50', '2018-04-27 20:19:50');
INSERT INTO `film_answer` VALUES ('28', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123456\"]}}', '2018-04-27 20:19:53', '2018-04-27 20:19:53', '2018-04-27 20:19:53');
INSERT INTO `film_answer` VALUES ('29', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123456\"]}}', '2018-04-27 20:19:54', '2018-04-27 20:19:54', '2018-04-27 20:19:54');
INSERT INTO `film_answer` VALUES ('30', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123456\"]}}', '2018-04-27 20:19:54', '2018-04-27 20:19:54', '2018-04-27 20:19:54');
INSERT INTO `film_answer` VALUES ('31', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123456\"]}}', '2018-04-27 20:19:55', '2018-04-27 20:19:55', '2018-04-27 20:19:55');
INSERT INTO `film_answer` VALUES ('32', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123456\"]}}', '2018-04-27 20:19:55', '2018-04-27 20:19:55', '2018-04-27 20:19:55');
INSERT INTO `film_answer` VALUES ('33', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123456\"]}}', '2018-04-27 20:19:56', '2018-04-27 20:19:56', '2018-04-27 20:19:56');
INSERT INTO `film_answer` VALUES ('34', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123456\"]}}', '2018-04-27 20:19:56', '2018-04-27 20:19:56', '2018-04-27 20:19:56');
INSERT INTO `film_answer` VALUES ('35', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123456\"]}}', '2018-04-27 20:19:56', '2018-04-27 20:19:56', '2018-04-27 20:19:56');
INSERT INTO `film_answer` VALUES ('36', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选1\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"阿卡丽\"]}}', '2018-04-27 20:20:28', '2018-04-27 20:20:28', '2018-04-27 20:20:28');
INSERT INTO `film_answer` VALUES ('37', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选1\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"阿卡丽\"]}}', '2018-04-27 20:20:30', '2018-04-27 20:20:30', '2018-04-27 20:20:30');
INSERT INTO `film_answer` VALUES ('38', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选1\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"阿卡丽\"]}}', '2018-04-27 20:20:31', '2018-04-27 20:20:31', '2018-04-27 20:20:31');
INSERT INTO `film_answer` VALUES ('39', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选1\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"阿卡丽\"]}}', '2018-04-27 20:20:31', '2018-04-27 20:20:31', '2018-04-27 20:20:31');
INSERT INTO `film_answer` VALUES ('40', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选1\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选2\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"99\"]}}', '2018-04-27 20:20:38', '2018-04-27 20:20:38', '2018-04-27 20:20:38');
INSERT INTO `film_answer` VALUES ('41', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选1\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选2\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"99\"]}}', '2018-04-27 20:20:42', '2018-04-27 20:20:42', '2018-04-27 20:20:42');
INSERT INTO `film_answer` VALUES ('42', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选1\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选2\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"99\"]}}', '2018-04-27 20:20:42', '2018-04-27 20:20:42', '2018-04-27 20:20:42');
INSERT INTO `film_answer` VALUES ('43', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选1\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选2\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"99\"]}}', '2018-04-27 20:20:42', '2018-04-27 20:20:42', '2018-04-27 20:20:42');
INSERT INTO `film_answer` VALUES ('44', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123456\"]}}', '2018-04-27 20:20:45', '2018-04-27 20:20:45', '2018-04-27 20:20:45');
INSERT INTO `film_answer` VALUES ('45', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123456\"]}}', '2018-04-27 20:20:45', '2018-04-27 20:20:45', '2018-04-27 20:20:45');
INSERT INTO `film_answer` VALUES ('46', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123456\"]}}', '2018-04-27 20:20:46', '2018-04-27 20:20:46', '2018-04-27 20:20:46');
INSERT INTO `film_answer` VALUES ('47', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选2\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\",\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"123456\"]}}', '2018-04-27 20:20:46', '2018-04-27 20:20:46', '2018-04-27 20:20:46');
INSERT INTO `film_answer` VALUES ('48', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选1\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"2\"]}}', '2018-04-27 20:22:22', '2018-04-27 20:22:22', '2018-04-27 20:22:22');
INSERT INTO `film_answer` VALUES ('49', '2', '9', '{\"1\":{\"q\":\"单选测试\",\"type\":1,\"a\":[\"选1\"]},\"2\":{\"q\":\"多选测试\",\"type\":2,\"a\":[\"选1\"]},\"3\":{\"q\":\"填空题测试\",\"type\":3,\"a\":[\"阿卡丽\"]}}', '2018-04-27 20:25:42', '2018-04-27 20:25:42', '2018-04-27 20:25:42');
INSERT INTO `film_answer` VALUES ('50', '15', '10', '{\"1\":{\"q\":\"123\",\"type\":1,\"a\":[\"123\"]}}', '2018-05-04 20:13:18', '2018-05-04 20:13:18', '2018-05-04 20:13:18');
INSERT INTO `film_answer` VALUES ('51', '15', '10', '{\"1\":{\"q\":\"123\",\"type\":1,\"a\":[\"123\"]}}', '2018-05-05 11:48:48', '2018-05-05 11:48:48', '2018-05-05 11:48:48');
INSERT INTO `film_answer` VALUES ('52', '15', '10', '{\"1\":{\"q\":\"123\",\"type\":1,\"a\":[\"123\"]}}', '2018-05-07 13:57:02', '2018-05-07 13:57:02', '2018-05-07 13:57:02');
INSERT INTO `film_answer` VALUES ('53', '15', '10', '{\"1\":{\"q\":\"123\",\"type\":1,\"a\":[\"123\"]}}', '2018-05-07 13:57:04', '2018-05-07 13:57:04', '2018-05-07 13:57:04');
INSERT INTO `film_answer` VALUES ('54', '15', '10', '{\"1\":{\"q\":\"123\",\"type\":1,\"a\":[\"123\"]}}', '2018-05-07 13:57:10', '2018-05-07 13:57:10', '2018-05-07 13:57:10');
INSERT INTO `film_answer` VALUES ('55', '15', '10', '{\"1\":{\"q\":\"123\",\"type\":1,\"a\":[\"123\"]}}', '2018-05-08 11:28:40', '2018-05-08 11:28:40', '2018-05-08 11:28:40');
INSERT INTO `film_answer` VALUES ('56', '15', '10', '{\"1\":{\"q\":\"123\",\"type\":1,\"a\":[\"123\"]}}', '2018-05-08 11:28:53', '2018-05-08 11:28:53', '2018-05-08 11:28:53');
INSERT INTO `film_answer` VALUES ('57', '15', '10', '{\"1\":{\"q\":\"123\",\"type\":1,\"a\":[\"123\"]}}', '2018-05-08 11:29:20', '2018-05-08 11:29:20', '2018-05-08 11:29:20');
INSERT INTO `film_answer` VALUES ('58', '15', '10', '{\"1\":{\"q\":\"123\",\"type\":1,\"a\":[\"123\"]}}', '2018-05-08 11:30:44', '2018-05-08 11:30:44', '2018-05-08 11:30:44');
INSERT INTO `film_answer` VALUES ('59', '15', '10', '{\"1\":{\"q\":\"123\",\"type\":1,\"a\":[\"123\"]}}', '2018-05-08 11:31:08', '2018-05-08 11:31:08', '2018-05-08 11:31:08');
INSERT INTO `film_answer` VALUES ('60', '15', '10', '{\"1\":{\"q\":\"123\",\"type\":1,\"a\":[\"123\"]}}', '2018-05-08 11:32:21', '2018-05-08 11:32:21', '2018-05-08 11:32:21');
INSERT INTO `film_answer` VALUES ('61', '15', '10', '{\"1\":{\"q\":\"123\",\"type\":1,\"a\":[\"123\"]}}', '2018-05-08 11:33:33', '2018-05-08 11:33:33', '2018-05-08 11:33:33');

-- ----------------------------
-- Table structure for film_company
-- ----------------------------
DROP TABLE IF EXISTS `film_company`;
CREATE TABLE `film_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `contact` varchar(255) NOT NULL DEFAULT '' COMMENT '公司联系人',
  `phone` varchar(32) NOT NULL DEFAULT '' COMMENT '联系电话',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `cname_cn` varchar(255) NOT NULL DEFAULT '' COMMENT '中文名',
  `cname_en` varchar(255) NOT NULL DEFAULT '' COMMENT '英文名',
  `brief` varchar(2000) NOT NULL DEFAULT '' COMMENT '简介',
  `website` varchar(255) NOT NULL DEFAULT '' COMMENT '网址',
  `license` varchar(255) NOT NULL DEFAULT '' COMMENT '许可证',
  `apply` varchar(255) NOT NULL DEFAULT '' COMMENT '申请书',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未审核，1审核通过, 2审核未通过',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COMMENT='公司信息';

-- ----------------------------
-- Records of film_company
-- ----------------------------
INSERT INTO `film_company` VALUES ('32', '2', '王亚洲', '13691214607', '332472018@qq.com', '摩羯', 'Capricorn', '一家专门做买卖的公司', 'http//:Capricorn.com', 'asdasd', 'adassd', '0', '2018-04-27 10:50:21', '2018-04-27 10:50:21');
INSERT INTO `film_company` VALUES ('33', '2', '王亚洲啊~~~', '139白酒红酒葡萄酒', '4565656565656', '德玛西亚', 'demaxiya', 'LOL', 'www.baidu.com', 'asdasd', 'adassd', '0', '2018-04-27 11:15:35', '2018-04-27 11:15:35');
INSERT INTO `film_company` VALUES ('55', '15', '申请', '123', 'sss', '测试', 'ceshi', '简介', 'www', 'https://wxappres.feeyan.com/film/2018/05/6bKBgPUT3L0hjrtEIzof8VyipDq4eWaM.jpg', 'https://wxappres.feeyan.com/film/2018/05/nuJdKXFtpLgsQAGCrDq4YPyUaIi9vzbe.png', '2', '2018-05-09 11:25:08', '2018-05-09 11:54:44');
INSERT INTO `film_company` VALUES ('56', '15', '申请红红火火', '123', 'sss', '测试', 'ceshi', '简介222', 'www', 'https://wxappres.feeyan.com/film/2018/05/6bKBgPUT3L0hjrtEIzof8VyipDq4eWaM.jpg', 'https://wxappres.feeyan.com/film/2018/05/nuJdKXFtpLgsQAGCrDq4YPyUaIi9vzbe.png', '1', '2018-05-11 14:45:08', '2018-05-11 15:07:19');

-- ----------------------------
-- Table structure for film_company_audit_log
-- ----------------------------
DROP TABLE IF EXISTS `film_company_audit_log`;
CREATE TABLE `film_company_audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '资讯id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '审核人id',
  `reason` varchar(500) NOT NULL DEFAULT '' COMMENT '未通过原因',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='公司审核记录';

-- ----------------------------
-- Records of film_company_audit_log
-- ----------------------------
INSERT INTO `film_company_audit_log` VALUES ('2', '36', '2', '打发打发', '2018-05-04 22:37:22', '2018-05-04 22:37:22');
INSERT INTO `film_company_audit_log` VALUES ('3', '36', '2', '阿斯顿发射点', '2018-05-04 22:48:04', '2018-05-04 22:48:04');
INSERT INTO `film_company_audit_log` VALUES ('4', '50', '15', '哦', '2018-05-07 16:36:28', '2018-05-07 16:36:28');
INSERT INTO `film_company_audit_log` VALUES ('5', '55', '15', '不通过', '2018-05-09 11:54:44', '2018-05-09 11:54:44');
INSERT INTO `film_company_audit_log` VALUES ('6', '56', '15', '456', '2018-05-11 15:04:47', '2018-05-11 15:04:47');

-- ----------------------------
-- Table structure for film_experts
-- ----------------------------
DROP TABLE IF EXISTS `film_experts`;
CREATE TABLE `film_experts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `breif` varchar(1000) NOT NULL DEFAULT '' COMMENT '简介',
  `create_time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='专家';

-- ----------------------------
-- Records of film_experts
-- ----------------------------
INSERT INTO `film_experts` VALUES ('1', '专家1', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '专家1\r\n', '2018-04-12 15:08:42', '1', '2018-04-23 10:47:09');
INSERT INTO `film_experts` VALUES ('2', '专家2', 'https://wxappres.feeyan.com/film/2018/04/OPypleGQojYB857IiL3aSxKvZ0gtRcVq.png', '测试', '2018-04-18 17:36:06', '1', '2018-04-23 10:48:50');
INSERT INTO `film_experts` VALUES ('3', 'Amber Zhang', 'https://wxappres.feeyan.com/film/2018/05/5QGXF2ZBNOsVtidxCoaJbEW9Pf0nS1gI.png', '测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试11111', '2018-05-04 11:52:46', '1', '2018-05-04 12:00:51');

-- ----------------------------
-- Table structure for film_meeting
-- ----------------------------
DROP TABLE IF EXISTS `film_meeting`;
CREATE TABLE `film_meeting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `sTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间',
  `eTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `info_set` text NOT NULL COMMENT '人员信息设置',
  `intro` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='会议';

-- ----------------------------
-- Records of film_meeting
-- ----------------------------
INSERT INTO `film_meeting` VALUES ('1', '1', 'https://wxappres.feeyan.com/film/2018/04/yXmdSCHRgtsc53z7GYV2BxqorTUaZbuM.png', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '[\"11\",\"22\"]', '<p>1</p>', '0', '2018-04-12 11:04:55', '2018-04-12 11:59:26');
INSERT INTO `film_meeting` VALUES ('2', '测试会议1', 'https://wxappres.feeyan.com/film/2018/04/yXmdSCHRgtsc53z7GYV2BxqorTUaZbuM.png', '北美国际', '2018-04-12 16:20:35', '2018-04-12 18:20:47', '[\"姓名\",\"手机号\"]', '<p>测试</p>', '1', '2018-04-12 11:05:46', '2018-04-12 11:05:46');
INSERT INTO `film_meeting` VALUES ('3', '测试会议2', 'https://wxappres.feeyan.com/film/2018/04/GlSiWg0yVCfJAFPK6Hs2u8jOMdXBk3Db.png', '北美国际', '2018-04-13 17:15:00', '2018-04-20 22:30:00', '[\"\"]', '<p style=\"margin: 0px 8px; padding: 0px; max-width: 100%; min-height: 1em; color: rgb(62, 62, 62);\">国产电视动画进一步繁荣发展，涌现出一批思想精深、艺术精湛、制作精良相统一的优秀作品，为未成年人的健康成长提供了优质精神食粮，营造了绿色空间。</p>', '1', '2018-04-13 16:49:56', '2018-04-20 21:41:10');
INSERT INTO `film_meeting` VALUES ('4', '影视协作体上线大会', 'https://wxappres.feeyan.com/film/2018/04/TrfFSRvE8GYCVnhkcmxQI9ytaZgeNO3B.png', '海淀区', '2018-04-28 09:30:00', '2018-05-07 20:45:00', '[\"\\u8eab\\u4efd\\u8bc1\\u53f7\"]', '<p>测试测试测试数据</p><p>测试测试测试数据</p><p>测试测试测试数据</p><p>测试测试测试数据</p><p>测试测试测试数据</p><p><img src=\"https://wxappres.feeyan.com/film/2018/04/OjeAHciPSBfyVZXaYomIG3zsUp4Mg06h.png\" title=\"\" width=\"100%\" alt=\"\"/></p><p><br/></p><p style=\"white-space: normal;\"><br/></p><p><br/></p>', '1', '2018-04-27 20:44:50', '2018-05-07 20:05:35');

-- ----------------------------
-- Table structure for film_meeting_order
-- ----------------------------
DROP TABLE IF EXISTS `film_meeting_order`;
CREATE TABLE `film_meeting_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderSn` varchar(32) NOT NULL DEFAULT '' COMMENT '订单号',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会议id',
  `member` varchar(1000) NOT NULL DEFAULT '' COMMENT '人员信息',
  `contact` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人',
  `phone` varchar(32) NOT NULL DEFAULT '' COMMENT '联系人电话',
  `cname` varchar(255) NOT NULL DEFAULT '' COMMENT '公司名称',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '提交时间',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `m_title` varchar(255) NOT NULL DEFAULT '' COMMENT '会议标题',
  `m_address` varchar(255) NOT NULL DEFAULT '' COMMENT '会议地址',
  `m_sTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间',
  `m_eTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `m_cover` varchar(255) NOT NULL DEFAULT '' COMMENT '封面',
  `send_status` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效',
  `extend` text NOT NULL COMMENT '扩展信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of film_meeting_order
-- ----------------------------
INSERT INTO `film_meeting_order` VALUES ('105', '2018050812275442629522149', '15', '0', '4', '{\"\\u59d3\\u540d\":\"1\",\"\\u804c\\u52a1\":\"1\",\"\\u7535\\u8bdd\":\"1\"}', '测试', '123', 'ce\'shi', '1525753674', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '影视协作体上线大会', '海淀区', '2018-04-28 09:30:00', '2018-05-07 20:45:00', 'https://wxappres.feeyan.com/film/2018/04/TrfFSRvE8GYCVnhkcmxQI9ytaZgeNO3B.png', '0', '1', '{\"身份证号\":\"123\"}');
INSERT INTO `film_meeting_order` VALUES ('106', '2018050812275442631598977', '15', '0', '4', '{\"\\u59d3\\u540d\":\"2\",\"\\u804c\\u52a1\":\"2\",\"\\u7535\\u8bdd\":\"2\"}', '测试', '123', 'ce\'shi', '1525753674', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '影视协作体上线大会', '海淀区', '2018-04-28 09:30:00', '2018-05-07 20:45:00', 'https://wxappres.feeyan.com/film/2018/04/TrfFSRvE8GYCVnhkcmxQI9ytaZgeNO3B.png', '0', '1', '{\"身份证号\":\"123\"}');
INSERT INTO `film_meeting_order` VALUES ('108', '2018050812310083702810910', '15', '0', '4', '{\"\\u59d3\\u540d\":\"11\",\"\\u804c\\u52a1\":\"11\",\"\\u7535\\u8bdd\":\"11\"}', 'ceshi', 'ceshi', 'ceshi', '1525753860', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '影视协作体上线大会', '海淀区', '2018-04-28 09:30:00', '2018-05-07 20:45:00', 'https://wxappres.feeyan.com/film/2018/04/TrfFSRvE8GYCVnhkcmxQI9ytaZgeNO3B.png', '0', '1', '{\"身份证号\":\"1\"}');
INSERT INTO `film_meeting_order` VALUES ('109', '2018050802201930001656169', '15', '0', '3', '{\"\\u59d3\\u540d\":\"5\",\"\\u804c\\u52a1\":\"5\",\"\\u7535\\u8bdd\":\"5\"}', '55', '55', '55', '1525760419', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '测试会议2', '北美国际', '2018-04-13 17:15:00', '2018-04-20 22:30:00', 'https://wxappres.feeyan.com/film/2018/04/GlSiWg0yVCfJAFPK6Hs2u8jOMdXBk3Db.png', '0', '1', '{}');
INSERT INTO `film_meeting_order` VALUES ('110', '2018050804310689860272166', '15', '0', '3', '{\"\\u59d3\\u540d\":\"66\",\"\\u804c\\u52a1\":\"66\",\"\\u7535\\u8bdd\":\"66\"}', '66', '66', '66', '1525768266', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '测试会议2', '北美国际', '2018-04-13 17:15:00', '2018-04-20 22:30:00', 'https://wxappres.feeyan.com/film/2018/04/GlSiWg0yVCfJAFPK6Hs2u8jOMdXBk3Db.png', '0', '1', '{}');
INSERT INTO `film_meeting_order` VALUES ('111', '2018050804455985907295864', '15', '0', '3', '{\"\\u59d3\\u540d\":\"3\",\"\\u804c\\u52a1\":\"2\",\"\\u7535\\u8bdd\":\"3\"}', '我', '2', '22', '1525769159', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '测试会议2', '北美国际', '2018-04-13 17:15:00', '2018-04-20 22:30:00', 'https://wxappres.feeyan.com/film/2018/04/GlSiWg0yVCfJAFPK6Hs2u8jOMdXBk3Db.png', '0', '1', '{}');
INSERT INTO `film_meeting_order` VALUES ('112', '2018050804545489881193172', '15', '0', '4', '{\"\\u59d3\\u540d\":\"\\u7684\",\"\\u804c\\u52a1\":\"3\",\"\\u7535\\u8bdd\":\"3\"}', '45', '4', '4', '1525769694', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '影视协作体上线大会', '海淀区', '2018-04-28 09:30:00', '2018-05-07 20:45:00', 'https://wxappres.feeyan.com/film/2018/04/TrfFSRvE8GYCVnhkcmxQI9ytaZgeNO3B.png', '0', '1', '{\"身份证号\":\"4\"}');
INSERT INTO `film_meeting_order` VALUES ('113', '2018050807061437670803295', '15', '0', '3', '{\"\\u59d3\\u540d\":\"1\",\"\\u804c\\u52a1\":\"2\",\"\\u7535\\u8bdd\":\"3\"}', '嗯嗯', '589', '369', '1525777574', '0000-00-00 00:00:00', '2018-05-10 15:14:52', '测试会议2', '北美国际', '2018-04-13 17:15:00', '2018-04-20 22:30:00', 'https://wxappres.feeyan.com/film/2018/04/GlSiWg0yVCfJAFPK6Hs2u8jOMdXBk3Db.png', '1', '1', '{}');
INSERT INTO `film_meeting_order` VALUES ('114', '2018050911261830787994848', '15', '0', '3', '{\"\\u59d3\\u540d\":\"666\",\"\\u804c\\u52a1\":\"666\",\"\\u7535\\u8bdd\":\"666\"}', '66', '666', '666', '1525836378', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '测试会议2', '北美国际', '2018-04-13 17:15:00', '2018-04-20 22:30:00', 'https://wxappres.feeyan.com/film/2018/04/GlSiWg0yVCfJAFPK6Hs2u8jOMdXBk3Db.png', '0', '1', '{}');
INSERT INTO `film_meeting_order` VALUES ('115', '2018050905180580384136118', '15', '0', '4', '{\"\\u59d3\\u540d\":\"1\",\"\\u804c\\u52a1\":\"1\",\"\\u7535\\u8bdd\":\"1\"}', '1', '1', '1', '1525857485', '0000-00-00 00:00:00', '2018-05-10 15:01:59', '影视协作体上线大会', '海淀区', '2018-04-28 09:30:00', '2018-05-07 20:45:00', 'https://wxappres.feeyan.com/film/2018/04/TrfFSRvE8GYCVnhkcmxQI9ytaZgeNO3B.png', '1', '1', '{\"身份证号\":\"1\"}');

-- ----------------------------
-- Table structure for film_news
-- ----------------------------
DROP TABLE IF EXISTS `film_news`;
CREATE TABLE `film_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '封面',
  `author` varchar(255) NOT NULL DEFAULT '' COMMENT '作者',
  `content` text NOT NULL COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `audit_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0审核未通过，1一级审核，2二级审核，3三级审核，4四级审核，5审核通过',
  `audit_time` datetime NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `pubdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '发布时间',
  `is_admin` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1用户，2后台',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COMMENT='资讯列表';

-- ----------------------------
-- Records of film_news
-- ----------------------------
INSERT INTO `film_news` VALUES ('1', '2', '0', '测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '2018-05-11 17:15:12', '2018-04-23 15:28:01', '2018-04-23 15:28:01', '2018-05-11 17:15:12', '1');
INSERT INTO `film_news` VALUES ('2', '2', '0', '测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 15:28:23', '2018-04-23 15:28:23', '2018-04-22 18:04:40', '1');
INSERT INTO `film_news` VALUES ('3', '2', '0', '测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('4', '2', '0', 'asd', '../../images/pag.jpg', 'asd', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-04-24 14:50:43', '2018-04-24 14:50:43', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('5', '2', '0', 'asd', '../../images/pag.jpg', 'asd', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-04-24 15:07:06', '2018-04-24 15:07:06', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('6', '2', '0', 'asd', '../../images/pag.jpg', 'asd', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-04-24 15:10:53', '2018-04-24 15:10:53', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('7', '2', '0', 'asd', '../../images/pag.jpg', 'asd', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-04-24 15:11:00', '2018-04-24 15:11:00', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('8', '2', '0', 'asd', '../../images/pag.jpg', 'asd', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-04-24 16:28:21', '2018-04-24 16:28:21', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('9', '2', '0', 'asd', '../../images/pag.jpg', 'asd', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-04-24 16:29:03', '2018-04-24 16:29:03', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('10', '2', '0', 'asd', '../../images/pag.jpg', 'asd', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-04-24 16:30:34', '2018-04-24 16:30:34', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('11', '2', '0', '啊萨达时代', '../../images/pag.jpg', '阿达', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-04-27 11:17:27', '2018-04-27 11:17:27', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('12', '2', '0', '阿萨德', '../../images/pag.jpg', '打打', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-04-27 19:34:56', '2018-04-27 19:34:56', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('13', '2', '0', '3', '../../images/pag.jpg', '3', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-05-02 11:18:59', '2018-05-02 11:18:59', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('14', '2', '0', '3', '../../images/pag.jpg', '3', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-05-02 11:19:07', '2018-05-02 11:19:07', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('15', '2', '0', '33', '../../images/pag.jpg', '33', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-05-02 11:19:25', '2018-05-02 11:19:25', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('16', '2', '0', '111', '../../images/pag.jpg', '111', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-05-02 11:26:06', '2018-05-02 11:26:06', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('17', '2', '0', '111', '../../images/pag.jpg', '111', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-05-02 11:26:15', '2018-05-02 11:26:15', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('18', '2', '0', '66', '../../images/pag.jpg', '66', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-05-02 11:42:46', '2018-05-02 11:42:46', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('19', '2', '0', 'ww', '../../images/pag.jpg', 'www', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-05-02 11:44:27', '2018-05-02 11:44:27', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('20', '2', '0', '111', '../../images/pag.jpg', '111', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-05-03 11:10:41', '2018-05-03 11:10:41', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('21', '2', '0', '111', '../../images/pag.jpg', '111', 'asdasdasd', '1', '1', '0000-00-00 00:00:00', '2018-05-03 11:11:22', '2018-05-03 11:11:22', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('22', '2', '0', '2', '../../images/pag.jpg', '2', '[{\"type\":\"2\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-03 13:14:51', '2018-05-03 13:14:51', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('23', '2', '0', '2', '../../images/pag.jpg', '2', '[{\"type\":\"2\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-03 13:15:30', '2018-05-03 13:15:30', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('24', '2', '0', '2', '../../images/pag.jpg', '2', '[]', '1', '1', '0000-00-00 00:00:00', '2018-05-03 16:30:15', '2018-05-03 16:30:15', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('25', '2', '0', '1', '../../images/pag.jpg', '1', '[]', '1', '1', '0000-00-00 00:00:00', '2018-05-03 16:46:08', '2018-05-03 16:46:08', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('26', '2', '0', '22', '../../images/pag.jpg', '22', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/e0roWP8Y4dLmKByh7HSuzfIR5XEOskMC.png\"},{\"type\":\"words\",\"content\":\"3\"},{\"type\":\"words\",\"content\":\"2\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-03 20:49:36', '2018-05-03 20:49:36', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('27', '2', '0', '22', '../../images/pag.jpg', '22', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/e0roWP8Y4dLmKByh7HSuzfIR5XEOskMC.png\"},{\"type\":\"words\",\"content\":\"3\"},{\"type\":\"words\",\"content\":\"2\"},{\"type\":\"words\",\"content\":\"3\"},{\"type\":\"words\",\"content\":\"2\"},{\"type\":\"words\",\"content\":\"2\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-03 20:49:48', '2018-05-03 20:49:48', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('28', '2', '0', '2', '../../images/pag.jpg', '2', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/syBqmGpgzw7rTLNF6MnVduo9H1cUa4k2.png\"},{\"type\":\"words\",\"content\":\"a\"},{\"type\":\"words\",\"content\":\"c\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-03 20:50:10', '2018-05-03 20:50:10', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('29', '2', '0', '1', '../../images/pag.jpg', '1', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/94RFzeW7yVqugJxt02pDBKMGlh1865Ha.png\"},{\"type\":\"words\",\"content\":\"2\"},{\"type\":\"video\",\"content\":\"3\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-03 20:54:56', '2018-05-03 20:54:56', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('30', '2', '0', '2', '../../images/pag.jpg', '2', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/EtOXV1S9qj3d0avDowF5lyUI8T4iRxeG.png\"},{\"type\":\"words\",\"content\":\"1\"},{\"type\":\"video\",\"content\":\"3\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-03 20:55:30', '2018-05-03 20:55:30', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('31', '2', '0', '1', 'https://wxappres.feeyan.com/film/2018/05/5Az2HiZaC6rUTc1wOLbWRYyqGPd8vjKJ.png', '1', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/CFI3D67rOdqSXe9BPvpcxQ5UybATuzJa.png\"},{\"type\":\"words\",\"content\":\"22\"},{\"type\":\"video\",\"content\":\"33\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-03 21:21:40', '2018-05-03 21:21:40', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('32', '2', '0', '影视公众号', 'https://wxappres.feeyan.com/film/2018/05/h86itPkECRjHDnwFW5NfGx7vJ1spqlMr.png', '飞燕小程序', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/L45Mw1jTyi8UaCxnXlmSh9VGeWtIg2PY.png\"},{\"type\":\"words\",\"content\":\"22\"},{\"type\":\"words\",\"content\":\"33\"},{\"type\":\"video\",\"content\":\"https://v.qq.com/x/page/g06437jm4m2.html\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-03 21:55:12', '2018-05-03 21:55:12', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('33', '2', '0', '555', 'https://wxappres.feeyan.com/film/2018/05/lMeiVsXj93Ak2a1ndxSm8EZwIGO5R4WF.png', '555', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/d2tzPQZH60pYSiAvqja783rsl9uC4LDX.png\"},{\"type\":\"words\",\"content\":\"33\"},{\"type\":\"video\",\"content\":\"<iframe frameborder=\\\"0\\\" width=\\\"640\\\" height=\\\"498\\\" src=\\\"https://v.qq.com/iframe/player.html?vid=j0643w7b7ho&tiny=0&auto=0\\\" allowfullscreen></iframe>\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-03 22:00:01', '2018-05-03 22:00:01', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('34', '2', '0', '1测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('35', '2', '0', '2测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('36', '2', '0', '3测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('37', '2', '0', '4测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('38', '2', '0', '5测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('39', '2', '0', '6测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('40', '2', '0', '7测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('41', '2', '0', '8测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('42', '2', '0', '9测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('43', '2', '0', '10测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('44', '2', '0', '11测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('45', '2', '0', '12测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('46', '2', '0', '13测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('47', '2', '0', '14测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('48', '2', '0', '15测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('49', '2', '0', '16测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('50', '2', '0', '测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('51', '2', '0', '测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('52', '2', '0', '测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('53', '2', '0', '测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('54', '2', '0', '测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('55', '2', '0', '测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('56', '2', '0', '测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('57', '2', '0', '测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('58', '2', '0', '测试标题', 'https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png', '测试作者', '[{\"type\":\"words\",\"content\":\"xxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"},{\"type\":\"words\",\"content\":\"xxxxx\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/04/bRZ5jd3iBm2CM4sHxShUGlaoIfcnkVQO.png\"}]', '1', '5', '0000-00-00 00:00:00', '2018-04-23 17:35:27', '2018-04-23 17:35:27', '2018-04-21 18:04:48', '1');
INSERT INTO `film_news` VALUES ('59', '0', '0', '测试资讯', 'https://wxappres.feeyan.com/film/2018/05/zwUqP3DC9aSvf72nQ4BZdehEVYlKWkIp.png', '测试', '[{\"type\":\"words\",\"content\":\"\\u6d4b\\u8bd5\\u5185\\u5bb9\"},{\"type\":\"video\",\"content\":\"https:\\/\\/wxappres.feeyan.com\\/film\\/2018\\/05\\/YpoKhBnAGkTFt4Nc86WrS395d1vumOeL.mp4\"}]', '1', '5', '0000-00-00 00:00:00', '2018-05-04 13:19:54', '2018-05-04 21:43:09', '2018-05-04 21:43:00', '2');
INSERT INTO `film_news` VALUES ('60', '15', '0', '这个隐形防盗的贴身枪包太实用，收到的男生都喜欢哭了', 'https://wxappres.feeyan.com/film/2018/05/yNf04lwcS23ZeKqspb7V5hBgYHmICM89.png', '阿糖', '[{\"type\":\"pic\",\"content\":\"https:\\/\\/wxappres.feeyan.com\\/film\\/2018\\/05\\/krVyKdFRhn8zBfi2HU6A7QL3pltGw5sW.png\"},{\"type\":\"words\",\"content\":\"\\u6bcf\\u4e2a\\u7537\\u4eba\\u5fc3\\u4e2d\\u90fd\\u6709\\u4e00\\u4e2a\\u201c\\u67aa\\u706b\\u68a6\\u201d\\uff0c\\u8170\\u85cf\\u5229\\u5668\\uff0c\\u8def\\u89c1\\u4e0d\\u5e73\\u62d4\\u67aa\\u76f8\\u5411\\uff0c\\u6c5f\\u6e56\\u4e2d\\u6740\\u51fa\\u4e00\\u6761\\u8840\\u8def\\uff1b\\u800c\\u6bcf\\u4e2a\\u5973\\u4eba\\u5fc3\\u4e2d\\uff0c\\u4e5f\\u90fd\\u6709\\u4e00\\u4e2a\\u968f\\u65f6\\u968f\\u5730\\u4e3a\\u5979\\u638f\\u67aa\\u7684\\u76d6\\u4e16\\u82f1\\u96c4~~  \\r\\n\\r\\n\\r\\n\\u4e0d\\u8fc7\\u5728\\u5c0f\\u7f16\\u770b\\u6765\\uff0c\\u548c\\u5e73\\u5e74\\u4ee3\\u8fd8\\u662f\\u4e0d\\u8981\\u60f3\\u7740\\u638f\\u67aa\\u638f\\u70ae\\u5566\\uff0c\\u4ece\\u8170\\u95f4\\u638f\\u638f\\u624b\\u673a\\u638f\\u638f\\u94b1\\u5305\\u4e5f\\u662f\\u53ef\\u4ee5\\u5f88\\u5e05\\u7684\\u5462~\\r\\n\\r\\n\\r\\n\\u5f53\\u7136\\u8bdd\\u53c8\\u8bf4\\u56de\\u6765\\uff0c\\u8c01\\u8fd8\\u6ca1\\u4e2a\\u70ed\\u8840\\u68a6\\uff1f\\u867d\\u7136\\u68a6\\u60f3\\u5230\\u73b0\\u5b9e\\u7684\\u8ddd\\u79bb\\u603b\\u662f\\u65e0\\u6cd5\\u4e08\\u91cf\\uff0c\\u4f46\\u81f3\\u5c11\\u73b0\\u5728\\u53ef\\u4ee5\\u8ba9\\u4f60\\u62e5\\u6709\\u76f8\\u8fd1\\u7684\\u4f53\\u9a8c\\uff01\\u5e76\\u4e14\\u7edd\\u5bf9\\u5b89\\u5168~\\u65e0\\u516c\\u5bb3~\\r\\n\\r\\n\\r\\n \\u989d~\\u548c\\u8c10\\u793e\\u4f1a\\uff0c\\u6211\\u662f\\u8bf4\\uff0c\\u6709\\u90a3\\u4e48\\u4e2aFeel\\u5c31\\u597d~\\u73b0\\u5728\\u5c31\\u6709\\u8fd9\\u4e48\\u4e00\\u4e2a\\u67aa\\u5305\\uff0c\\u6700\\u5927\\u9650\\u5ea6\\u7684\\u63a5\\u8fd1\\u4f60\\u4eec\\u7684\\u68a6\\u60f3\\uff01\"},{\"type\":\"urlVideo\",\"content\":\"<iframe frameborder=\"}]', '1', '0', '0000-00-00 00:00:00', '2018-05-04 14:49:24', '2018-05-04 21:43:02', '2018-05-04 21:43:00', '1');
INSERT INTO `film_news` VALUES ('61', '2', '0', '555a', 'https://wxappres.feeyan.com/film/2018/05/brFTx3oMz2AqjfZKSE8stLehPgavBlJN.png', '555a', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/o6Qf0yLsCEeGuF4gxZzcBWnMR51XKmSY.png\"},{\"type\":\"words\",\"content\":\"asdasd\"},{\"type\":\"video\",\"content\":\"adsasd\"},{\"type\":\"words\",\"content\":\"asdasd\"},{\"type\":\"video\",\"content\":\"adsasd\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-04 15:47:51', '2018-05-04 15:47:51', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('62', '2', '0', '555', 'https://wxappres.feeyan.com/film/2018/05/lMeiVsXj93Ak2a1ndxSm8EZwIGO5R4WF.png', '555', '[]', '1', '1', '0000-00-00 00:00:00', '2018-05-04 15:47:58', '2018-05-04 15:47:58', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('63', '15', '0', '22', 'https://wxappres.feeyan.com/film/2018/05/3VHtPCG58KAEmoMOz9YFvwTuykXndQpR.png', '王亚楠', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/W7jqNVcr4dFEx8Lo9UZnpGsyKQaHbfz3.png\"},{\"type\":\"words\",\"content\":\"今天是周五\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-04 17:53:57', '2018-05-04 17:53:57', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('64', '15', '0', '123456', 'https://wxappres.feeyan.com/film/2018/05/G3k7pZWXzSnMRPNFCcAs2rU460oqmaDQ.jpg', '啊啊啊啊啊啊啊', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/GsoEeNv7S8rRpCtzO036JhkDHq4YaAcU.jpg\"},{\"type\":\"words\",\"content\":\"阿达的\"},{\"type\":\"video\",\"content\":\"阿达的\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-04 18:04:24', '2018-05-04 18:04:24', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('65', '0', '0', '这个隐形防盗的贴身枪包太实用，收到的男生都喜欢哭了', 'https://wxappres.feeyan.com/film/2018/05/UPq2wz17GJc4ANFrtnlvx658IL9hk3Ys.png', '阿糖测试', '[{\"type\":\"words\",\"content\":\"123\"}]', '1', '5', '0000-00-00 00:00:00', '2018-05-04 18:04:45', '2018-05-04 21:42:15', '2018-05-04 21:42:00', '2');
INSERT INTO `film_news` VALUES ('66', '15', '0', '5', 'https://wxappres.feeyan.com/film/2018/05/Uu1D0gcCoadLs4Y5Xh6qbz7yReOAHv8r.png', '555', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/oz1ICLWMN3lx4qESrQdsDhiw2ZvguRf6.png\"},{\"type\":\"words\",\"content\":\"55\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-04 19:38:13', '2018-05-04 19:38:13', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('67', '15', '0', '44', 'https://wxappres.feeyan.com/film/2018/05/exr4ZtBVaq2Wo3OsAwmz1SyUiHLgPYGd.png', '44', '[{\"type\":\"words\",\"content\":\"55555\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/5WiumE2rhLalc7yMqB4fw1D0kOCSTUY6.png\"},{\"type\":\"words\",\"content\":\"55555\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-04 19:58:55', '2018-05-04 19:58:55', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('68', '15', '0', '啊啊啊啊啊啊', 'https://wxappres.feeyan.com/film/2018/05/e8T0AJHKDCS4Nn27XswqmiYRdWL65pkl.png', '啊啊啊', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/9guU6vdpPI8WafzheboZjwVi02BqyN4c.png\"},{\"type\":\"words\",\"content\":\"qq\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-04 20:06:56', '2018-05-04 20:06:56', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('69', '15', '0', 'q', 'https://wxappres.feeyan.com/film/2018/05/RK15UunTsOdaFZciVMSyQDfLCeXzv3bN.png', '去', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/bGON9vrYkehmxE6IFSqVcU1fa43pBX2w.png\"},{\"type\":\"words\",\"content\":\"\"},{\"type\":\"words\",\"content\":\"\"},{\"type\":\"words\",\"content\":\"q\"},{\"type\":\"words\",\"content\":\"q\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-04 20:07:17', '2018-05-04 20:07:17', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('70', '15', '0', '5', 'https://wxappres.feeyan.com/film/2018/05/HagBJpT51Dz3ckM4WVZb8qNidS9YFjtf.png', '545', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/jZm9U7luRLc4pVEwCW1e5Ba8MYFvyXJt.png\"},{\"type\":\"words\",\"content\":\"4\"},{\"type\":\"video\",\"content\":\"4\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-04 20:07:55', '2018-05-04 20:07:55', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('71', '15', '0', '123', 'https://wxappres.feeyan.com/film/2018/05/64pWAeLbKPwRVyZdsf0q8h72xUNBEtYu.jpg', '你好', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/ODMghFvBiZuIYEpCstlz2a6mNydjTbA3.jpg\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/aOjI9YoFgXluKebwA0BZiQqHsMxUzN84.jpeg\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/WUnmJD8G7dT4NlAox9kIYLBKi5sRrb0u.jpg\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/yVKMJUDQZ5mp1BP87iOs6CSEcbxgteGz.jpg\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/OTuC6tGi7dnA2URcDQaK4Np1MIxwqlVr.jpg\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/8Y3xA7lSdPEuXw5Lp9nyZMRI0DbifrvN.jpg\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/lcTEJugMot9PA3znSK7emIWsikxaqVY2.jpeg\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/EjWau6w0K8NO9ISeiosqLmAHQtUkgRY1.jpg\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/cMxtpBYvRy4HoO7l53XUjdZsPAiF9w6T.jpg\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-05 15:49:44', '2018-05-05 15:49:44', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('72', '15', '0', '飞燕小程序', 'https://wxappres.feeyan.com/film/2018/05/B1QNXux2wo9HaSt43zdZ0CmDEPk8cIgT.jpg', '你好', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/UKrNgvmPhVbXHj46a8YyFGlTeEnWSq2B.jpg\"},{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/XKcky8bm0oBzEx51wf9RMO3gruVCL4tD.jpg\"},{\"type\":\"words\",\"content\":\"北京欢迎你\"}]', '1', '0', '2018-05-09 15:23:25', '2018-05-05 15:54:12', '2018-05-05 15:54:12', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('73', '15', '0', '1', 'https://wxappres.feeyan.com/film/2018/05/Cu8G4vLPB3OIdwYUzhMcDSRi65psmAoj.png', '1', '[]', '1', '1', '0000-00-00 00:00:00', '2018-05-05 16:11:56', '2018-05-05 16:11:56', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('74', '0', '0', '测试5.7', 'https://wxappres.feeyan.com/film/2018/05/kNEGevxRWY28uXJbMylgnwFpqSAmzoBf.png', '123', '[{\"type\":\"words\",\"content\":\"123\"}]', '1', '2', '2018-05-09 15:21:32', '2018-05-07 17:58:41', '2018-05-07 17:58:50', '2018-05-07 17:58:00', '2');
INSERT INTO `film_news` VALUES ('75', '15', '0', '333333', 'https://wxappres.feeyan.com/film/2018/05/g7SWcjYNvnix6kpBKqJEeMFyI2R0P1uV.png', '22223', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/Qq7nZY3LD6RGrW98cExyb4TIUVX2hiBN.png\"},{\"type\":\"urlVideo\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/ZzBL9nFu4xpveUTXhCRNP7JHKQDkom3f.mp4\"},{\"type\":\"words\",\"content\":\"大家好\"},{\"type\":\"video\",\"content\":\"567890\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-07 19:51:46', '2018-05-07 19:51:46', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('76', '15', '0', '6666', 'https://wxappres.feeyan.com/film/2018/05/DAzLKxlS6wbd1XhGJINP5Mep8ngCsHoT.png', '66666', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/YUPOEo7SIqxQXfrBmyJcCenW8HG0u9p3.png\"},{\"type\":\"urlVideo\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/wo9vY5XGqrMJPFWn86aUS4ANeiThI3BZ.mp4\"},{\"type\":\"words\",\"content\":\"66666\"},{\"type\":\"video\",\"content\":\"6666666666666666666666666666\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-09 14:12:34', '2018-05-09 14:12:34', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('77', '15', '0', '88888', 'https://wxappres.feeyan.com/film/2018/05/ULcRvyxmYJMpWkj2NX7B6uPSFnboQIOq.png', '88888', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/fE5y7ki38uKw6eapGoOJc0LjYVdZgCWU.png\"},{\"type\":\"video\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/sqI4J6mQy7NcMC8dPwF3kXpU5BSAZnHe.mp4\"},{\"type\":\"words\",\"content\":\"88888888888888\"},{\"type\":\"urlVideo\",\"content\":\"8888888888888888888\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-09 14:15:35', '2018-05-09 14:15:35', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('78', '15', '0', '我', '../../images/a11.png', '啊', '[{\"type\":\"words\",\"content\":\"测试\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-11 16:24:28', '2018-05-11 16:24:28', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('79', '15', '0', '6', '../../images/a11.png', '6', '[{\"type\":\"words\",\"content\":\"6\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-11 16:41:46', '2018-05-11 16:41:46', '0000-00-00 00:00:00', '1');
INSERT INTO `film_news` VALUES ('80', '15', '0', '1', 'https://wxappres.feeyan.com/film/2018/05/68cufICaSMEwsTpjhy5ODeKUPA4ovxdi.png', '1', '[{\"type\":\"words\",\"content\":\"1\"}]', '1', '5', '2018-05-11 17:32:03', '2018-05-11 16:58:15', '2018-05-11 16:58:15', '2018-05-11 17:32:03', '1');
INSERT INTO `film_news` VALUES ('81', '15', '0', '这是一个公司的名字', 'https://wxappres.feeyan.com/film/2018/05/EVQqrOykCMwifFptmx0H9BuhcNg72loz.jpg', '我的公司', '[{\"type\":\"pic\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/nMYCiluw4gkSzWyER29vaG1K7x8DFrpZ.jpg\"},{\"type\":\"video\",\"content\":\"https://wxappres.feeyan.com/film/2018/05/dLSub5TnDtWcMesx3CJFvf02oipRhBPE.mp4\"},{\"type\":\"words\",\"content\":\"文字来测试的\"}]', '1', '1', '0000-00-00 00:00:00', '2018-05-11 18:09:40', '2018-05-11 18:09:40', '0000-00-00 00:00:00', '1');

-- ----------------------------
-- Table structure for film_news_audit_log
-- ----------------------------
DROP TABLE IF EXISTS `film_news_audit_log`;
CREATE TABLE `film_news_audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_id` int(11) NOT NULL DEFAULT '0' COMMENT '资讯id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '审核人id',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1一审，2二审，3三审',
  `reason` varchar(500) NOT NULL DEFAULT '' COMMENT '未通过原因',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COMMENT='资讯审核记录';

-- ----------------------------
-- Records of film_news_audit_log
-- ----------------------------
INSERT INTO `film_news_audit_log` VALUES ('1', '74', '16', '2', '不通过', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('2', '72', '15', '3', '不通过', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('3', '1', '15', '3', '333', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('4', '1', '15', '3', '666', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('5', '1', '15', '3', '8888', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('6', '1', '15', '3', '454t', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('7', '1', '15', '3', '454t', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('8', '1', '15', '3', '454t', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('9', '1', '15', '3', '66', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('10', '1', '15', '3', '66', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('11', '1', '15', '3', '88', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('12', '1', '15', '3', '鹅鹅鹅啊', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('13', '1', '15', '3', '88', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `film_news_audit_log` VALUES ('14', '1', '15', '3', '555555', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for film_question
-- ----------------------------
DROP TABLE IF EXISTS `film_question`;
CREATE TABLE `film_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` text NOT NULL COMMENT '问题',
  `start_time` datetime NOT NULL COMMENT '开始时间',
  `end_time` datetime NOT NULL COMMENT '结束时间',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='问卷列表';

-- ----------------------------
-- Records of film_question
-- ----------------------------
INSERT INTO `film_question` VALUES ('1', '{\"q_1\":{\"type\":\"2\",\"q\":\"14444\",\"a\":\"1\\r\\n1444\\r\\n1\"}}', '2018-04-11 11:47:20', '2018-04-12 11:47:28', '0', '2018-04-10 18:19:52', '2018-04-11 12:21:05');
INSERT INTO `film_question` VALUES ('3', '{\"q_1\":{\"type\":\"2\",\"q\":\"2222\",\"a\":\"2\\r\\n2\"},\"q_2\":{\"type\":\"2\",\"q\":\"4\",\"a\":\"4\\r\\n5\"}}', '2018-04-11 00:00:00', '2018-04-12 00:00:00', '0', '2018-04-10 20:20:37', '2018-04-11 12:22:49');
INSERT INTO `film_question` VALUES ('4', '{\"q_1\":{\"type\":\"2\",\"q\":\"2222\",\"a\":\"2\\r\\n2\"},\"q_2\":{\"type\":\"2\",\"q\":\"4\",\"a\":\"4\\r\\n5\"}}', '2018-04-11 00:00:00', '2018-04-12 00:00:00', '0', '2018-04-10 20:20:37', '2018-04-11 12:25:28');
INSERT INTO `film_question` VALUES ('5', '{\"q_1\":{\"type\":\"2\",\"q\":\"2222\",\"a\":\"2\\r\\n2\"},\"q_2\":{\"type\":\"2\",\"q\":\"4\",\"a\":\"4\\r\\n5\"}}', '2018-04-11 00:00:00', '2018-04-12 00:00:00', '1', '2018-04-10 20:20:37', '2018-04-11 11:40:53');
INSERT INTO `film_question` VALUES ('6', '{\"q_1\":{\"type\":\"2\",\"q\":\"2222\",\"a\":\"2\\r\\n2\"},\"q_2\":{\"type\":\"2\",\"q\":\"4\",\"a\":\"4\\r\\n5\"}}', '2018-04-11 00:00:00', '2018-04-12 00:00:00', '1', '2018-04-10 20:20:37', '2018-04-11 11:40:53');
INSERT INTO `film_question` VALUES ('7', '{\"q_1\":{\"q\":\"d\",\"a\":\"d\\r\\nd\",\"type\":\"1\"}}', '2018-04-12 10:17:52', '2018-04-13 10:17:59', '1', '2018-04-12 10:17:18', '2018-04-12 10:17:18');
INSERT INTO `film_question` VALUES ('8', '{\"q_1\":{\"type\":\"2\",\"q\":\"多选题\",\"a\":\"选项1\\r\\n选项2\"},\"q_2\":{\"type\":\"1\",\"q\":\"单选题\",\"a\":\"选项1\\r\\n选项2\"},\"q_3\":{\"type\":\"2\",\"q\":\"多选题\",\"a\":\"选项1\\r\\n选项2\"},\"q_4\":{\"type\":\"3\",\"q\":\"你最喜欢哪个明星？\"},\"q_5\":{\"type\":\"1\",\"q\":\"单选题\",\"a\":\"选项1\\r\\n选项2\"}}', '2018-04-22 00:00:00', '2018-04-29 00:00:00', '1', '2018-04-12 10:20:21', '2018-04-12 10:20:21');
INSERT INTO `film_question` VALUES ('9', '{\"q_1\":{\"q\":\"\\u5355\\u9009\\u6d4b\\u8bd5\",\"a\":\"\\u90091\\r\\n\\u90092\\r\\n\\u90093\",\"type\":\"1\"},\"q_2\":{\"q\":\"\\u591a\\u9009\\u6d4b\\u8bd5\",\"a\":\"\\u90091\\r\\n\\u90092\\r\\n\\u90093\\r\\n\",\"type\":\"2\"},\"q_3\":{\"q\":\"\\u586b\\u7a7a\\u9898\\u6d4b\\u8bd5\",\"type\":\"3\"}}', '2018-04-27 00:00:00', '2018-05-02 00:00:00', '1', '2018-04-27 20:18:34', '2018-04-27 20:18:34');
INSERT INTO `film_question` VALUES ('10', '{\"q_1\":{\"type\":\"1\",\"q\":\"123\",\"a\":\"123\\r\\n123\\r\\n123\"}}', '2018-05-04 00:00:00', '2018-05-09 00:00:00', '1', '2018-05-04 15:35:16', '2018-05-07 13:56:53');

-- ----------------------------
-- Table structure for film_reserve
-- ----------------------------
DROP TABLE IF EXISTS `film_reserve`;
CREATE TABLE `film_reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) NOT NULL DEFAULT '0' COMMENT '专家id',
  `ename` varchar(255) NOT NULL DEFAULT '' COMMENT '专家名称',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '位置',
  `intro` varchar(500) NOT NULL DEFAULT '' COMMENT '简介',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COMMENT='预约列表';

-- ----------------------------
-- Records of film_reserve
-- ----------------------------
INSERT INTO `film_reserve` VALUES ('2', '1', 'd', 'd', 'd', 'fdgsdfgsfg', '1', '2018-04-16 14:23:17', '2018-04-16 14:23:17');
INSERT INTO `film_reserve` VALUES ('3', '1', 'd', 'd', 'd', 'fdgsdfgsfg', '1', '2018-04-16 14:37:03', '2018-04-16 14:37:03');
INSERT INTO `film_reserve` VALUES ('4', '1', 'd', '3', '3', '测试顶顶顶顶顶', '1', '2018-04-16 16:02:29', '2018-04-19 11:24:15');
INSERT INTO `film_reserve` VALUES ('5', '1', 'd', '4', '4', 'dffdfddfd', '1', '2018-04-16 16:12:04', '2018-04-16 21:07:32');
INSERT INTO `film_reserve` VALUES ('6', '2', '测试', '测试标题', '顶顶顶顶', '打发打发', '1', '2018-04-19 11:12:58', '2018-04-19 11:12:58');
INSERT INTO `film_reserve` VALUES ('7', '1', '专家1', '专家一 测试标题', '专家一 测试地址', '测试简介', '1', '2018-04-23 10:57:53', '2018-04-23 10:57:53');
INSERT INTO `film_reserve` VALUES ('8', '2', '专家2', '专家2 测试标题', '专家2 测试地址', '测测测试简介', '1', '2018-04-23 10:57:53', '2018-04-23 10:57:53');
INSERT INTO `film_reserve` VALUES ('9', '3', 'Amber Zhang', '区块链在影视行业的应用402室', '北京市三里屯通盈酒店4层402室402室', '区块链在影视行业的应用\r\n区块链在影视行业的应用\r\n区块链在影视行业的应用\r\n区块链在影视行业的应用\r\n区块链在影视行业的应用\r\n区块链在影视行业的应用\r\n区块链在影视行业的应用', '1', '2018-05-04 11:59:49', '2018-05-07 16:29:52');
INSERT INTO `film_reserve` VALUES ('10', '2', '专家2', '1', '1', '1', '1', '2018-05-04 21:18:56', '2018-05-04 21:18:56');
INSERT INTO `film_reserve` VALUES ('11', '3', 'Amber Zhang', '测试测试5.7', '北京市朝阳区北美国际商务中心B座', '123', '1', '2018-05-07 17:47:24', '2018-05-07 17:47:24');

-- ----------------------------
-- Table structure for film_reserve_times
-- ----------------------------
DROP TABLE IF EXISTS `film_reserve_times`;
CREATE TABLE `film_reserve_times` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL DEFAULT '0' COMMENT '预约列表id',
  `eid` int(11) NOT NULL DEFAULT '0' COMMENT '专家',
  `ename` varchar(32) NOT NULL DEFAULT '' COMMENT '专家名称',
  `rDate` date NOT NULL COMMENT '可约日期',
  `sTime` time NOT NULL COMMENT '开始时间',
  `eTime` time NOT NULL COMMENT '结束时间',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '预约人id',
  `confirm_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '预约提交时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0删除， 1可约，2已预约',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `contact` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人',
  `phone` varchar(32) NOT NULL DEFAULT '' COMMENT '电话',
  `cname` varchar(255) NOT NULL DEFAULT '' COMMENT '公司名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COMMENT='可约时间段';

-- ----------------------------
-- Records of film_reserve_times
-- ----------------------------
INSERT INTO `film_reserve_times` VALUES ('1', '2', '1', 'd', '2018-04-16', '08:00:00', '09:00:00', '2', '2018-04-23 20:23:12', '2', '0000-00-00 00:00:00', '2018-04-23 20:23:12', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('2', '2', '1', 'd', '2018-04-16', '10:00:00', '11:30:00', '2', '2018-04-23 20:22:30', '2', '0000-00-00 00:00:00', '2018-04-23 20:22:30', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('3', '2', '1', 'd', '2018-04-16', '14:00:00', '16:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:31:21', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('4', '2', '1', 'd', '2018-04-17', '09:00:00', '10:30:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:31:21', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('5', '2', '1', 'd', '2018-04-17', '11:00:00', '14:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:31:21', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('6', '2', '1', 'd', '2018-04-17', '17:00:00', '18:30:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:31:21', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('7', '2', '1', 'd', '2018-04-18', '10:00:00', '11:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:31:21', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('8', '2', '1', 'd', '2018-04-18', '13:00:00', '14:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:31:21', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('9', '2', '1', 'd', '2018-04-18', '15:00:00', '17:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:31:21', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('16', '3', '1', 'd', '2018-04-16', '08:00:00', '09:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:29:23', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('17', '3', '1', 'd', '2018-04-16', '10:00:00', '11:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:29:23', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('18', '3', '1', 'd', '2018-04-16', '14:00:00', '15:00:00', '2', '0000-00-00 00:00:00', '2', '0000-00-00 00:00:00', '2018-04-19 11:29:23', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('19', '3', '1', 'd', '2018-04-17', '08:00:00', '09:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:29:23', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('20', '3', '1', 'd', '2018-04-17', '10:00:00', '11:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:29:23', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('21', '3', '1', 'd', '2018-04-17', '12:00:00', '13:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:29:23', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('22', '3', '1', 'd', '2018-04-18', '08:00:00', '09:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:29:23', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('23', '3', '1', 'd', '2018-04-18', '11:00:00', '13:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:29:23', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('24', '3', '1', 'd', '2018-04-18', '14:00:00', '15:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:29:23', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('31', '4', '2', 'd', '2018-04-19', '11:45:00', '12:45:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:24:25', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('32', '4', '2', 'd', '2018-04-19', '14:00:00', '15:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:24:25', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('33', '4', '2', 'd', '2018-04-30', '08:00:00', '09:00:00', '16', '2018-05-04 19:14:57', '2', '0000-00-00 00:00:00', '2018-05-04 19:14:57', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('34', '4', '2', 'd', '2018-04-30', '10:00:00', '11:00:00', '15', '2018-05-04 19:13:00', '2', '0000-00-00 00:00:00', '2018-05-04 19:13:00', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('35', '4', '2', 'd', '2018-04-30', '14:00:00', '15:00:00', '0', '2018-05-11 18:25:36', '1', '0000-00-00 00:00:00', '2018-05-11 18:25:36', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('36', '4', '2', 'd', '2018-05-01', '08:00:00', '09:00:00', '16', '2018-05-04 19:15:30', '2', '0000-00-00 00:00:00', '2018-05-04 19:15:30', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('37', '4', '2', 'd', '2018-05-01', '14:00:00', '15:00:00', '16', '2018-05-04 19:19:26', '2', '0000-00-00 00:00:00', '2018-05-04 19:19:26', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('38', '5', '2', 'd', '2018-04-17', '12:00:00', '13:00:00', '0', '0000-00-00 00:00:00', '1', '0000-00-00 00:00:00', '2018-04-19 11:22:18', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('39', '5', '2', 'd', '2018-04-17', '14:00:00', '15:00:00', '0', '0000-00-00 00:00:00', '1', '2018-04-16 21:10:02', '2018-04-19 11:22:18', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('40', '5', '2', 'd', '2018-04-18', '11:30:00', '13:00:00', '0', '0000-00-00 00:00:00', '1', '2018-04-16 21:29:28', '2018-04-19 11:22:18', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('41', '6', '2', '测试', '2018-04-19', '11:30:00', '12:00:00', '2', '2018-04-20 15:06:52', '1', '2018-04-19 11:12:58', '2018-04-20 15:06:52', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('42', '7', '1', '专家1', '2018-04-23', '11:00:00', '12:00:00', '2', '2018-04-27 20:14:59', '2', '2018-04-23 10:57:54', '2018-04-27 20:14:59', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('43', '7', '1', '专家1', '2018-04-23', '13:00:00', '14:00:00', '2', '2018-04-26 13:31:52', '2', '2018-04-23 10:57:54', '2018-04-26 13:31:52', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('44', '7', '1', '专家1', '2018-04-23', '14:15:00', '15:00:00', '2', '2018-04-27 20:15:39', '2', '2018-04-23 10:57:54', '2018-04-27 20:15:39', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('45', '7', '1', '专家1', '2018-04-24', '08:00:00', '09:00:00', '2', '2018-04-24 13:40:27', '2', '2018-04-23 10:57:54', '2018-04-24 13:40:27', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('46', '7', '1', '专家1', '2018-04-24', '10:00:00', '11:00:00', '2', '2018-04-27 20:36:29', '2', '2018-04-23 10:57:54', '2018-04-27 20:36:29', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('47', '7', '1', '专家1', '2018-04-24', '14:00:00', '15:00:00', '2', '2018-04-27 20:29:37', '2', '2018-04-23 10:57:54', '2018-04-27 20:29:37', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('48', '7', '1', '专家1', '2018-04-24', '15:15:00', '16:00:00', '2', '2018-04-27 20:19:09', '2', '2018-04-23 10:57:54', '2018-04-27 20:19:09', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('49', '7', '1', '专家1', '2018-04-25', '08:00:00', '09:00:00', '2', '2018-04-27 20:54:34', '2', '2018-04-23 10:57:54', '2018-04-27 20:54:34', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('50', '7', '1', '专家1', '2018-04-25', '14:00:00', '15:00:00', '2', '2018-04-27 20:55:08', '2', '2018-04-23 10:57:54', '2018-04-27 20:55:08', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('51', '7', '1', '专家1', '2018-04-26', '08:00:00', '10:00:00', '0', '0000-00-00 00:00:00', '1', '2018-04-23 10:57:54', '2018-04-23 10:57:54', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('52', '7', '1', '专家1', '2018-04-26', '14:00:00', '15:00:00', '2', '2018-04-27 20:58:11', '2', '2018-04-23 10:57:54', '2018-04-27 20:58:11', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('53', '7', '1', '专家1', '2018-04-27', '09:00:00', '11:00:00', '0', '0000-00-00 00:00:00', '1', '2018-04-23 10:57:54', '2018-04-23 10:57:54', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('54', '7', '1', '专家1', '2018-04-27', '14:00:00', '15:00:00', '0', '0000-00-00 00:00:00', '1', '2018-04-23 10:57:54', '2018-04-23 10:57:54', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('55', '7', '1', '专家1', '2018-04-27', '16:00:00', '17:00:00', '0', '0000-00-00 00:00:00', '1', '2018-04-23 10:57:54', '2018-04-23 10:57:54', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('68', '8', '2', '专家2', '2018-04-27', '16:00:00', '17:00:00', '2', '2018-04-27 20:56:19', '2', '2018-04-23 10:57:54', '2018-04-27 20:56:19', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('69', '8', '2', '专家2', '2018-04-27', '14:00:00', '15:00:00', '0', '0000-00-00 00:00:00', '1', '2018-04-23 10:57:54', '2018-04-23 10:57:54', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('70', '8', '2', '专家2', '2018-04-27', '09:00:00', '11:00:00', '2', '2018-04-27 20:57:29', '2', '2018-04-23 10:57:54', '2018-04-27 20:57:29', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('71', '8', '2', '专家2', '2018-04-26', '14:00:00', '15:00:00', '2', '2018-04-27 20:55:46', '2', '2018-04-23 10:57:54', '2018-04-27 20:55:46', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('72', '8', '2', '专家2', '2018-04-26', '08:00:00', '10:00:00', '0', '0000-00-00 00:00:00', '1', '2018-04-23 10:57:54', '2018-04-23 10:57:54', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('73', '8', '2', '专家2', '2018-04-25', '14:00:00', '15:00:00', '0', '2018-04-27 20:28:56', '1', '2018-04-23 10:57:54', '2018-04-27 20:28:56', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('74', '8', '2', '专家2', '2018-04-25', '08:00:00', '09:00:00', '0', '0000-00-00 00:00:00', '1', '2018-04-23 10:57:54', '2018-04-23 10:57:54', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('75', '8', '2', '专家2', '2018-04-24', '15:15:00', '16:00:00', '2', '2018-04-27 20:22:42', '2', '2018-04-23 10:57:54', '2018-04-27 20:22:42', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('76', '8', '2', '专家2', '2018-04-24', '14:00:00', '15:00:00', '2', '2018-04-27 20:24:50', '2', '2018-04-23 10:57:54', '2018-04-27 20:24:50', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('77', '8', '2', '专家2', '2018-04-24', '10:00:00', '11:00:00', '2', '2018-04-27 20:18:44', '2', '2018-04-23 10:57:54', '2018-04-27 20:18:44', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('78', '8', '2', '专家2', '2018-04-24', '08:00:00', '09:00:00', '2', '2018-04-27 20:16:29', '2', '2018-04-23 10:57:54', '2018-04-27 20:16:29', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('79', '8', '2', '专家2', '2018-04-23', '14:15:00', '15:00:00', '2', '2018-04-27 20:15:23', '2', '2018-04-23 10:57:54', '2018-04-27 20:15:23', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('80', '8', '2', '专家2', '2018-04-23', '13:00:00', '14:00:00', '2', '2018-04-24 13:35:50', '2', '2018-04-23 10:57:54', '2018-04-24 13:35:50', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('81', '8', '2', '专家2', '2018-04-23', '11:00:00', '12:00:00', '2', '2018-04-26 13:22:14', '2', '2018-04-23 10:57:54', '2018-04-26 13:22:14', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('82', '9', '3', 'Amber Zhang', '2018-05-05', '14:30:00', '15:30:00', '2', '2018-05-04 12:08:02', '2', '2018-05-04 11:59:49', '2018-05-04 14:28:17', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('83', '9', '3', 'Amber Zhang', '2018-05-05', '16:00:00', '17:00:00', '2', '2018-05-04 14:28:45', '2', '2018-05-04 11:59:49', '2018-05-04 14:28:45', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('84', '9', '3', 'Amber Zhang', '2018-05-05', '17:30:00', '18:30:00', '2', '2018-05-04 14:36:24', '2', '2018-05-04 11:59:49', '2018-05-04 14:36:24', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('85', '9', '3', 'Amber Zhang', '2018-05-04', '10:15:00', '11:15:00', '15', '2018-05-04 19:32:22', '2', '2018-05-04 17:17:46', '2018-05-04 19:32:22', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('86', '9', '3', 'Amber Zhang', '2018-05-04', '08:00:00', '09:00:00', '17', '2018-05-04 19:36:47', '2', '2018-05-04 19:33:37', '2018-05-04 19:41:35', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('87', '9', '3', 'Amber Zhang', '2018-05-04', '09:30:00', '10:30:00', '0', '2018-05-11 18:25:48', '1', '2018-05-04 19:33:37', '2018-05-11 18:25:48', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('88', '9', '3', 'Amber Zhang', '2018-05-07', '08:30:00', '09:30:00', '0', '2018-05-11 18:25:17', '1', '2018-05-04 21:17:58', '2018-05-11 18:25:17', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('89', '10', '2', '专家2', '2018-05-05', '21:00:00', '21:45:00', '0', '0000-00-00 00:00:00', '1', '2018-05-04 21:18:56', '2018-05-04 21:18:56', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('90', '9', '3', 'Amber Zhang', '2018-05-07', '10:00:00', '11:00:00', '0', '2018-05-11 18:26:02', '1', '2018-05-07 16:29:08', '2018-05-11 18:26:02', '王亚楠', '110', '飞燕小程序');
INSERT INTO `film_reserve_times` VALUES ('91', '9', '3', 'Amber Zhang', '2018-05-07', '15:00:00', '16:00:00', '0', '2018-05-11 18:21:57', '1', '2018-05-07 16:29:08', '2018-05-11 18:21:57', '问问', '111', '3333');
INSERT INTO `film_reserve_times` VALUES ('92', '11', '3', 'Amber Zhang', '2018-05-09', '09:30:00', '10:30:00', '0', '2018-05-11 18:25:28', '1', '2018-05-07 17:47:24', '2018-05-11 18:25:28', '', '', '');
INSERT INTO `film_reserve_times` VALUES ('93', '11', '3', 'Amber Zhang', '2018-05-09', '11:00:00', '12:00:00', '0', '2018-05-11 18:24:37', '1', '2018-05-07 17:47:24', '2018-05-11 18:24:37', '', '', '');

-- ----------------------------
-- Table structure for film_setting
-- ----------------------------
DROP TABLE IF EXISTS `film_setting`;
CREATE TABLE `film_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` varchar(255) NOT NULL DEFAULT '' COMMENT '小程序id',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '类型',
  `value` varchar(10) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
  `module` varchar(32) NOT NULL DEFAULT '' COMMENT '模块',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `brief` varchar(1000) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `appid` (`appid`(191))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of film_setting
-- ----------------------------
INSERT INTO `film_setting` VALUES ('1', '', '1', '', '', 'https://wxappres.feeyan.com/film/2018/05/FmSz4PhCXc57KUQBbGyatNerAup63fYM.png', '', '', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '');
INSERT INTO `film_setting` VALUES ('2', '', '2', '', '', '', '', '', '0', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '123', '123', '123');

-- ----------------------------
-- Table structure for film_user
-- ----------------------------
DROP TABLE IF EXISTS `film_user`;
CREATE TABLE `film_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `phone` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机号',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `unionid` varchar(32) NOT NULL DEFAULT '' COMMENT '微信唯一id',
  `openid` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '微信id',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '邮箱',
  `avatar` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '头像',
  `gender` tinyint(4) NOT NULL DEFAULT '0' COMMENT '性别',
  `usergroup` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否超级管理员',
  `admingroup` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否管理员',
  `city` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '城市',
  `province` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '省市',
  `country` varchar(255) NOT NULL DEFAULT '',
  `sign` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '个人签名',
  `session_key` varchar(255) NOT NULL DEFAULT '' COMMENT 'session',
  `expires_in` int(11) NOT NULL DEFAULT '0' COMMENT '有效期',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `cphone` varchar(255) NOT NULL DEFAULT '' COMMENT '公司联系电话',
  `contact` varchar(32) NOT NULL DEFAULT '' COMMENT '公司联系人',
  `cname` varchar(255) NOT NULL DEFAULT '' COMMENT '公司名',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '公司id',
  PRIMARY KEY (`uid`),
  KEY `openid` (`openid`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of film_user
-- ----------------------------
INSERT INTO `film_user` VALUES ('1', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '', '', '', '0');
INSERT INTO `film_user` VALUES ('2', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '1', '1', '1', '36');
INSERT INTO `film_user` VALUES ('3', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '13522179270', '王亚楠', '飞燕', '0');
INSERT INTO `film_user` VALUES ('4', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '11', '11', '11', '0');
INSERT INTO `film_user` VALUES ('5', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '33', '33', '33', '0');
INSERT INTO `film_user` VALUES ('6', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '22', '22', '22', '0');
INSERT INTO `film_user` VALUES ('7', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '1', '1', '1', '0');
INSERT INTO `film_user` VALUES ('8', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '13525896456', '张三', '飞燕小程序', '0');
INSERT INTO `film_user` VALUES ('9', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '13522785969', '张三', '飞燕小程序', '0');
INSERT INTO `film_user` VALUES ('10', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '13525589636', '张三', '飞燕小程序', '0');
INSERT INTO `film_user` VALUES ('11', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '110', '张三', '飞燕小程序', '0');
INSERT INTO `film_user` VALUES ('12', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '2', '2', '2', '0');
INSERT INTO `film_user` VALUES ('13', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '22222', '22222222', '22', '0');
INSERT INTO `film_user` VALUES ('14', '', '', '', '', '', '', '', '', '0', '0', '0', '', '', '', '', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '555', '555', '555', '0');
INSERT INTO `film_user` VALUES ('15', '', '', '', 'o7j4e1Qd0w-x_PyE48b1PIqEjft4', 'SkyLi', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83erUx8ZwPmIrP4C2EX9YF29iaOSRibGq7qzxYibH38Ukd5ibxo3a1Rpmkd3Mq5r5eogqwxoia718zo1kWug/132', '1', '0', '4', '', '莫斯科', '俄罗斯', '', '', '0', '2018-05-03 20:01:46', '2018-05-11 17:15:07', '1', '123', '申请红红火火', '测试', '56');
INSERT INTO `film_user` VALUES ('16', '', '', '', 'o7j4e1UeS1QHFKnbGrjI6qd3XrCE', '王亚楠', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIb7ZH9mUnN6tvu4VsYuTSkYDcoXn0bsdQXPmGEYQReHpOFQHpNeuZ7dpG9fw39Zz8ic1DGQ5dDsQQ/132', '2', '0', '1', '', '河南', '中国', '', '', '0', '2018-05-03 20:55:49', '2018-05-11 17:06:45', '1', '', '', '', '0');
INSERT INTO `film_user` VALUES ('17', '', '', '', 'o7j4e1QddhOcwStVpp5z7Wukca_M', 'Amber阿糖', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKgNQbtic3oYSQAHz753RaasOjpxeoETrsY6A8qoOIgJ29YZtLC8bK6CiaiaiceDghQtI4I28UzfiaMwYw/132', '2', '0', '0', '朝阳', '北京', '中国', '', '', '0', '2018-05-03 21:04:49', '2018-05-03 21:04:49', '1', '', '', '', '0');
INSERT INTO `film_user` VALUES ('18', '', '', '', 'o7j4e1cup2swNvtEu7EP6D1eX5FY', 'Capricorn', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/PicmOgM5hib501dwmj1OznxvyLIL35h2DewQaHTVa3M9UL7aljsUMFrvFOp08EPuNArF3Rn9mrG4nnfq2Wr94zfA/132', '1', '0', '0', '昌平', '北京', '中国', '', '', '0', '2018-05-04 18:54:44', '2018-05-09 13:53:07', '1', '', '', '', '0');
INSERT INTO `film_user` VALUES ('19', '', '', '', 'o7j4e1ZfLU1Orc1wQJmrti1tn-1U', 'Addie晓迪', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83erbSOQmibPy6OxN5cibt5v2L67u9FNNcrtpmEdXm7voqt7vCGCgUDTwRobEzJ0UeLXL4k7qSaweKpnQ/132', '2', '0', '0', '海淀', '北京', '中国', '', '', '0', '2018-05-10 15:31:39', '2018-05-10 15:31:39', '1', '', '', '', '0');
INSERT INTO `film_user` VALUES ('20', '', '', '', 'o7j4e1RdEMTtbNa6oQstcaB_IIkM', '张延利', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLciaov6lYWWIXCkqP4Iu1vc6xRqVkZoDZibLBFjTlhGu6JOia8It6vWVOAckqYTGpQKYriac1Ja60IpQ/132', '1', '0', '0', '海淀', '北京', '中国', '', '', '0', '2018-05-11 10:06:12', '2018-05-11 10:06:12', '1', '', '', '', '0');
INSERT INTO `film_user` VALUES ('21', '', '', '', 'o7j4e1d8SDRoA60G_xAe4A0pMHdU', '童虎?', '', '', 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q3auHgzwzM6B4cAiczHB7Ib45GR83y4qqwprfS7Sg2mP96EibJrZHPqvyI36e4RRFo3lAelGzib8L7IWWTfaOlfaQ/132', '1', '0', '0', '东城', '北京', '中国', '', '', '0', '2018-05-12 13:31:51', '2018-05-12 13:31:51', '1', '', '', '', '0');

-- ----------------------------
-- Table structure for film_user_session
-- ----------------------------
DROP TABLE IF EXISTS `film_user_session`;
CREATE TABLE `film_user_session` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `session_key` varchar(200) NOT NULL DEFAULT '',
  `create_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `session_key` (`session_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of film_user_session
-- ----------------------------
INSERT INTO `film_user_session` VALUES ('15', '74bcea8beb4fab3a771694408f58a8bf6dd3ccaea031149c058a23ea5e7f695c', '2018-05-03 20:01:46', '2018-05-03 20:01:46');
INSERT INTO `film_user_session` VALUES ('16', 'fbea18fabc0de6d72939465ef631aa52cabb56c6d1b4a1fb48b95f1354f82906', '2018-05-03 20:55:49', '2018-05-03 20:55:49');
INSERT INTO `film_user_session` VALUES ('17', '1ec528b0d6a2b171e7d528a58bb8e09406d2fb9b7f60134c8ec2e376233c9b70', '2018-05-03 21:04:49', '2018-05-03 21:04:49');
INSERT INTO `film_user_session` VALUES ('18', 'ddf58c68c73793cd48fa89a30530c50ab77071833492058dc9323675bbebc8e3', '2018-05-04 18:54:44', '2018-05-04 18:54:44');
INSERT INTO `film_user_session` VALUES ('19', '73f7576331756e658a0e9a6e2048685b2faf1911dca757bf36a1a3153bae11bc', '2018-05-10 15:31:39', '2018-05-10 15:31:39');
INSERT INTO `film_user_session` VALUES ('20', '2d607e46b5dbe8dd4afe97f2bf57e09bf58020e813dfb5822ecfb70824bf6916', '2018-05-11 10:06:12', '2018-05-11 10:06:12');
INSERT INTO `film_user_session` VALUES ('21', '74d38b6e99cb3671b22125174f461bc71e60ffa019d05bfbc0695c6735b06ceb', '2018-05-12 13:31:51', '2018-05-12 13:31:51');
