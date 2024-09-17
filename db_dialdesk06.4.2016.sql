/*
SQLyog Community v8.32 
MySQL - 5.0.95 : Database - db_dialdesk
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_dialdesk` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db_dialdesk`;

/*Table structure for table `agent_master` */

DROP TABLE IF EXISTS `agent_master`;

CREATE TABLE `agent_master` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(255) default NULL,
  `userid` varchar(255) default NULL,
  `password` varchar(255) default NULL,
  `password2` varchar(255) default NULL,
  `status` varchar(10) default NULL,
  `createdate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `agent_master` */

insert  into `agent_master`(`id`,`username`,`userid`,`password`,`password2`,`status`,`createdate`) values (1,'Chandresh Mani Tripathi','chandresh','chandresh','chandresh','A',NULL),(6,'Anil Kumar','anil','$2a$10$Y5teHs.kYi4cd3ECvzdG/u4i2PlNU7J.cmwciOvt56pfVgeQTuiBq','anil','A','2016-03-04 11:16:23'),(3,'Ravi Kumar','ravi','ravi','ravi','I',NULL),(5,'shilpa','shilpa','$2a$10$6ZA4Xlal5Mk5SMW1ovTBh.P6.vLWYGPSp6KY0uHSmrirWVjpvMMSW','123','A','2016-03-04 10:47:50'),(7,'anil','anil1','$2a$10$O2dz.j4BSxBrPDM7C1aHXOgnQ4hHmlu.byszWR9jTY32yqiL09yJK','123','A','2016-03-07 18:33:44'),(8,'Chandresh','chandresh@gmail.com','$2a$10$cOJLVtt7K0kePEQEW5YAqOUtr41PkA/O2EV3.uNkYNLMMdee0kDbm','india123','A','2016-03-23 15:33:03');

/*Table structure for table `call_master` */

DROP TABLE IF EXISTS `call_master`;

CREATE TABLE `call_master` (
  `Id` int(10) NOT NULL auto_increment,
  `ClientId` bigint(12) default NULL,
  `SrNo` bigint(12) NOT NULL,
  `MSISDN` bigint(12) default NULL,
  `Category1` varchar(200) default NULL,
  `Category2` varchar(200) default NULL,
  `Category3` varchar(200) default NULL,
  `Category4` varchar(200) default NULL,
  `Category5` varchar(200) default NULL,
  `Field1` varchar(500) default NULL,
  `Field2` varchar(500) default NULL,
  `Field3` varchar(500) default NULL,
  `Field4` varchar(500) default NULL,
  `Field5` varchar(500) default NULL,
  `Field6` varchar(500) default NULL,
  `Field7` varchar(500) default NULL,
  `Field8` varchar(500) default NULL,
  `Field9` varchar(500) default NULL,
  `Field10` varchar(500) default NULL,
  `Field11` varchar(500) default NULL,
  `Field12` varchar(500) default NULL,
  `Field13` varchar(500) default NULL,
  `Field14` varchar(500) default NULL,
  `Field15` varchar(500) default NULL,
  `Field16` varchar(500) default NULL,
  `Field17` varchar(500) default NULL,
  `Field18` varchar(500) default NULL,
  `Field19` varchar(500) default NULL,
  `Field20` varchar(500) default NULL,
  `CallDate` datetime default NULL,
  `AgentId` int(10) default NULL,
  `LeadId` bigint(12) default NULL,
  `CloseLoopCate1` varchar(200) default NULL,
  `CloseLoopCate2` varchar(200) default NULL,
  `UpdateDate` datetime default NULL,
  `emailSend` varchar(50) default NULL,
  `smsSend` varchar(50) default NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

/*Data for the table `call_master` */

insert  into `call_master`(`Id`,`ClientId`,`SrNo`,`MSISDN`,`Category1`,`Category2`,`Category3`,`Category4`,`Category5`,`Field1`,`Field2`,`Field3`,`Field4`,`Field5`,`Field6`,`Field7`,`Field8`,`Field9`,`Field10`,`Field11`,`Field12`,`Field13`,`Field14`,`Field15`,`Field16`,`Field17`,`Field18`,`Field19`,`Field20`,`CallDate`,`AgentId`,`LeadId`,`CloseLoopCate1`,`CloseLoopCate2`,`UpdateDate`,`emailSend`,`smsSend`) values (1,2,1,1524578,'Complain','Mobile','Customer Service','Desktop',NULL,'Mobile','wrerr','No','werwer','werwerwe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-12 00:00:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,2,2,1234578,'Complaint',NULL,NULL,NULL,NULL,'fws','sdfsdfsdf',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-13 14:46:39',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,2,3,NULL,'Complaint',NULL,NULL,NULL,NULL,'welcome','back',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-13 17:02:21',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,1,1,3333333333,'Complain','Hardisk','Disk','SrNo','Contact No',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'','','2016-03-05 17:47:15','1',''),(7,1,2,3333333333,'Request','Hardisk','Disk','SrNo','Contact No',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',''),(8,1,3,3333333333,'Complain','Hardisk','Disk','SrNo','Contact No',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',''),(15,3,1,7876765654,'Complain','System','Desktop','Hard Disk',NULL,'Chandresh','8787676565','Laxminagar','Delhi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(16,3,2,7876765654,'Complain','System','Desktop','Hard Disk',NULL,'Anil','6765654543','Noida','UP',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,3,3,5656545434,'Complain','System','Leptop','Hard Disk',NULL,'Anil','7777777777','New yo','Delhi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Third Lavel','First Label','2016-02-18 12:08:21',NULL,NULL),(18,3,4,6666666666,'Complain','System','Desktop','Hard Disk',NULL,'Rajesh','8888888888','Anil Singh','Gorakhpur',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-02 12:21:52',NULL,NULL,'P1','P2','2016-02-18 15:28:28',NULL,NULL),(21,6,1,1111111111,'Inquiry','Phase I',NULL,NULL,NULL,'Plan Inquiry','Laxmi Nagar',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-02 12:21:52',NULL,NULL,'System Loop Cat','','2016-02-22 11:03:20',NULL,NULL),(22,6,2,2222222222,'Complaint','Phase II','sfsdfsfd','sdsdf',NULL,'Voda phone','Shakarpur',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-02 12:21:52',NULL,NULL,'First Inquiry','Second Inquiry','2016-02-22 11:03:25',NULL,NULL),(23,6,3,3333333333,'Request','Phase III',NULL,NULL,NULL,'Voda Plan Request','Anand Vihar','f1','f2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-03 15:18:57',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(24,6,4,4444444444,'Complaint','Phase I',NULL,NULL,NULL,'Num verify','Vashali',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-03 17:50:49',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,5,1,NULL,'request','request accepted','please change this','hellos','byesss','calling',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-04 12:03:51',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,6,5,5555555555,'Request','Phase II',NULL,NULL,NULL,'System Inquery','Shakarpur','f1','f2','f3','f4',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-04 11:35:36',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(29,6,6,6666666666,'Complaint','Phase III',NULL,NULL,NULL,'sdfsdf','sdfsdfsdf',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-04 11:52:35',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(30,6,7,7777777777,'Request','Phase I',NULL,NULL,NULL,'sdfsdf','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 11:54:40',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(31,6,8,8888888888,'Inquiry','Phase II',NULL,NULL,NULL,'sdfsdf','sdfsdf',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 12:45:55',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(32,6,9,9999999999,'Request','Phase III',NULL,NULL,NULL,'sdfsdf','sdfsdf',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 12:46:06',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(33,6,10,12222222222,'Inquiry','Phase I',NULL,NULL,NULL,'sdfsdf','sdfsf',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 12:46:15',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(34,6,11,13333333333,'Inquiry','Phase II',NULL,NULL,NULL,'sdfsdfsf','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-06 12:46:27',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(35,6,12,14444444444,'Inquiry','Phase III',NULL,NULL,NULL,'sdfsdfsdf','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-06 12:46:46',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(36,6,13,345345345,'Inquiry','Phase I',NULL,NULL,NULL,'fdgdfg','dfgdfgdg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-06 13:31:10',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(37,6,14,2332423423,'Inquiry','Phase I',NULL,NULL,NULL,'dfgdfg','dfgdfg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-07 16:42:48',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(38,1,4,6765654543,'cat1','Mat1','Chat!','Hat1','Bat1','Wel1','Wel22','F1','G1','H1','I1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-07 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(39,1,5,6765654544,'cat2','Mat2','Chat!','Hat2','Bat2','Wel2','Wel23','F2','G2','H2','I2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(40,1,6,6765654545,'cat3','Mat3','Chat!','Hat3','Bat3','Wel3','Wel24','F3','G3','H3','I3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(41,1,7,6765654546,'cat4','Mat4','Chat!','Hat4','Bat4','Wel4','Wel25','F4','G4','H4','I4',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(42,1,8,6765654547,'cat5','Mat5','Chat!','Hat5','Bat5','Wel5','Wel26','F5','G5','H5','I5',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(43,1,9,6765654548,'cat6','Mat6','Chat!','Hat6','Bat6','Wel6','Wel27','F6','G6','H6','I6',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-07 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(44,1,10,6765654549,'cat7','Mat7','Chat!','Hat7','Bat7','Wel7','Wel28','F7','G7','H7','I7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(45,1,11,6765654550,'cat8','Mat8','Chat!','Hat8','Bat8','Wel8','Wel29','F8','G8','H8','I8',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(46,1,12,6765654551,'cat9','Mat9','Chat!','Hat9','Bat9','Wel9','Wel30','F9','G9','H9','I9',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(47,1,13,6765654552,'cat10','Mat10','Chat!','Hat10','Bat10','Wel10','Wel31','F10','G10','H10','I10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(48,1,14,6765654553,'cat11','Mat11','Chat!','Hat11','Bat11','Wel11','Wel32','F11','G11','H11','I11',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(49,1,15,6765654554,'cat12','Mat12','Chat!','Hat12','Bat12','Wel12','Wel33','F12','G12','H12','I12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(50,1,16,6765654555,'cat13','Mat13','Chat!','Hat13','Bat13','Wel13','Wel34','F13','G13','H13','I13',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(51,1,17,6765654556,'cat14','Mat14','Chat!','Hat14','Bat14','Wel14','Wel35','F14','G14','H14','I14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-02 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(52,1,18,6765654557,'cat15','Mat15','Chat!','Hat15','Bat15','Wel15','Wel36','F15','G15','H15','I15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-02 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(53,1,19,6765654558,'cat16','Mat16','Chat!','Hat16','Bat16','Wel16','Wel37','F16','G16','H16','I16',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-02 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(54,1,20,6765654559,'cat17','Mat17','Chat!','Hat17','Bat17','Wel17','Wel38','F17','G17','H17','I17',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-02 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(55,1,21,6765654560,'cat18','Mat18','Chat!','Hat18','Bat18','Wel18','Wel39','F18','G18','H18','I18',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-03 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(56,1,22,6765654561,'cat19','Mat19','Chat!','Hat19','Bat19','Wel19','Wel40','F19','G19','H19','I19',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-03 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(57,1,23,6765654562,'cat20','Mat20','Chat!','Hat20','Bat20','Wel20','Wel41','F20','G20','H20','I20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-03 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(58,1,24,6765654563,'cat21','Mat21','Chat!','Hat21','Bat21','Wel21','Wel42','F21','G21','H21','I21',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-04 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(59,1,25,6765654564,'cat22','Mat22','Chat!','Hat22','Bat22','Wel22','Wel43','F22','G22','H22','I22',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-04 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(60,1,26,6765654565,'cat23','Mat23','Chat!','Hat23','Bat23','Wel23','Wel44','F23','G23','H23','I23',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-04 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(61,1,27,6765654566,'cat24','Mat24','Chat!','Hat24','Bat24','Wel24','Wel45','F24','G24','H24','I24',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(62,1,28,6765654567,'cat25','Mat25','Chat!','Hat25','Bat25','Wel25','Wel46','F25','G25','H25','I25',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(63,1,29,6765654568,'cat26','Mat26','Chat!','Hat26','Bat26','Wel26','Wel47','F26','G26','H26','I26',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(64,1,30,6765654569,'cat27','Mat27','Chat!','Hat27','Bat27','Wel27','Wel48','F27','G27','H27','I27',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-06 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(65,1,31,6765654570,'cat28','Mat28','Chat!','Hat28','Bat28','Wel28','Wel49','F28','G28','H28','I28',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-06 17:00:40',NULL,NULL,NULL,NULL,NULL,'1',''),(66,1,32,6765654571,'Complain','Laptop','Chat!','Hat29','Bat29','Wel29','Wel50','F29','G29','H29','I29',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-07 17:00:40',NULL,NULL,'Manual','Next Manual','2016-03-05 18:16:08','1','');

/*Table structure for table `call_master_history` */

DROP TABLE IF EXISTS `call_master_history`;

CREATE TABLE `call_master_history` (
  `Id` int(10) NOT NULL default '0',
  `ClientId` bigint(12) default NULL,
  `SrNo` bigint(12) NOT NULL,
  `MSISDN` bigint(12) default NULL,
  `Category1` varchar(200) default NULL,
  `Category2` varchar(200) default NULL,
  `Category3` varchar(200) default NULL,
  `Category4` varchar(200) default NULL,
  `Category5` varchar(200) default NULL,
  `Field1` varchar(500) default NULL,
  `Field2` varchar(500) default NULL,
  `Field3` varchar(500) default NULL,
  `Field4` varchar(500) default NULL,
  `Field5` varchar(500) default NULL,
  `Field6` varchar(500) default NULL,
  `Field7` varchar(500) default NULL,
  `Field8` varchar(500) default NULL,
  `Field9` varchar(500) default NULL,
  `Field10` varchar(500) default NULL,
  `Field11` varchar(500) default NULL,
  `Field12` varchar(500) default NULL,
  `Field13` varchar(500) default NULL,
  `Field14` varchar(500) default NULL,
  `Field15` varchar(500) default NULL,
  `Field16` varchar(500) default NULL,
  `Field17` varchar(500) default NULL,
  `Field18` varchar(500) default NULL,
  `Field19` varchar(500) default NULL,
  `Field20` varchar(500) default NULL,
  `CallDate` datetime default NULL,
  `AgentId` int(10) default NULL,
  `LeadId` bigint(12) default NULL,
  `CloseLoopCate1` varchar(200) default NULL,
  `CloseLoopCate2` varchar(200) default NULL,
  `UpdateDate` datetime default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `call_master_history` */

insert  into `call_master_history`(`Id`,`ClientId`,`SrNo`,`MSISDN`,`Category1`,`Category2`,`Category3`,`Category4`,`Category5`,`Field1`,`Field2`,`Field3`,`Field4`,`Field5`,`Field6`,`Field7`,`Field8`,`Field9`,`Field10`,`Field11`,`Field12`,`Field13`,`Field14`,`Field15`,`Field16`,`Field17`,`Field18`,`Field19`,`Field20`,`CallDate`,`AgentId`,`LeadId`,`CloseLoopCate1`,`CloseLoopCate2`,`UpdateDate`) values (3,4,0,1,'Test3','Test2','Test1',NULL,NULL,'System inquire','7878767656','Shakarpur','Hariom',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-08 10:53:22',NULL,NULL,NULL,NULL,'2016-02-08 13:32:57'),(3,4,0,1,'Test1','Test2','Test3',NULL,NULL,'System inquire','7878767656','Shakarpur','Hariom',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-08 10:53:22',NULL,NULL,NULL,NULL,'2016-02-08 17:02:13'),(3,4,0,1,'System1','Test2','Test3',NULL,NULL,'System inquire','7878767656','Shakarpur','Hariom',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-08 10:53:22',NULL,NULL,NULL,NULL,'2016-02-08 17:02:37'),(3,4,0,1,'System','','Test3',NULL,NULL,'System inquire','7878767656','Shakarpur','Hariom',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-08 10:53:22',NULL,NULL,NULL,NULL,'2016-02-08 17:03:19'),(3,4,0,1,'System','Computer','Test3','',NULL,'System inquire','7878767656','Shakarpur','Hariom',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-08 10:53:22',NULL,NULL,NULL,NULL,'2016-02-08 17:03:37'),(4,4,1,123,'Cat1','Cat2','Cat3',NULL,NULL,'Sinquire','7876767654','Laxmi Nagar','Delhi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-08 11:50:50',NULL,NULL,NULL,NULL,'2016-02-08 17:03:51'),(4,4,1,123,'Assets','','Cat3',NULL,NULL,'Sinquire','7876767654','Laxmi Nagar','Delhi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-08 11:50:50',NULL,NULL,NULL,NULL,'2016-02-08 17:03:52'),(1,4,0,7876767656,'System','Computer','Motherbord','Assus','In Service','System inquire','7878767656','Shakarpur','Delhi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-09 13:55:41',NULL,NULL,NULL,NULL,'2016-02-09 14:33:14'),(1,4,0,7876767656,'System','Computer','Motherbord','Assus','In Service','System inquire','7878767656','Shakarpur','Delhi',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-09 13:55:41',NULL,NULL,'First Service ','Second Service','2016-02-09 14:33:14');

/*Table structure for table `call_master_out` */

DROP TABLE IF EXISTS `call_master_out`;

CREATE TABLE `call_master_out` (
  `Id` int(10) unsigned NOT NULL auto_increment,
  `DataId` varchar(100) default NULL,
  `ClientId` bigint(12) default NULL,
  `AllocationId` int(20) default NULL,
  `SrNo` bigint(12) NOT NULL,
  `MSISDN` bigint(12) default NULL,
  `Category1` varchar(200) default NULL,
  `Category2` varchar(200) default NULL,
  `Category3` varchar(200) default NULL,
  `Category4` varchar(200) default NULL,
  `Category5` varchar(200) default NULL,
  `Field1` varchar(500) default NULL,
  `Field2` varchar(500) default NULL,
  `Field3` varchar(500) default NULL,
  `Field4` varchar(500) default NULL,
  `Field5` varchar(500) default NULL,
  `Field6` varchar(500) default NULL,
  `Field7` varchar(500) default NULL,
  `Field8` varchar(500) default NULL,
  `Field9` varchar(500) default NULL,
  `Field10` varchar(500) default NULL,
  `Field11` varchar(500) default NULL,
  `Field12` varchar(500) default NULL,
  `Field13` varchar(500) default NULL,
  `Field14` varchar(500) default NULL,
  `Field15` varchar(500) default NULL,
  `Field16` varchar(500) default NULL,
  `Field17` varchar(500) default NULL,
  `Field18` varchar(500) default NULL,
  `Field19` varchar(500) default NULL,
  `Field20` varchar(500) default NULL,
  `CallDate` datetime default NULL,
  `AgentId` int(10) default NULL,
  `LeadId` bigint(12) default NULL,
  `CloseLoopCate1` varchar(200) default NULL,
  `CloseLoopCate2` varchar(200) default NULL,
  `UpdateDate` datetime default NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Data for the table `call_master_out` */

insert  into `call_master_out`(`Id`,`DataId`,`ClientId`,`AllocationId`,`SrNo`,`MSISDN`,`Category1`,`Category2`,`Category3`,`Category4`,`Category5`,`Field1`,`Field2`,`Field3`,`Field4`,`Field5`,`Field6`,`Field7`,`Field8`,`Field9`,`Field10`,`Field11`,`Field12`,`Field13`,`Field14`,`Field15`,`Field16`,`Field17`,`Field18`,`Field19`,`Field20`,`CallDate`,`AgentId`,`LeadId`,`CloseLoopCate1`,`CloseLoopCate2`,`UpdateDate`) values (1,'1',6,5,1,NULL,'Srno','ServiceType','ContactNo','Address','State','123456',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-02 18:25:16',5,NULL,NULL,NULL,NULL),(2,'2',6,5,2,NULL,'Srno','ServiceType','ContactNo','Address','State','123456',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-02 18:26:21',5,NULL,NULL,NULL,NULL),(3,'3',6,5,3,NULL,'Srno','ServiceType','ContactNo','Address','State','123456',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-03 18:26:37',5,NULL,NULL,NULL,NULL),(4,'4',6,5,4,NULL,'Srno','ServiceType','ContactNo','Address','State','789456',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-03 18:34:19',5,NULL,NULL,NULL,NULL),(5,'5',6,5,5,NULL,'Srno','ServiceType','ContactNo','Address','State','123123123',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-03 19:03:41',5,NULL,NULL,NULL,NULL),(6,'6',6,5,6,NULL,'Srno','ServiceType','ContactNo','Address','State','23234234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-04 19:06:37',5,NULL,NULL,NULL,NULL),(7,'100',6,5,7,NULL,'Srno','ServiceType','ContactNo','Address','State','4234234234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-04 19:08:55',5,NULL,NULL,NULL,NULL),(8,'110',6,5,8,NULL,'Srno','ServiceType','ContactNo','Address','State','4234234234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-04 19:24:04',5,NULL,NULL,NULL,NULL),(9,'120',6,5,9,NULL,'Srno','ServiceType','ContactNo','Address','State','4234234234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-04 19:24:48',5,NULL,NULL,NULL,NULL),(10,'130',6,5,10,NULL,'Srno','ServiceType','ContactNo','Address','State','4234234234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 19:25:26',5,NULL,NULL,NULL,NULL),(11,'140',6,5,11,NULL,'Srno','ServiceType','ContactNo','Address','State','4234234234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 19:26:09',5,NULL,NULL,NULL,NULL),(12,'150',6,5,12,NULL,'Srno','ServiceType','ContactNo','Address','State','1231231231',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 19:26:21',5,NULL,NULL,NULL,NULL),(13,'16',6,5,13,NULL,'Srno','ServiceType','ContactNo','Address','State','24234234234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 19:27:25',5,NULL,NULL,NULL,NULL),(16,'101',1,7,3,NULL,'Pospaid','New Sim','Document','Driviing Licence',NULL,'12345',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-07 19:30:40',7,NULL,NULL,NULL,NULL);

/*Table structure for table `city_master` */

DROP TABLE IF EXISTS `city_master`;

CREATE TABLE `city_master` (
  `id` int(10) NOT NULL auto_increment,
  `StateId` int(10) default NULL,
  `CityName` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `city_master` */

insert  into `city_master`(`id`,`StateId`,`CityName`) values (1,1,'Alahabad'),(2,1,'Gorakhpur'),(3,2,'Patana'),(4,2,'Baksar');

/*Table structure for table `closeloop_master` */

DROP TABLE IF EXISTS `closeloop_master`;

CREATE TABLE `closeloop_master` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `client_id` int(11) default NULL,
  `Category1` varchar(255) default NULL,
  `Category2` varchar(255) default NULL,
  `close_loop` varchar(255) default NULL,
  `close_loop_category` varchar(255) default NULL,
  `parent_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `closeloop_master` */

insert  into `closeloop_master`(`id`,`client_id`,`Category1`,`Category2`,`close_loop`,`close_loop_category`,`parent_id`) values (1,1,'Complain','Laptop','system','Clossing According System',NULL),(2,1,'Complain','Laptop','system','Next Period',1);

/*Table structure for table `crone_master` */

DROP TABLE IF EXISTS `crone_master`;

CREATE TABLE `crone_master` (
  `id` int(20) NOT NULL auto_increment,
  `escalationId` int(20) default NULL,
  `type` varchar(20) default NULL,
  `ecrFields` varchar(100) default NULL,
  `captureFields` varchar(100) default NULL,
  `escalationType` varchar(20) default NULL,
  `escalationTime` varchar(20) default NULL,
  `clientId` varchar(20) default NULL,
  `active` int(2) default '0',
  `createdate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

/*Data for the table `crone_master` */

insert  into `crone_master`(`id`,`escalationId`,`type`,`ecrFields`,`captureFields`,`escalationType`,`escalationTime`,`clientId`,`active`,`createdate`) values (1,1,'alert','1,2,4','1,2,4','Month','9','1',0,'2016-03-31 17:43:15'),(2,2,'Escalation','1,2','1,2','Month','10','1',0,'2016-03-31 17:44:28'),(3,3,'Escalation','1,2','1,2','Month','10','1',0,'2016-03-31 17:47:08'),(4,4,'Escalation','1,2','1,2,3','Month','12','1',0,'2016-03-31 17:49:49'),(5,5,'Escalation','1,2','1,2,3','Month','12','1',0,'2016-03-31 17:54:24'),(6,6,'Escalation','1,2','1,2,3','Month','12','1',0,'2016-03-31 17:55:12'),(7,7,'Escalation','1,2','1,2,3','Month','12','1',0,'2016-03-31 17:55:20'),(8,8,'Escalation','1,2','1,2,3','Month','12','1',0,'2016-03-31 17:55:36'),(9,9,'Escalation','1,2','1,2,3','Month','12','1',0,'2016-03-31 17:56:36'),(10,10,'Escalation','1,2','1,2,3','Month','12','1',0,'2016-03-31 17:57:03'),(11,11,'Escalation','1,2','1,2,3','Month','12','1',0,'2016-03-31 17:57:11'),(12,12,'Escalation','1,2','1,2,3','Month','12','1',0,'2016-03-31 18:01:15'),(13,13,'Escalation','1,2,3,4','2,3,4','Month','16','1',0,'2016-03-31 19:07:54'),(14,14,'Escalation','1,2,3,4','1,2,3,4','Month','8','1',0,'2016-03-31 19:12:32'),(15,15,'Escalation','1,2,3,4','1,2,3,4','Month','7','1',0,'2016-03-31 19:15:05'),(16,16,'alert','1,2,3,4','1,2,3,4','Month','16','1',0,'2016-04-01 10:49:12'),(17,17,'alert','1,2,3,4','1,2,3,4','Month','10','1',0,'2016-04-01 11:10:00'),(18,18,'Escalation','1,2,3,4','1,2,3,4','Month','14','1',0,'2016-04-01 11:12:19'),(19,19,'alert','1,2,3,4','1,2,3,4','Month','15','1',0,'2016-04-01 11:14:43'),(20,20,'Escalation','1,2,3,4','1,2,3,4','Month','14','1',0,'2016-04-01 11:23:46'),(21,21,'Escalation','1,2,3,4','1,2,3,4','Month','14','1',0,'2016-04-01 11:23:54'),(22,22,'alert','1,2,3,4','1,2,3,4','Month','14','1',0,'2016-04-01 11:33:08'),(23,23,'alert','1,2,3,4','1,2,3,4','Month','1','1',0,'2016-04-01 12:45:38'),(24,24,'Escalation','1,2,3,4','','Month','1','1',0,'2016-04-01 12:48:26'),(25,25,'Escalation','1,2,3,4','','Month','1','1',0,'2016-04-01 12:53:45'),(26,26,'Escalation','1,2,3,4','1,2,3,4','Hour','00:01','1',0,'2016-04-01 12:58:49'),(27,27,'alert','1,2,3,4','1,2,3,4','Hour','00:01','1',0,'2016-04-01 13:04:29'),(28,28,'Escalation','1,2,3,4','1,2,3,4','Hour','00:01','1',0,'2016-04-01 13:17:05'),(29,29,'Escalation','1,2,3,4','1,2,3,4','Hour','00:01','1',0,'2016-04-01 13:28:24'),(30,30,'alert','1,2,3,4','1,2,3,4','Hour','00:01','1',0,'2016-04-01 13:44:22'),(31,31,'alert','1,2,3,4','1,2,3,4','Hour','00:01','1',0,'2016-04-01 13:45:03'),(32,32,'alert','1,2,3,4','1,2,3,4','Hour','00:01','1',0,'2016-04-01 13:47:24'),(33,33,'alert','1,2,3,4','1,2,3,4','Month','9','1',0,'2016-04-01 13:50:30'),(34,34,'alert','1,2,3,4','1,2,3,4','Hour','00:01','1',0,'2016-04-01 13:52:38'),(35,35,'alert','1,2,3,4','1,2,3,4','Hour','00:01','1',0,'2016-04-01 13:58:17'),(36,36,'Escalation','1,2,3,4','1,2,3,4','Hour','00:01','1',0,'2016-04-01 15:10:31');

/*Table structure for table `ecr_master` */

DROP TABLE IF EXISTS `ecr_master`;

CREATE TABLE `ecr_master` (
  `id` int(11) NOT NULL auto_increment,
  `ecrName` varchar(50) default NULL,
  `parent_id` varchar(11) default NULL,
  `Label` int(11) default NULL,
  `Client` varchar(100) default NULL,
  `createdate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

/*Data for the table `ecr_master` */

insert  into `ecr_master`(`id`,`ecrName`,`parent_id`,`Label`,`Client`,`createdate`) values (1,'Complain',NULL,1,'1','2016-02-16 19:15:47'),(2,'Laptop','1',2,'1','2016-02-16 19:16:16'),(3,'Display','2',3,'1','2016-02-16 19:16:36'),(4,'size','3',4,'1','2016-02-16 19:17:30'),(5,'Address','4',5,'1','2016-02-16 19:18:55'),(6,'Response',NULL,1,'1','2016-02-17 10:57:57'),(8,'Complain',NULL,1,'3','2016-02-18 11:29:44'),(9,'Request',NULL,1,'3','2016-02-18 11:30:29'),(10,'System','8',2,'3','2016-02-18 11:32:06'),(11,'Leptop','10',3,'3','2016-02-18 11:32:38'),(12,'Desktop','10',3,'3','2016-02-18 11:32:50'),(13,'Hard Disk','11',4,'3','2016-02-18 11:33:11'),(14,'Inquiry',NULL,1,'6','2016-02-20 10:59:25'),(15,'Request',NULL,1,'6','2016-02-20 10:59:33'),(16,'Complaint',NULL,1,'6','2016-02-20 10:59:44'),(17,'Phase I','14',2,'6','2016-02-20 16:42:51'),(18,'Phase I','15',2,'6','2016-02-20 16:43:11'),(19,'Phase II','15',2,'6','2016-02-20 16:43:21'),(20,'Phase I','16',2,'6','2016-02-20 16:43:36'),(21,'Phase II','16',2,'6','2016-02-20 16:43:40'),(22,'Phase III','16',2,'6','2016-02-20 16:43:49'),(24,'complain',NULL,1,'5','2016-02-22 10:58:01'),(25,'callin','24',2,'5','2016-02-22 10:58:16'),(26,'request',NULL,1,'5','2016-02-29 18:41:06'),(27,'request accepted','26',2,'5','2016-02-29 18:41:17'),(28,'please change this','27',3,'5','2016-02-29 18:41:32'),(29,'hellos','28',4,'5','2016-02-29 18:41:45'),(30,'byesss','29',5,'5','2016-02-29 18:41:58'),(31,'Desktop','1',2,'1','2016-03-07 11:38:21'),(32,'Hard Disk','2',3,'1','2016-03-07 11:38:53'),(33,'CRT','31',3,'1','2016-03-07 11:39:06'),(34,'Mother Board','31',3,'1','2016-03-07 11:39:40'),(35,'ProductNo','3',4,'1','2016-03-07 11:41:03');

/*Table structure for table `escalation_master` */

DROP TABLE IF EXISTS `escalation_master`;

CREATE TABLE `escalation_master` (
  `id` int(11) NOT NULL auto_increment,
  `ClientId` varchar(20) default NULL,
  `ecrId` varchar(20) default NULL,
  `notification` varchar(20) default NULL,
  `timer` varchar(20) default NULL,
  `Year` datetime default NULL,
  `Month` varchar(2) default NULL,
  `Week` varchar(20) default NULL,
  `Day` varchar(5) default NULL,
  `Hour` varchar(5) default NULL,
  `type` varchar(5) default NULL,
  `smsNo` varchar(20) default NULL,
  `email` varchar(50) default NULL,
  `fields` varchar(500) default NULL,
  `format` varchar(500) default NULL,
  `ecrFields` varchar(100) default NULL,
  `captureFields` varchar(100) default NULL,
  `status` int(1) default NULL,
  `createdate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

/*Data for the table `escalation_master` */

insert  into `escalation_master`(`id`,`ClientId`,`ecrId`,`notification`,`timer`,`Year`,`Month`,`Week`,`Day`,`Hour`,`type`,`smsNo`,`email`,`fields`,`format`,`ecrFields`,`captureFields`,`status`,`createdate`) values (1,'1','2','alert','Month',NULL,'9',NULL,NULL,NULL,'email',NULL,'tripathi@tripathimail.com','Your Mail is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category4[/tag]','Your Mail is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category4[/tag]','1,2,4','1,2,4',1,'2016-03-31 17:43:15'),(2,'1','6','Escalation','Month',NULL,'10',NULL,NULL,NULL,'email',NULL,'tripathi@tripathimail.com','Your Format is[tag1]Address[/tag1][tag1]City[/tag1][tag]Category1[/tag][tag]Category2[/tag]','Your Format is[tag1]Address[/tag1][tag1]City[/tag1][tag]Category1[/tag][tag]Category2[/tag]','1,2','1,2',NULL,'2016-03-31 17:44:28'),(3,'1','6','Escalation','Month',NULL,'10',NULL,NULL,NULL,'email',NULL,'tripathi@tripathimail.com','Your Format is[tag1]Address[/tag1][tag1]City[/tag1][tag]Category1[/tag][tag]Category2[/tag]','Your Format is[tag1]Address[/tag1][tag1]City[/tag1][tag]Category1[/tag][tag]Category2[/tag]','1,2','1,2',NULL,'2016-03-31 17:47:08'),(4,'1','1','Escalation','Month',NULL,'12',NULL,NULL,NULL,'both',NULL,'tripathi@tripathimail.com','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','1,2','1,2,3',1,'2016-03-31 17:49:49'),(5,'1','1','Escalation','Month',NULL,'12',NULL,NULL,NULL,'both',NULL,'tripathi@tripathimail.com','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','1,2','1,2,3',1,'2016-03-31 17:54:24'),(6,'1','1','Escalation','Month',NULL,'12',NULL,NULL,NULL,'both',NULL,'tripathi@tripathimail.com','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','1,2','1,2,3',1,'2016-03-31 17:55:12'),(7,'1','1','Escalation','Month',NULL,'12',NULL,NULL,NULL,'both',NULL,'tripathi@tripathimail.com','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','1,2','1,2,3',NULL,'2016-03-31 17:55:20'),(8,'1','1','Escalation','Month',NULL,'12',NULL,NULL,NULL,'both',NULL,'tripathi@tripathimail.com','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','1,2','1,2,3',NULL,'2016-03-31 17:55:36'),(9,'1','1','Escalation','Month',NULL,'12',NULL,NULL,NULL,'both',NULL,'tripathi@tripathimail.com','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','1,2','1,2,3',NULL,'2016-03-31 17:56:36'),(10,'1','1','Escalation','Month',NULL,'12',NULL,NULL,NULL,'both',NULL,'tripathi@tripathimail.com','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','1,2','1,2,3',NULL,'2016-03-31 17:57:03'),(11,'1','1','Escalation','Month',NULL,'12',NULL,NULL,NULL,'both',NULL,'tripathi@tripathimail.com','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','1,2','1,2,3',NULL,'2016-03-31 17:57:11'),(12,'1','6','Escalation','Month',NULL,'12',NULL,NULL,NULL,'both',NULL,'tripathi@tripathimail.com','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','Your Format is\r\n[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag]Category1[/tag][tag]Category2[/tag]','1,2','1,2,3',NULL,'2016-03-31 18:01:15'),(13,'1','32','Escalation','Month',NULL,'16',NULL,NULL,NULL,'both',NULL,'tripathi@tripathimail.com','Your Email Format is[tag1][/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','Your Email Format is[tag1][/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','1,2,3,4','2,3,4',NULL,'2016-03-31 19:07:54'),(14,'1','2','Escalation','Month',NULL,'8',NULL,NULL,NULL,'email',NULL,'tripathi@tripathimail.com','Your FOrmat is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','Your FOrmat is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','1,2,3,4','1,2,3,4',1,'2016-03-31 19:12:32'),(15,'1','31','Escalation','Month',NULL,'7',NULL,NULL,NULL,'both',NULL,'tripathi@tripathimail.com','YOur FOrmat is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','YOur FOrmat is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','1,2,3,4','1,2,3,4',NULL,'2016-03-31 19:15:05'),(16,'1','32','alert','Month',NULL,'16',NULL,NULL,NULL,'email',NULL,'anil.goar@teammas.in','Your Mail Format is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','Your Mail Format is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 10:49:12'),(17,'1','2','alert','Month',NULL,'10',NULL,NULL,NULL,'email',NULL,'anil.goar@teammas.in','Your Mail for this is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','Your Mail for this is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','1,2,3,4','1,2,3,4',1,'2016-04-01 11:10:00'),(18,'1','32','Escalation','Month',NULL,'14',NULL,NULL,NULL,'email',NULL,'anil.goar@teammas.in','YOur mail is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','YOur mail is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 11:12:19'),(19,'1','34','alert','Month',NULL,'15',NULL,NULL,NULL,'email',NULL,'anil.goar@teammas.in','Your Email is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','Your Email is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 11:14:43'),(20,'1','2','Escalation','Month',NULL,'14',NULL,NULL,NULL,'sms','8882240641',NULL,'Your Email Format is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','Your Email Format is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 11:23:46'),(21,'1','2','Escalation','Month',NULL,'14',NULL,NULL,NULL,'email',NULL,'anil.goar@teammas.in','Your Email Format is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','Your Email Format is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 11:23:54'),(22,'1','3','alert','Month',NULL,'14',NULL,NULL,NULL,'email',NULL,'anil.goar@teammas.in','Your Mail is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','Your Mail is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 11:33:08'),(23,'1','31','alert','Month',NULL,'1',NULL,NULL,NULL,'email',NULL,'anil.goar@teammas.in','Your Format is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','Your Format is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 12:45:38'),(24,'1','31','Escalation','Month',NULL,'1',NULL,NULL,NULL,'sms','8882240641',NULL,'[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','1,2,3,4','',NULL,'2016-04-01 12:48:26'),(25,'1','31','Escalation','Month',NULL,'1',NULL,NULL,NULL,'sms',NULL,NULL,'[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','1,2,3,4','',NULL,'2016-04-01 12:53:45'),(26,'1','2','Escalation','Hour',NULL,NULL,NULL,NULL,NULL,'email',NULL,'chandresh.tripathi@teammas.in','Your Data is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','Your Data is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 12:58:49'),(27,'1','3','alert','Hour',NULL,NULL,NULL,NULL,NULL,'email',NULL,'chandresh.tripathi@teammas.in','Your Email Format is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','Your Email Format is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 13:04:29'),(28,'1','2','Escalation','Hour',NULL,NULL,NULL,NULL,NULL,'email',NULL,'chandresh.tripathi@teammas.in','Your Escalation Data is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','Your Escalation Data is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 13:17:05'),(29,'1','34','Escalation','Hour',NULL,NULL,NULL,NULL,NULL,'email',NULL,'tripathi@tripathimail.com','Your Escalation For Data is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','Your Escalation For Data is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 13:28:24'),(30,'1','2','alert','Hour',NULL,NULL,NULL,NULL,NULL,'email',NULL,'chandresh.tripathi@teammas.in','Your Alert Fields Are[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','Your Alert Fields Are[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 13:44:22'),(31,'1','2','alert','Hour',NULL,NULL,NULL,NULL,NULL,'email',NULL,'chandresh.tripathi@teammas.in','Your Alert Fields Are[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','Your Alert Fields Are[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 13:45:03'),(32,'1','1','alert','Hour',NULL,NULL,NULL,NULL,NULL,'email',NULL,'chandresh.tripathi@teammas.in','Your Email Format is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','Your Email Format is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 13:47:24'),(33,'1','31','alert','Month',NULL,'9',NULL,NULL,NULL,'email',NULL,'chandresh.tripathi@teammas.in','Your Format of Mail is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','Your Format of Mail is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 13:50:30'),(34,'1','2','alert','Hour',NULL,NULL,NULL,NULL,NULL,'email',NULL,'chandresh.tripathi@teammas.in','Your Email Format is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','Your Email Format is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 13:52:38'),(35,'1','2','alert','Hour',NULL,NULL,NULL,NULL,'00:01','email',NULL,'chandresh.tripathi@teammas.in','Your Format for Email is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','Your Format for Email is[tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag][tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 13:58:17'),(36,'1','32','Escalation','Hour',NULL,NULL,NULL,NULL,'00:01','email',NULL,'chandresh.tripathi@teammas.in','Your Escalation Format is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','Your Escalation Format is[tag1]Address[/tag1][tag1]City[/tag1][tag1]State[/tag1][tag1]country[/tag1][tag]Category1[/tag][tag]Category2[/tag][tag]Category3[/tag][tag]Category4[/tag]','1,2,3,4','1,2,3,4',NULL,'2016-04-01 15:10:31');

/*Table structure for table `field_master` */

DROP TABLE IF EXISTS `field_master`;

CREATE TABLE `field_master` (
  `id` int(10) NOT NULL auto_increment,
  `FieldName` varchar(200) default NULL,
  `FieldType` varchar(200) default NULL,
  `FieldValidation` varchar(100) default NULL,
  `RequiredCheck` varchar(100) default NULL,
  `Priority` int(10) default NULL,
  `ClientId` int(10) default NULL,
  `CreateDate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Data for the table `field_master` */

insert  into `field_master`(`id`,`FieldName`,`FieldType`,`FieldValidation`,`RequiredCheck`,`Priority`,`ClientId`,`CreateDate`) values (1,'Name','TextBox','Char','1',2,2,'2016-02-13 13:00:59'),(2,'Address','TextArea','Char','1',1,2,'2016-02-13 13:02:37'),(3,'Name','TextBox','Char','1',1,3,'2016-02-18 11:39:43'),(4,'Contact','TextBox','Char','1',2,3,'2016-02-18 11:39:54'),(5,'Address','TextArea','Char','0',3,3,'2016-02-18 11:40:06'),(6,'City','DropDown','Char','1',4,3,'2016-02-18 11:40:47'),(7,'Address','TextBox','Char','1',1,1,'2016-02-18 15:36:27'),(8,'Customer Inquiry','TextBox','Char','1',2,6,'2016-02-22 10:56:25'),(9,'Addresss','TextArea','Char','0',1,6,'2016-02-22 10:56:38'),(10,'complain number','DropDown','Char','1',1,5,'2016-02-22 11:03:31'),(13,'City','DropDown','Char','1',2,1,'2016-03-31 12:01:06'),(11,'State','TextBox','Char','1',3,1,'2016-03-22 18:13:25'),(12,'country','TextBox','Char','1',4,1,'2016-03-22 18:14:57');

/*Table structure for table `field_master_value` */

DROP TABLE IF EXISTS `field_master_value`;

CREATE TABLE `field_master_value` (
  `id` int(10) NOT NULL auto_increment,
  `FieldId` int(10) default NULL,
  `FieldValueName` varchar(300) default NULL,
  `ClientId` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `field_master_value` */

insert  into `field_master_value`(`id`,`FieldId`,`FieldValueName`,`ClientId`) values (1,5,'Complain','3'),(2,6,'Name','3'),(3,7,'FF','3'),(4,10,'calling','5'),(5,13,'GreaterNoida','1'),(6,13,'Ghaziabad','1'),(7,13,'Delhi','1'),(8,13,'Noida','1');

/*Table structure for table `ivr` */

DROP TABLE IF EXISTS `ivr`;

CREATE TABLE `ivr` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `cid` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

/*Data for the table `ivr` */

insert  into `ivr`(`id`,`name`,`pid`,`cid`) values (1,'p1',0,1),(2,'P2',0,1),(3,'P3',0,1),(4,'A1',1,1),(5,'A2',1,1),(6,'A3',1,1),(7,'B1',4,1),(8,'B2',4,1),(9,'B3',4,1),(10,'C1',5,1),(11,'C2',5,1),(12,'C3',5,1),(13,'L1',2,1),(14,'L2',2,1),(15,'L3',2,1),(16,'S1',13,1),(17,'S2',0,1),(18,'S2',13,1);

/*Table structure for table `ivr_master` */

DROP TABLE IF EXISTS `ivr_master`;

CREATE TABLE `ivr_master` (
  `id` int(11) NOT NULL auto_increment,
  `clientId` varchar(20) default NULL,
  `parent_id` varchar(20) default NULL,
  `hide` varchar(2) default '0',
  `Msg` varchar(100) default NULL,
  `wait_for_msg` varchar(100) default NULL,
  `type` varchar(100) default NULL,
  `type_info` varchar(100) default NULL,
  `createdate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;

/*Data for the table `ivr_master` */

insert  into `ivr_master`(`id`,`clientId`,`parent_id`,`hide`,`Msg`,`wait_for_msg`,`type`,`type_info`,`createdate`) values (1,'1','0','0','Complain',NULL,NULL,NULL,'2016-02-27 19:08:36'),(2,'5','0','0','Welcome',NULL,NULL,NULL,'2016-02-27 19:09:49'),(3,'5','2','0','request accepted',NULL,NULL,NULL,'2016-02-27 19:09:58'),(4,'5','3','0','permission',NULL,NULL,NULL,'2016-02-27 19:10:05'),(5,'5','6','0','enquery',NULL,NULL,NULL,'2016-02-27 19:10:17'),(6,'5','2','0','anil',NULL,NULL,NULL,'2016-02-27 19:11:11'),(66,'6','63','0','F1',NULL,NULL,NULL,'2016-03-10 12:43:28'),(72,'6','63','0','F2',NULL,NULL,NULL,'2016-03-10 13:25:55'),(57,'7','0','0','Welcome',NULL,NULL,NULL,'2016-03-10 12:01:26'),(16,NULL,'0','0','Welcome',NULL,NULL,NULL,'2016-03-09 19:22:37'),(17,'2','0','0','Welcome',NULL,NULL,NULL,'2016-03-10 11:25:03'),(18,'3','0','0','Welcome',NULL,NULL,NULL,'2016-03-10 11:25:06'),(19,'4','0','0','Welcome',NULL,NULL,NULL,'2016-03-10 11:25:14'),(63,'6','0','0','Welcome',NULL,NULL,NULL,'2016-03-10 12:42:57'),(21,'','0','0','Welcome',NULL,NULL,NULL,'2016-03-10 11:26:12'),(70,'1','1','0','Request',NULL,NULL,NULL,'2016-03-10 12:56:33'),(74,'1','1','0','Service',NULL,NULL,NULL,'2016-03-26 12:20:56');

/*Table structure for table `logincreation_master` */

DROP TABLE IF EXISTS `logincreation_master`;

CREATE TABLE `logincreation_master` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `create_id` int(11) default NULL,
  `name` varchar(255) default NULL,
  `phone` bigint(20) default NULL,
  `username` varchar(255) default NULL,
  `designation` varchar(255) default NULL,
  `user_right` varchar(255) default NULL,
  `password` varchar(255) default NULL,
  `password2` varchar(255) default NULL,
  `active` varchar(5) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

/*Data for the table `logincreation_master` */

insert  into `logincreation_master`(`id`,`create_id`,`name`,`phone`,`username`,`designation`,`user_right`,`password`,`password2`,`active`) values (3,4,'Chandresh Tripathi',4444444444,'chandresh.tripathi@teammas.in123','Develop','2,3','india123','india123','0'),(4,2,'Rajesh',5555555555,'rajesh@gmail.com','java','2,3,4,5','india123','india123','0'),(5,2,'Manoj Kumar',6666666666,'manoj@gmail.com','java','2,3','india123','india123','0'),(7,2,'sunil',1234567890,'sunil@gmail.com','agent','1,2','12345','india123','0'),(8,2,'panjaj',1245,'panjaj@gmail.com','agent','1','12345','india123','0'),(9,2,'manoj',78945,'manoj@yahoo.co.in','agent','1','12345','india123','0'),(10,2,'saroj',87459,'saroj@hotmail.com','agnet','1','12345','india123','0'),(11,2,'neelam',4715,'neelam@ibibo.com','agent','1','12345','india123','0'),(12,2,'shasha',4598,'shasha@rediffmail.com','agent','1','12345','india123','0'),(15,4,'Chandan Kumar',7867765654,'chandresh.mani001@gmail.com','Development','2,3,4','india123','india123','0'),(22,6,'Chandresh Mani Tripathi',1111111111,'chandresh.tripathi@teammas.in','PHP Developer','2,3','$2a$10$xrpbaOFlhDVWQs.F.HkkIuUQZVfyxvoL/BE/ZQrbEcUcWDCtVNL5a','india123','0'),(21,6,'Rajesh',2222222222,'amit@gmail.com','Php','2,3','$2a$10$yM5kW4BUrtrUY9uHLiX4y.AyWn1ZWIF.n30ORAVXzAMRqydYVAt5u','india123','0'),(23,5,'shilpa',1234567890,'shilpa.jain@teammas.in','developer','3','$2a$10$KfSv8Gr4XuUHYshYj3tuPuzCO943hJVNfQHHgkbDUem2N6WpGZxrK','123','0'),(24,1,'Chandresh',7777777777,'chandresh.tripathi@teammas.in','Contant writer','1,2','$2a$10$25qrev3gufUqorWWnpEO.e45xrD0cnfOkUIjxajD5pm2krsM8K1xO','india123','0');

/*Table structure for table `misreportmatrix_master` */

DROP TABLE IF EXISTS `misreportmatrix_master`;

CREATE TABLE `misreportmatrix_master` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `client_id` int(11) default NULL,
  `email_id` varchar(255) default NULL,
  `email_status` varchar(255) default NULL,
  `sms_status` varchar(255) default NULL,
  `email_time` varchar(255) default NULL,
  `sms_time` varchar(255) default NULL,
  `create_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `data_status` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `misreportmatrix_master` */

/*Table structure for table `ob_allocation_name` */

DROP TABLE IF EXISTS `ob_allocation_name`;

CREATE TABLE `ob_allocation_name` (
  `id` int(10) NOT NULL auto_increment,
  `ClientId` int(10) default NULL,
  `CampaignId` int(10) default NULL,
  `AllocationName` varchar(100) default NULL,
  `CreateDate` datetime default NULL,
  `TotalCount` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `ob_allocation_name` */

insert  into `ob_allocation_name`(`id`,`ClientId`,`CampaignId`,`AllocationName`,`CreateDate`,`TotalCount`) values (1,6,1,'Chandresh','2016-03-05 16:40:14','5'),(2,1,3,'calling1','2016-03-05 16:58:47','8');

/*Table structure for table `ob_campaign` */

DROP TABLE IF EXISTS `ob_campaign`;

CREATE TABLE `ob_campaign` (
  `id` int(10) NOT NULL auto_increment,
  `ClientId` int(10) default NULL,
  `CampaignName` varchar(100) default NULL,
  `Field1` varchar(200) default NULL,
  `Field2` varchar(200) default NULL,
  `Field3` varchar(200) default NULL,
  `Field4` varchar(200) default NULL,
  `Field5` varchar(200) default NULL,
  `Field6` varchar(200) default NULL,
  `Field7` varchar(200) default NULL,
  `Field8` varchar(200) default NULL,
  `Field9` varchar(200) default NULL,
  `Field10` varchar(200) default NULL,
  `CreationDate` datetime default NULL,
  `TotalCount` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `ob_campaign` */

insert  into `ob_campaign`(`id`,`ClientId`,`CampaignName`,`Field1`,`Field2`,`Field3`,`Field4`,`Field5`,`Field6`,`Field7`,`Field8`,`Field9`,`Field10`,`CreationDate`,`TotalCount`) values (1,6,'First Campaign','MISDN','Client Name','Address','Contact','Complain Id',NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:23:17','5'),(2,6,'Second Campaign','Client Name','Address1','Address2','Contact1','Contact2','Complain Id','Land Mark',NULL,NULL,NULL,'2016-03-05 16:25:38','7'),(3,1,'compain solution','MSISDN','Name','Father','Mother','Product','ComplainNo','CallDate','SettlementDate','1','a','2016-03-05 16:44:50','8');

/*Table structure for table `ob_campaign_data` */

DROP TABLE IF EXISTS `ob_campaign_data`;

CREATE TABLE `ob_campaign_data` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `AllocationId` int(10) default NULL,
  `AgentId` int(10) default NULL,
  `Field1` varchar(200) default NULL,
  `Field2` varchar(200) default NULL,
  `Field3` varchar(200) default NULL,
  `Field4` varchar(200) default NULL,
  `Field5` varchar(200) default NULL,
  `Field6` varchar(200) default NULL,
  `Field7` varchar(200) default NULL,
  `Field8` varchar(200) default NULL,
  `Field9` varchar(200) default NULL,
  `Field10` varchar(200) default NULL,
  `DataStatus` varchar(100) default NULL,
  `CallDate` datetime default NULL,
  `CreationDate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=113 DEFAULT CHARSET=latin1;

/*Data for the table `ob_campaign_data` */

insert  into `ob_campaign_data`(`id`,`AllocationId`,`AgentId`,`Field1`,`Field2`,`Field3`,`Field4`,`Field5`,`Field6`,`Field7`,`Field8`,`Field9`,`Field10`,`DataStatus`,`CallDate`,`CreationDate`) values (1,1,1,'6765654543','Chandresh1','Laxmi Nagar 1','7876765654','110012',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(2,1,1,'6765654544','Chandresh2','Laxmi Nagar 2','7876765655','110013',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(3,1,6,'6765654545','Chandresh3','Laxmi Nagar 3','7876765656','110014',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(4,1,6,'6765654546','Chandresh4','Laxmi Nagar 4','7876765657','110015',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(5,1,3,'6765654547','Chandresh5','Laxmi Nagar 5','7876765658','110016',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(6,1,3,'6765654548','Chandresh6','Laxmi Nagar 6','7876765659','110017',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(7,1,5,'6765654549','Chandresh7','Laxmi Nagar 7','7876765660','110018',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(8,1,5,'6765654550','Chandresh8','Laxmi Nagar 8','7876765661','110019',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(9,1,1,'6765654551','Chandresh9','Laxmi Nagar 9','7876765662','110020',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(10,1,NULL,'6765654552','Chandresh10','Laxmi Nagar 10','7876765663','110021',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(11,1,NULL,'6765654553','Chandresh11','Laxmi Nagar 11','7876765664','110022',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(12,1,NULL,'6765654554','Chandresh12','Laxmi Nagar 12','7876765665','110023',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(13,1,NULL,'6765654555','Chandresh13','Laxmi Nagar 13','7876765666','110024',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(14,1,NULL,'6765654556','Chandresh14','Laxmi Nagar 14','7876765667','110025',NULL,NULL,NULL,NULL,NULL,'call','2016-03-07 19:26:52','2016-03-05 16:40:14'),(15,1,NULL,'6765654557','Chandresh15','Laxmi Nagar 15','7876765668','110026',NULL,NULL,NULL,NULL,NULL,'call','2016-03-07 19:27:42','2016-03-05 16:40:14'),(16,1,NULL,'6765654558','Chandresh16','Laxmi Nagar 16','7876765669','110027',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(17,1,NULL,'6765654559','Chandresh17','Laxmi Nagar 17','7876765670','110028',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(18,1,NULL,'6765654560','Chandresh18','Laxmi Nagar 18','7876765671','110029',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(19,1,NULL,'6765654561','Chandresh19','Laxmi Nagar 19','7876765672','110030',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(20,1,NULL,'6765654562','Chandresh20','Laxmi Nagar 20','7876765673','110031',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(21,1,NULL,'6765654563','Chandresh21','Laxmi Nagar 21','7876765674','110032',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(22,1,NULL,'6765654564','Chandresh22','Laxmi Nagar 22','7876765675','110033',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(23,1,NULL,'6765654565','Chandresh23','Laxmi Nagar 23','7876765676','110034',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(24,1,NULL,'6765654566','Chandresh24','Laxmi Nagar 24','7876765677','110035',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(25,1,NULL,'6765654567','Chandresh25','Laxmi Nagar 25','7876765678','110036',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(26,1,NULL,'6765654568','Chandresh26','Laxmi Nagar 26','7876765679','110037',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(27,1,NULL,'6765654569','Chandresh27','Laxmi Nagar 27','7876765680','110038',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(28,1,NULL,'6765654570','Chandresh28','Laxmi Nagar 28','7876765681','110039',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(29,1,NULL,'6765654571','Chandresh29','Laxmi Nagar 29','7876765682','110040',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(30,1,NULL,'6765654572','Chandresh30','Laxmi Nagar 30','7876765683','110041',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(31,1,NULL,'6765654573','Chandresh31','Laxmi Nagar 31','7876765684','110042',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(32,1,NULL,'6765654574','Chandresh32','Laxmi Nagar 32','7876765685','110043',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(33,1,NULL,'6765654575','Chandresh33','Laxmi Nagar 33','7876765686','110044',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(34,1,NULL,'6765654576','Chandresh34','Laxmi Nagar 34','7876765687','110045',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(35,1,NULL,'6765654577','Chandresh35','Laxmi Nagar 35','7876765688','110046',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(36,1,NULL,'6765654578','Chandresh36','Laxmi Nagar 36','7876765689','110047',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(37,1,NULL,'6765654579','Chandresh37','Laxmi Nagar 37','7876765690','110048',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(38,1,NULL,'6765654580','Chandresh38','Laxmi Nagar 38','7876765691','110049',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(39,1,NULL,'6765654581','Chandresh39','Laxmi Nagar 39','7876765692','110050',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(40,1,NULL,'6765654582','Chandresh40','Laxmi Nagar 40','7876765693','110051',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(41,1,NULL,'6765654583','Chandresh41','Laxmi Nagar 41','7876765694','110052',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(42,1,NULL,'6765654584','Chandresh42','Laxmi Nagar 42','7876765695','110053',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(43,1,NULL,'6765654585','Chandresh43','Laxmi Nagar 43','7876765696','110054',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(44,1,NULL,'6765654586','Chandresh44','Laxmi Nagar 44','7876765697','110055',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(45,1,NULL,'6765654587','Chandresh45','Laxmi Nagar 45','7876765698','110056',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(46,1,NULL,'6765654588','Chandresh46','Laxmi Nagar 46','7876765699','110057',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(47,1,NULL,'6765654589','Chandresh47','Laxmi Nagar 47','7876765700','110058',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(48,1,NULL,'6765654590','Chandresh48','Laxmi Nagar 48','7876765701','110059',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(49,1,NULL,'6765654591','Chandresh49','Laxmi Nagar 49','7876765702','110060',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(50,1,NULL,'6765654592','Chandresh50','Laxmi Nagar 50','7876765703','110061',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(51,1,NULL,'6765654593','Chandresh51','Laxmi Nagar 51','7876765704','110062',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(52,1,NULL,'6765654594','Chandresh52','Laxmi Nagar 52','7876765705','110063',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(53,1,NULL,'6765654595','Chandresh53','Laxmi Nagar 53','7876765706','110064',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(54,1,NULL,'6765654596','Chandresh54','Laxmi Nagar 54','7876765707','110065',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(55,1,NULL,'6765654597','Chandresh55','Laxmi Nagar 55','7876765708','110066',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(56,1,NULL,'6765654598','Chandresh56','Laxmi Nagar 56','7876765709','110067',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(57,1,NULL,'6765654599','Chandresh57','Laxmi Nagar 57','7876765710','110068',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(58,1,NULL,'6765654600','Chandresh58','Laxmi Nagar 58','7876765711','110069',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(59,1,NULL,'6765654601','Chandresh59','Laxmi Nagar 59','7876765712','110070',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(60,1,NULL,'6765654602','Chandresh60','Laxmi Nagar 60','7876765713','110071',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(61,1,NULL,'6765654603','Chandresh61','Laxmi Nagar 61','7876765714','110072',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(62,1,NULL,'6765654604','Chandresh62','Laxmi Nagar 62','7876765715','110073',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(63,1,NULL,'6765654605','Chandresh63','Laxmi Nagar 63','7876765716','110074',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(64,1,NULL,'6765654606','Chandresh64','Laxmi Nagar 64','7876765717','110075',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(65,1,NULL,'6765654607','Chandresh65','Laxmi Nagar 65','7876765718','110076',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(66,1,NULL,'6765654608','Chandresh66','Laxmi Nagar 66','7876765719','110077',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(67,1,NULL,'6765654609','Chandresh67','Laxmi Nagar 67','7876765720','110078',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(68,1,NULL,'6765654610','Chandresh68','Laxmi Nagar 68','7876765721','110079',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(69,1,NULL,'6765654611','Chandresh69','Laxmi Nagar 69','7876765722','110080',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(70,1,NULL,'6765654612','Chandresh70','Laxmi Nagar 70','7876765723','110081',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(71,1,NULL,'6765654613','Chandresh71','Laxmi Nagar 71','7876765724','110082',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(72,1,NULL,'6765654614','Chandresh72','Laxmi Nagar 72','7876765725','110083',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(73,1,NULL,'6765654615','Chandresh73','Laxmi Nagar 73','7876765726','110084',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(74,1,NULL,'6765654616','Chandresh74','Laxmi Nagar 74','7876765727','110085',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(75,1,NULL,'6765654617','Chandresh75','Laxmi Nagar 75','7876765728','110086',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(76,1,NULL,'6765654618','Chandresh76','Laxmi Nagar 76','7876765729','110087',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(77,1,NULL,'6765654619','Chandresh77','Laxmi Nagar 77','7876765730','110088',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(78,1,NULL,'6765654620','Chandresh78','Laxmi Nagar 78','7876765731','110089',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(79,1,NULL,'6765654621','Chandresh79','Laxmi Nagar 79','7876765732','110090',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(80,1,NULL,'6765654622','Chandresh80','Laxmi Nagar 80','7876765733','110091',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(81,1,NULL,'6765654623','Chandresh81','Laxmi Nagar 81','7876765734','110092',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(82,1,NULL,'6765654624','Chandresh82','Laxmi Nagar 82','7876765735','110093',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(83,1,NULL,'6765654625','Chandresh83','Laxmi Nagar 83','7876765736','110094',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(84,1,NULL,'6765654626','Chandresh84','Laxmi Nagar 84','7876765737','110095',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(85,1,NULL,'6765654627','Chandresh85','Laxmi Nagar 85','7876765738','110096',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(86,1,NULL,'6765654628','Chandresh86','Laxmi Nagar 86','7876765739','110097',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(87,1,NULL,'6765654629','Chandresh87','Laxmi Nagar 87','7876765740','110098',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(88,1,NULL,'6765654630','Chandresh88','Laxmi Nagar 88','7876765741','110099',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(89,1,NULL,'6765654631','Chandresh89','Laxmi Nagar 89','7876765742','110100',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(90,1,NULL,'6765654632','Chandresh90','Laxmi Nagar 90','7876765743','110101',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(91,1,NULL,'6765654633','Chandresh91','Laxmi Nagar 91','7876765744','110102',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(92,1,NULL,'6765654634','Chandresh92','Laxmi Nagar 92','7876765745','110103',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(93,1,NULL,'6765654635','Chandresh93','Laxmi Nagar 93','7876765746','110104',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(94,1,NULL,'6765654636','Chandresh94','Laxmi Nagar 94','7876765747','110105',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(95,1,NULL,'6765654637','Chandresh95','Laxmi Nagar 95','7876765748','110106',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(96,1,NULL,'6765654638','Chandresh96','Laxmi Nagar 96','7876765749','110107',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(97,1,NULL,'6765654639','Chandresh97','Laxmi Nagar 97','7876765750','110108',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(98,1,NULL,'6765654640','Chandresh98','Laxmi Nagar 98','7876765751','110109',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(99,1,NULL,'6765654641','Chandresh99','Laxmi Nagar 99','7876765752','110110',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 16:40:14'),(100,2,1,'7894561238','mohan','sohan','sonali','laptop','12456','','Today',NULL,NULL,NULL,NULL,'2016-03-05 16:58:47'),(101,2,7,'5789456123','ajay','jai','sonam','computer','124567','','Today',NULL,NULL,'call','2016-03-07 19:30:40','2016-03-05 16:58:47'),(102,2,5,'8882240641','raghu','amar','mrityu','Hard Disk','124568','','Today',NULL,NULL,NULL,NULL,'2016-03-05 16:58:47'),(103,2,5,'1478523691','ranjan','rajnath','ambika','Motherboard','124569','','Today',NULL,NULL,NULL,NULL,'2016-03-05 16:58:47'),(104,2,5,'1596324781','suresh','ram','ramavati','System','12470','','Today',NULL,NULL,NULL,NULL,'2016-03-05 16:58:47'),(105,2,5,'1485796321','rajeev','kame','shanti','TFT','12471','','Today',NULL,NULL,NULL,NULL,'2016-03-05 16:58:47'),(0,2,5,'1478596231','mangal','dharmesh','rani','LCD','12472','','Today',NULL,NULL,NULL,NULL,'2016-03-05 16:58:47'),(107,2,5,'7894512361','ganesh','mahesh','parvati','Mobile','12473','','Today',NULL,NULL,NULL,NULL,'2016-03-05 16:58:47'),(108,2,5,'1478521478','bahadur','rambhadur','saraswati','CRT','12473','','Today',NULL,NULL,NULL,NULL,'2016-03-05 16:58:47'),(109,5,5,'123456',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(110,5,5,'123456',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 18:18:55',NULL),(111,5,5,'123456',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 18:20:56',NULL),(112,5,5,'123456',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-05 18:21:43',NULL);

/*Table structure for table `ob_ecr` */

DROP TABLE IF EXISTS `ob_ecr`;

CREATE TABLE `ob_ecr` (
  `id` int(11) NOT NULL auto_increment,
  `Campaign` varchar(50) default NULL,
  `client` varchar(100) default NULL,
  `createdate` datetime default NULL,
  `outbound` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1671 DEFAULT CHARSET=latin1;

/*Data for the table `ob_ecr` */

insert  into `ob_ecr`(`id`,`Campaign`,`client`,`createdate`,`outbound`) values (1,'1',NULL,'2016-03-14 18:58:01',NULL),(2,'1',NULL,'2016-03-14 18:59:01',NULL),(3,'1',NULL,'2016-03-14 19:00:01',NULL),(4,'1',NULL,'2016-03-14 19:01:01',NULL),(5,'1',NULL,'2016-03-14 19:02:01',NULL),(6,'1',NULL,'2016-03-14 19:03:01',NULL),(7,'1',NULL,'2016-03-14 19:04:01',NULL),(8,'1',NULL,'2016-03-14 19:05:01',NULL),(9,'1',NULL,'2016-03-14 19:06:01',NULL),(10,'1',NULL,'2016-03-14 19:07:01',NULL),(11,'1',NULL,'2016-03-14 19:08:01',NULL),(12,'1',NULL,'2016-03-14 19:09:01',NULL),(13,'1',NULL,'2016-03-14 19:10:01',NULL),(14,'1',NULL,'2016-03-14 19:11:01',NULL),(15,'1',NULL,'2016-03-14 19:12:01',NULL),(16,'1',NULL,'2016-03-14 19:13:01',NULL),(17,'1',NULL,'2016-03-14 19:14:01',NULL),(18,'1',NULL,'2016-03-14 19:15:01',NULL),(19,'1',NULL,'2016-03-14 19:16:01',NULL),(20,'1',NULL,'2016-03-14 19:17:01',NULL),(21,'1',NULL,'2016-03-14 19:18:01',NULL),(22,'1',NULL,'2016-03-14 19:19:01',NULL),(23,'1',NULL,'2016-03-14 19:20:01',NULL),(24,'1',NULL,'2016-03-14 19:21:01',NULL),(25,'1',NULL,'2016-03-14 19:22:01',NULL),(26,'1',NULL,'2016-03-14 19:23:01',NULL),(27,'1',NULL,'2016-03-14 19:24:01',NULL),(28,'1',NULL,'2016-03-14 19:25:01',NULL),(29,'1',NULL,'2016-03-14 19:26:01',NULL),(30,'1',NULL,'2016-03-14 19:27:01',NULL),(31,'1',NULL,'2016-03-14 19:28:01',NULL),(32,'1',NULL,'2016-03-14 19:29:01',NULL),(33,'1',NULL,'2016-03-14 19:30:01',NULL),(34,'1',NULL,'2016-03-14 19:31:01',NULL),(35,'1',NULL,'2016-03-14 19:32:01',NULL),(36,'1',NULL,'2016-03-14 19:33:01',NULL),(37,'1',NULL,'2016-03-14 19:34:01',NULL),(38,'1',NULL,'2016-03-14 19:35:01',NULL),(39,'1',NULL,'2016-03-14 19:36:01',NULL),(40,'1',NULL,'2016-03-14 19:37:01',NULL),(41,'1',NULL,'2016-03-14 19:38:02',NULL),(42,'1',NULL,'2016-03-14 19:39:01',NULL),(43,'1',NULL,'2016-03-14 19:40:01',NULL),(44,'1',NULL,'2016-03-14 19:41:01',NULL),(45,'1',NULL,'2016-03-14 19:42:01',NULL),(46,'1',NULL,'2016-03-14 19:43:01',NULL),(47,'1',NULL,'2016-03-14 19:44:01',NULL),(48,'1',NULL,'2016-03-14 19:45:01',NULL),(49,'1',NULL,'2016-03-14 19:46:01',NULL),(50,'1',NULL,'2016-03-14 19:47:01',NULL),(51,'1',NULL,'2016-03-14 19:48:01',NULL),(52,'1',NULL,'2016-03-14 19:49:01',NULL),(53,'1',NULL,'2016-03-14 19:50:01',NULL),(54,'1',NULL,'2016-03-14 19:51:01',NULL),(55,'1',NULL,'2016-03-14 19:52:01',NULL),(56,'1',NULL,'2016-03-15 10:14:03',NULL),(57,'1',NULL,'2016-03-15 10:15:01',NULL),(58,'1',NULL,'2016-03-15 10:16:01',NULL),(59,'1',NULL,'2016-03-15 10:17:01',NULL),(60,'1',NULL,'2016-03-15 10:18:01',NULL),(61,'1',NULL,'2016-03-15 10:19:01',NULL),(62,'1',NULL,'2016-03-15 10:20:01',NULL),(63,'1',NULL,'2016-03-15 10:21:01',NULL),(64,'1',NULL,'2016-03-15 10:22:01',NULL),(65,'1',NULL,'2016-03-15 10:23:01',NULL),(66,'1',NULL,'2016-03-15 10:24:01',NULL),(67,'1',NULL,'2016-03-15 10:25:01',NULL),(68,'1',NULL,'2016-03-15 10:26:01',NULL),(69,'1',NULL,'2016-03-15 10:27:01',NULL),(70,'1',NULL,'2016-03-15 10:28:01',NULL),(71,'1',NULL,'2016-03-15 10:29:01',NULL),(72,'1',NULL,'2016-03-15 10:30:01',NULL),(73,'1',NULL,'2016-03-15 10:31:01',NULL),(74,'1',NULL,'2016-03-15 10:32:01',NULL),(75,'1',NULL,'2016-03-15 10:33:02',NULL),(76,'1',NULL,'2016-03-15 10:34:01',NULL),(77,'1',NULL,'2016-03-15 10:35:01',NULL),(78,'1',NULL,'2016-03-15 10:36:01',NULL),(79,'1',NULL,'2016-03-15 10:37:01',NULL),(80,'1',NULL,'2016-03-15 10:38:01',NULL),(81,'1',NULL,'2016-03-15 10:39:01',NULL),(82,'1',NULL,'2016-03-15 10:40:01',NULL),(83,'1',NULL,'2016-03-15 10:41:01',NULL),(84,'1',NULL,'2016-03-15 10:42:01',NULL),(85,'1',NULL,'2016-03-15 10:43:01',NULL),(86,'1',NULL,'2016-03-15 10:44:01',NULL),(87,'1',NULL,'2016-03-15 10:45:01',NULL),(88,'1',NULL,'2016-03-15 10:46:01',NULL),(89,'1',NULL,'2016-03-15 10:47:01',NULL),(90,'1',NULL,'2016-03-15 10:48:01',NULL),(91,'1',NULL,'2016-03-15 10:49:01',NULL),(92,'1',NULL,'2016-03-15 10:50:01',NULL),(93,'1',NULL,'2016-03-15 10:51:01',NULL),(94,'1',NULL,'2016-03-15 10:52:01',NULL),(95,'1',NULL,'2016-03-15 10:53:01',NULL),(96,'1',NULL,'2016-03-15 10:54:01',NULL),(97,'1',NULL,'2016-03-15 10:55:01',NULL),(98,'1',NULL,'2016-03-15 10:56:01',NULL),(99,'1',NULL,'2016-03-15 10:57:01',NULL),(100,'1',NULL,'2016-03-15 10:58:01',NULL),(101,'1',NULL,'2016-03-15 10:59:01',NULL),(102,'1',NULL,'2016-03-15 11:00:01',NULL),(103,'1',NULL,'2016-03-15 11:01:01',NULL),(104,'1',NULL,'2016-03-15 11:02:01',NULL),(105,'1',NULL,'2016-03-15 11:03:01',NULL),(106,'1',NULL,'2016-03-15 11:04:01',NULL),(107,'1',NULL,'2016-03-15 11:05:01',NULL),(108,'1',NULL,'2016-03-15 11:06:01',NULL),(109,'1',NULL,'2016-03-15 11:07:01',NULL),(110,'1',NULL,'2016-03-15 11:08:01',NULL),(111,'1',NULL,'2016-03-15 11:09:01',NULL),(112,'1',NULL,'2016-03-15 11:10:01',NULL),(113,'1',NULL,'2016-03-15 11:11:01',NULL),(114,'1',NULL,'2016-03-15 11:12:01',NULL),(115,'1',NULL,'2016-03-15 11:13:01',NULL),(116,'1',NULL,'2016-03-15 11:14:01',NULL),(117,'1',NULL,'2016-03-15 11:15:01',NULL),(118,'1',NULL,'2016-03-15 11:16:01',NULL),(119,'1',NULL,'2016-03-15 11:17:01',NULL),(120,'1',NULL,'2016-03-15 11:18:01',NULL),(121,'1',NULL,'2016-03-15 11:19:01',NULL),(122,'1',NULL,'2016-03-15 11:20:01',NULL),(123,'1',NULL,'2016-03-15 11:21:01',NULL),(124,'1',NULL,'2016-03-15 11:22:01',NULL),(125,'1',NULL,'2016-03-15 11:23:01',NULL),(126,'1',NULL,'2016-03-15 11:24:01',NULL),(127,'1',NULL,'2016-03-15 11:25:01',NULL),(128,'1',NULL,'2016-03-15 11:26:01',NULL),(129,'1',NULL,'2016-03-15 11:27:01',NULL),(130,'1',NULL,'2016-03-15 11:28:01',NULL),(131,'1',NULL,'2016-03-15 11:29:01',NULL),(132,'1',NULL,'2016-03-15 11:30:01',NULL),(133,'1',NULL,'2016-03-15 11:31:01',NULL),(134,'1',NULL,'2016-03-15 11:32:01',NULL),(135,'1',NULL,'2016-03-15 11:33:01',NULL),(136,'1',NULL,'2016-03-15 11:34:01',NULL),(137,'1',NULL,'2016-03-15 11:35:01',NULL),(138,'1',NULL,'2016-03-15 11:36:01',NULL),(139,'1',NULL,'2016-03-15 11:37:01',NULL),(140,'1',NULL,'2016-03-15 11:38:01',NULL),(141,'1',NULL,'2016-03-15 11:39:01',NULL),(142,'1',NULL,'2016-03-15 11:40:01',NULL),(143,'1',NULL,'2016-03-15 11:41:01',NULL),(144,'1',NULL,'2016-03-15 11:42:01',NULL),(145,'1',NULL,'2016-03-15 11:43:01',NULL),(146,'1',NULL,'2016-03-15 11:44:01',NULL),(147,'1',NULL,'2016-03-15 11:45:01',NULL),(148,'1',NULL,'2016-03-15 11:46:01',NULL),(149,'1',NULL,'2016-03-15 11:47:01',NULL),(150,'1',NULL,'2016-03-15 11:48:01',NULL),(151,'1',NULL,'2016-03-15 11:49:01',NULL),(152,'1',NULL,'2016-03-15 11:50:01',NULL),(153,'1',NULL,'2016-03-15 11:51:01',NULL),(154,'1',NULL,'2016-03-15 11:52:01',NULL),(155,'1',NULL,'2016-03-15 11:53:01',NULL),(156,'1',NULL,'2016-03-15 11:54:02',NULL),(157,'1',NULL,'2016-03-15 11:55:01',NULL),(158,'1',NULL,'2016-03-15 11:56:01',NULL),(159,'1',NULL,'2016-03-15 11:57:01',NULL),(160,'1',NULL,'2016-03-15 11:58:01',NULL),(161,'1',NULL,'2016-03-15 11:59:01',NULL),(162,'1',NULL,'2016-03-15 12:00:01',NULL),(163,'1',NULL,'2016-03-15 12:01:01',NULL),(164,'1',NULL,'2016-03-15 12:02:01',NULL),(165,'1',NULL,'2016-03-15 12:03:01',NULL),(166,'1',NULL,'2016-03-15 12:04:01',NULL),(167,'1',NULL,'2016-03-15 12:05:01',NULL),(168,'1',NULL,'2016-03-15 12:06:01',NULL),(169,'1',NULL,'2016-03-15 12:07:01',NULL),(170,'1',NULL,'2016-03-15 12:08:01',NULL),(171,'1',NULL,'2016-03-15 12:09:01',NULL),(172,'1',NULL,'2016-03-15 12:10:01',NULL),(173,'1',NULL,'2016-03-15 12:11:01',NULL),(174,'1',NULL,'2016-03-15 12:12:01',NULL),(175,'1',NULL,'2016-03-15 12:13:01',NULL),(176,'1',NULL,'2016-03-15 12:14:01',NULL),(177,'1',NULL,'2016-03-15 12:15:01',NULL),(178,'1',NULL,'2016-03-15 12:16:01',NULL),(179,'1',NULL,'2016-03-15 12:17:02',NULL),(180,'1',NULL,'2016-03-15 12:18:01',NULL),(181,'1',NULL,'2016-03-15 12:19:01',NULL),(182,'1',NULL,'2016-03-15 12:20:01',NULL),(183,'1',NULL,'2016-03-15 12:21:01',NULL),(184,'1',NULL,'2016-03-15 12:22:01',NULL),(185,'1',NULL,'2016-03-15 12:23:02',NULL),(186,'1',NULL,'2016-03-15 12:24:01',NULL),(187,'1',NULL,'2016-03-15 12:25:01',NULL),(188,'1',NULL,'2016-03-15 12:26:01',NULL),(189,'1',NULL,'2016-03-15 12:27:01',NULL),(190,'1',NULL,'2016-03-15 12:28:01',NULL),(191,'1',NULL,'2016-03-15 12:29:01',NULL),(192,'1',NULL,'2016-03-15 12:30:01',NULL),(193,'1',NULL,'2016-03-15 12:31:01',NULL),(194,'1',NULL,'2016-03-15 12:32:01',NULL),(195,'1',NULL,'2016-03-15 12:33:01',NULL),(196,'1',NULL,'2016-03-15 12:34:01',NULL),(197,'1',NULL,'2016-03-15 12:35:01',NULL),(198,'1',NULL,'2016-03-15 12:36:01',NULL),(199,'1',NULL,'2016-03-15 12:37:01',NULL),(200,'1',NULL,'2016-03-15 12:38:01',NULL),(201,'1',NULL,'2016-03-15 12:39:01',NULL),(202,'1',NULL,'2016-03-15 12:40:01',NULL),(203,'1',NULL,'2016-03-15 12:41:01',NULL),(204,'1',NULL,'2016-03-15 12:42:01',NULL),(205,'1',NULL,'2016-03-15 12:43:01',NULL),(206,'1',NULL,'2016-03-15 12:44:01',NULL),(207,'1',NULL,'2016-03-15 12:45:01',NULL),(208,'1',NULL,'2016-03-15 12:46:01',NULL),(209,'1',NULL,'2016-03-15 12:47:01',NULL),(210,'1',NULL,'2016-03-15 12:48:01',NULL),(211,'1',NULL,'2016-03-15 12:49:01',NULL),(212,'1',NULL,'2016-03-15 12:50:01',NULL),(213,'1',NULL,'2016-03-15 12:51:01',NULL),(214,'1',NULL,'2016-03-15 12:52:01',NULL),(215,'1',NULL,'2016-03-15 12:53:01',NULL),(216,'1',NULL,'2016-03-15 12:54:01',NULL),(217,'1',NULL,'2016-03-15 12:55:01',NULL),(218,'1',NULL,'2016-03-15 12:56:01',NULL),(219,'1',NULL,'2016-03-15 12:57:01',NULL),(220,'1',NULL,'2016-03-15 12:58:01',NULL),(221,'1',NULL,'2016-03-15 12:59:01',NULL),(222,'1',NULL,'2016-03-15 13:00:01',NULL),(223,'1',NULL,'2016-03-15 13:01:01',NULL),(224,'1',NULL,'2016-03-15 13:02:01',NULL),(225,'1',NULL,'2016-03-15 13:03:02',NULL),(226,'1',NULL,'2016-03-15 13:04:01',NULL),(227,'1',NULL,'2016-03-15 13:05:01',NULL),(228,'1',NULL,'2016-03-15 13:06:01',NULL),(229,'1',NULL,'2016-03-15 13:07:01',NULL),(230,'1',NULL,'2016-03-15 13:08:01',NULL),(231,'1',NULL,'2016-03-15 13:09:01',NULL),(232,'1',NULL,'2016-03-15 13:10:01',NULL),(233,'1',NULL,'2016-03-15 13:11:01',NULL),(234,'1',NULL,'2016-03-15 13:12:01',NULL),(235,'1',NULL,'2016-03-15 13:13:01',NULL),(236,'1',NULL,'2016-03-15 13:14:01',NULL),(237,'1',NULL,'2016-03-15 13:15:01',NULL),(238,'1',NULL,'2016-03-15 13:16:02',NULL),(239,'1',NULL,'2016-03-15 13:17:01',NULL),(240,'1',NULL,'2016-03-15 13:18:01',NULL),(241,'1',NULL,'2016-03-15 13:19:01',NULL),(242,'1',NULL,'2016-03-15 13:20:01',NULL),(243,'1',NULL,'2016-03-15 13:21:01',NULL),(244,'1',NULL,'2016-03-15 13:22:01',NULL),(245,'1',NULL,'2016-03-15 13:23:01',NULL),(246,'1',NULL,'2016-03-15 13:24:01',NULL),(247,'1',NULL,'2016-03-15 13:25:01',NULL),(248,'1',NULL,'2016-03-15 13:26:01',NULL),(249,'1',NULL,'2016-03-15 13:27:01',NULL),(250,'1',NULL,'2016-03-15 13:28:01',NULL),(251,'1',NULL,'2016-03-15 13:29:01',NULL),(252,'1',NULL,'2016-03-15 13:30:01',NULL),(253,'1',NULL,'2016-03-15 13:31:01',NULL),(254,'1',NULL,'2016-03-15 13:32:01',NULL),(255,'1',NULL,'2016-03-15 13:33:01',NULL),(256,'1',NULL,'2016-03-15 13:34:01',NULL),(257,'1',NULL,'2016-03-15 13:35:01',NULL),(258,'1',NULL,'2016-03-15 13:36:01',NULL),(259,'1',NULL,'2016-03-15 13:37:01',NULL),(260,'1',NULL,'2016-03-15 13:38:01',NULL),(261,'1',NULL,'2016-03-15 13:39:01',NULL),(262,'1',NULL,'2016-03-15 13:40:01',NULL),(263,'1',NULL,'2016-03-15 13:41:02',NULL),(264,'1',NULL,'2016-03-15 13:42:01',NULL),(265,'1',NULL,'2016-03-15 13:43:01',NULL),(266,'1',NULL,'2016-03-15 13:44:01',NULL),(267,'1',NULL,'2016-03-15 13:45:01',NULL),(268,'1',NULL,'2016-03-15 13:46:01',NULL),(269,'1',NULL,'2016-03-15 13:47:01',NULL),(270,'1',NULL,'2016-03-15 13:48:01',NULL),(271,'1',NULL,'2016-03-15 13:49:01',NULL),(272,'1',NULL,'2016-03-15 13:50:01',NULL),(273,'1',NULL,'2016-03-15 13:51:01',NULL),(274,'1',NULL,'2016-03-15 13:52:01',NULL),(275,'1',NULL,'2016-03-15 13:53:01',NULL),(276,'1',NULL,'2016-03-15 13:54:01',NULL),(277,'1',NULL,'2016-03-15 13:55:01',NULL),(278,'1',NULL,'2016-03-15 13:56:01',NULL),(279,'1',NULL,'2016-03-15 13:57:01',NULL),(280,'1',NULL,'2016-03-15 13:58:01',NULL),(281,'1',NULL,'2016-03-15 13:59:01',NULL),(282,'1',NULL,'2016-03-15 14:00:01',NULL),(283,'1',NULL,'2016-03-15 14:01:01',NULL),(284,'1',NULL,'2016-03-15 14:02:01',NULL),(285,'1',NULL,'2016-03-15 14:03:01',NULL),(286,'1',NULL,'2016-03-15 14:04:01',NULL),(287,'1',NULL,'2016-03-15 14:05:01',NULL),(288,'1',NULL,'2016-03-15 14:06:01',NULL),(289,'1',NULL,'2016-03-15 14:07:01',NULL),(290,'1',NULL,'2016-03-15 14:08:01',NULL),(291,'1',NULL,'2016-03-15 14:09:01',NULL),(292,'1',NULL,'2016-03-15 14:10:01',NULL),(293,'1',NULL,'2016-03-15 14:11:01',NULL),(294,'1',NULL,'2016-03-15 14:12:01',NULL),(295,'1',NULL,'2016-03-15 14:13:01',NULL),(296,'1',NULL,'2016-03-15 14:14:01',NULL),(297,'1',NULL,'2016-03-15 14:15:01',NULL),(298,'1',NULL,'2016-03-15 14:16:01',NULL),(299,'1',NULL,'2016-03-15 14:17:01',NULL),(300,'1',NULL,'2016-03-15 14:18:01',NULL),(301,'1',NULL,'2016-03-15 14:19:01',NULL),(302,'1',NULL,'2016-03-15 14:20:01',NULL),(303,'1',NULL,'2016-03-15 14:21:01',NULL),(304,'1',NULL,'2016-03-15 14:22:01',NULL),(305,'1',NULL,'2016-03-15 14:23:01',NULL),(306,'1',NULL,'2016-03-15 14:24:01',NULL),(307,'1',NULL,'2016-03-15 14:25:01',NULL),(308,'1',NULL,'2016-03-15 14:26:01',NULL),(309,'1',NULL,'2016-03-15 14:27:01',NULL),(310,'1',NULL,'2016-03-15 14:28:01',NULL),(311,'1',NULL,'2016-03-15 14:29:01',NULL),(312,'1',NULL,'2016-03-15 14:30:01',NULL),(313,'1',NULL,'2016-03-15 14:31:01',NULL),(314,'1',NULL,'2016-03-15 14:32:01',NULL),(315,'1',NULL,'2016-03-15 14:33:01',NULL),(316,'1',NULL,'2016-03-15 14:34:01',NULL),(317,'1',NULL,'2016-03-15 14:35:01',NULL),(318,'1',NULL,'2016-03-15 14:36:01',NULL),(319,'1',NULL,'2016-03-15 14:37:01',NULL),(320,'1',NULL,'2016-03-15 14:38:01',NULL),(321,'1',NULL,'2016-03-15 14:39:01',NULL),(322,'1',NULL,'2016-03-15 14:40:01',NULL),(323,'1',NULL,'2016-03-15 14:41:01',NULL),(324,'1',NULL,'2016-03-15 14:42:01',NULL),(325,'1',NULL,'2016-03-15 14:43:01',NULL),(326,'1',NULL,'2016-03-15 14:44:01',NULL),(327,'1',NULL,'2016-03-15 14:45:01',NULL),(328,'1',NULL,'2016-03-15 14:46:01',NULL),(329,'1',NULL,'2016-03-15 14:47:01',NULL),(330,'1',NULL,'2016-03-15 14:48:02',NULL),(331,'1',NULL,'2016-03-15 14:49:01',NULL),(332,'1',NULL,'2016-03-15 14:50:01',NULL),(333,'1',NULL,'2016-03-15 14:51:01',NULL),(334,'1',NULL,'2016-03-15 14:52:01',NULL),(335,'1',NULL,'2016-03-15 14:53:01',NULL),(336,'1',NULL,'2016-03-15 14:54:01',NULL),(337,'1',NULL,'2016-03-15 14:55:01',NULL),(338,'1',NULL,'2016-03-15 14:56:01',NULL),(339,'1',NULL,'2016-03-15 14:57:01',NULL),(340,'1',NULL,'2016-03-15 14:58:01',NULL),(341,'1',NULL,'2016-03-15 14:59:01',NULL),(342,'1',NULL,'2016-03-15 15:00:01',NULL),(343,'1',NULL,'2016-03-15 15:01:01',NULL),(344,'1',NULL,'2016-03-15 15:02:01',NULL),(345,'1',NULL,'2016-03-15 15:03:01',NULL),(346,'1',NULL,'2016-03-15 15:04:01',NULL),(347,'1',NULL,'2016-03-15 15:05:01',NULL),(348,'1',NULL,'2016-03-15 15:06:01',NULL),(349,'1',NULL,'2016-03-15 15:07:01',NULL),(350,'1',NULL,'2016-03-15 15:08:01',NULL),(351,'1',NULL,'2016-03-15 15:09:02',NULL),(352,'1',NULL,'2016-03-15 15:10:01',NULL),(353,'1',NULL,'2016-03-15 15:11:01',NULL),(354,'1',NULL,'2016-03-15 15:12:01',NULL),(355,'1',NULL,'2016-03-15 15:13:01',NULL),(356,'1',NULL,'2016-03-15 15:14:01',NULL),(357,'1',NULL,'2016-03-15 15:15:01',NULL),(358,'1',NULL,'2016-03-15 15:16:01',NULL),(359,'1',NULL,'2016-03-15 15:17:01',NULL),(360,'1',NULL,'2016-03-15 15:18:01',NULL),(361,'1',NULL,'2016-03-15 15:19:01',NULL),(362,'1',NULL,'2016-03-15 15:20:01',NULL),(363,'1',NULL,'2016-03-15 15:21:01',NULL),(364,'1',NULL,'2016-03-15 15:22:01',NULL),(365,'1',NULL,'2016-03-15 15:23:01',NULL),(366,'1',NULL,'2016-03-15 15:24:01',NULL),(367,'1',NULL,'2016-03-15 15:25:01',NULL),(368,'1',NULL,'2016-03-15 15:26:01',NULL),(369,'1',NULL,'2016-03-15 15:27:01',NULL),(370,'1',NULL,'2016-03-15 15:28:01',NULL),(371,'1',NULL,'2016-03-15 15:29:01',NULL),(372,'1',NULL,'2016-03-15 15:30:01',NULL),(373,'1',NULL,'2016-03-15 15:31:01',NULL),(374,'1',NULL,'2016-03-15 15:32:01',NULL),(375,'1',NULL,'2016-03-15 15:33:01',NULL),(376,'1',NULL,'2016-03-15 15:34:01',NULL),(377,'1',NULL,'2016-03-15 15:35:01',NULL),(378,'1',NULL,'2016-03-15 15:36:01',NULL),(379,'1',NULL,'2016-03-15 15:37:01',NULL),(380,'1',NULL,'2016-03-15 15:38:01',NULL),(381,'1',NULL,'2016-03-15 15:39:01',NULL),(382,'1',NULL,'2016-03-15 15:40:01',NULL),(383,'1',NULL,'2016-03-15 15:41:01',NULL),(384,'1',NULL,'2016-03-15 15:42:01',NULL),(385,'1',NULL,'2016-03-15 15:43:01',NULL),(386,'1',NULL,'2016-03-15 15:44:01',NULL),(387,'1',NULL,'2016-03-15 15:45:01',NULL),(388,'1',NULL,'2016-03-15 15:46:01',NULL),(389,'1',NULL,'2016-03-15 15:47:01',NULL),(390,'1',NULL,'2016-03-15 15:48:01',NULL),(391,'1',NULL,'2016-03-15 15:49:01',NULL),(392,'1',NULL,'2016-03-15 15:50:01',NULL),(393,'1',NULL,'2016-03-15 15:51:01',NULL),(394,'1',NULL,'2016-03-15 15:52:01',NULL),(395,'1',NULL,'2016-03-15 15:53:01',NULL),(396,'1',NULL,'2016-03-15 15:54:01',NULL),(397,'1',NULL,'2016-03-15 15:55:01',NULL),(398,'1',NULL,'2016-03-15 15:56:02',NULL),(399,'1',NULL,'2016-03-15 15:57:01',NULL),(400,'1',NULL,'2016-03-15 15:58:02',NULL),(401,'1',NULL,'2016-03-15 15:59:01',NULL),(402,'1',NULL,'2016-03-15 16:00:01',NULL),(403,'1',NULL,'2016-03-15 16:01:01',NULL),(404,'1',NULL,'2016-03-15 16:02:01',NULL),(405,'1',NULL,'2016-03-15 16:03:01',NULL),(406,'1',NULL,'2016-03-15 16:04:02',NULL),(407,'1',NULL,'2016-03-15 16:05:01',NULL),(408,'1',NULL,'2016-03-15 16:06:01',NULL),(409,'1',NULL,'2016-03-15 16:07:01',NULL),(410,'1',NULL,'2016-03-15 16:08:01',NULL),(411,'1',NULL,'2016-03-15 16:09:01',NULL),(412,'1',NULL,'2016-03-15 16:10:01',NULL),(413,'1',NULL,'2016-03-15 16:11:01',NULL),(414,'1',NULL,'2016-03-15 16:12:01',NULL),(415,'1',NULL,'2016-03-15 16:13:01',NULL),(416,'1',NULL,'2016-03-15 16:14:01',NULL),(417,'1',NULL,'2016-03-15 16:15:01',NULL),(418,'1',NULL,'2016-03-15 16:16:01',NULL),(419,'1',NULL,'2016-03-15 16:17:01',NULL),(420,'1',NULL,'2016-03-15 16:18:01',NULL),(421,'1',NULL,'2016-03-15 16:19:01',NULL),(422,'1',NULL,'2016-03-15 16:20:01',NULL),(423,'1',NULL,'2016-03-15 16:21:01',NULL),(424,'1',NULL,'2016-03-15 16:22:01',NULL),(425,'1',NULL,'2016-03-15 16:23:01',NULL),(426,'1',NULL,'2016-03-15 16:24:01',NULL),(427,'1',NULL,'2016-03-15 16:25:01',NULL),(428,'1',NULL,'2016-03-15 16:26:01',NULL),(429,'1',NULL,'2016-03-15 16:27:01',NULL),(430,'1',NULL,'2016-03-15 16:28:01',NULL),(431,'1',NULL,'2016-03-15 16:29:01',NULL),(432,'1',NULL,'2016-03-15 16:30:01',NULL),(433,'1',NULL,'2016-03-15 16:31:01',NULL),(434,'1',NULL,'2016-03-15 16:32:01',NULL),(435,'1',NULL,'2016-03-15 16:33:01',NULL),(436,'1',NULL,'2016-03-15 16:34:01',NULL),(437,'1',NULL,'2016-03-15 16:35:01',NULL),(438,'1',NULL,'2016-03-15 16:36:01',NULL),(439,'1',NULL,'2016-03-15 16:37:01',NULL),(440,'1',NULL,'2016-03-15 16:38:01',NULL),(441,'1',NULL,'2016-03-15 16:39:01',NULL),(442,'1',NULL,'2016-03-15 16:40:01',NULL),(443,'1',NULL,'2016-03-15 16:41:01',NULL),(444,'1',NULL,'2016-03-15 16:42:01',NULL),(445,'1',NULL,'2016-03-15 16:43:01',NULL),(446,'1',NULL,'2016-03-15 16:44:01',NULL),(447,'1',NULL,'2016-03-15 16:45:01',NULL),(448,'1',NULL,'2016-03-15 16:46:01',NULL),(449,'1',NULL,'2016-03-15 16:47:01',NULL),(450,'1',NULL,'2016-03-15 16:48:01',NULL),(451,'1',NULL,'2016-03-15 16:49:01',NULL),(452,'1',NULL,'2016-03-15 16:50:01',NULL),(453,'1',NULL,'2016-03-15 16:51:01',NULL),(454,'1',NULL,'2016-03-15 16:52:01',NULL),(455,'1',NULL,'2016-03-15 16:53:01',NULL),(456,'1',NULL,'2016-03-15 16:54:01',NULL),(457,'1',NULL,'2016-03-15 16:55:01',NULL),(458,'1',NULL,'2016-03-15 16:56:01',NULL),(459,'1',NULL,'2016-03-15 16:57:01',NULL),(460,'1',NULL,'2016-03-15 16:58:01',NULL),(461,'1',NULL,'2016-03-15 16:59:01',NULL),(462,'1',NULL,'2016-03-15 17:00:01',NULL),(463,'1',NULL,'2016-03-15 17:01:01',NULL),(464,'1',NULL,'2016-03-15 17:02:01',NULL),(465,'1',NULL,'2016-03-15 17:03:01',NULL),(466,'1',NULL,'2016-03-15 17:04:01',NULL),(467,'1',NULL,'2016-03-15 17:05:01',NULL),(468,'1',NULL,'2016-03-15 17:06:01',NULL),(469,'1',NULL,'2016-03-15 17:07:01',NULL),(470,'1',NULL,'2016-03-15 17:08:01',NULL),(471,'1',NULL,'2016-03-15 17:09:01',NULL),(472,'1',NULL,'2016-03-15 17:10:01',NULL),(473,'1',NULL,'2016-03-15 17:11:01',NULL),(474,'1',NULL,'2016-03-15 17:12:01',NULL),(475,'1',NULL,'2016-03-15 17:13:01',NULL),(476,'1',NULL,'2016-03-15 17:14:01',NULL),(477,'1',NULL,'2016-03-15 17:15:01',NULL),(478,'1',NULL,'2016-03-15 17:16:01',NULL),(479,'1',NULL,'2016-03-15 17:17:01',NULL),(480,'1',NULL,'2016-03-15 17:18:01',NULL),(481,'1',NULL,'2016-03-15 17:19:01',NULL),(482,'1',NULL,'2016-03-15 17:20:01',NULL),(483,'1',NULL,'2016-03-15 17:21:01',NULL),(484,'1',NULL,'2016-03-15 17:22:01',NULL),(485,'1',NULL,'2016-03-15 17:23:01',NULL),(486,'1',NULL,'2016-03-15 17:24:01',NULL),(487,'1',NULL,'2016-03-15 17:25:01',NULL),(488,'1',NULL,'2016-03-15 17:26:01',NULL),(489,'1',NULL,'2016-03-15 17:27:01',NULL),(490,'1',NULL,'2016-03-15 17:28:01',NULL),(491,'1',NULL,'2016-03-15 17:29:01',NULL),(492,'1',NULL,'2016-03-15 17:30:01',NULL),(493,'1',NULL,'2016-03-15 17:31:01',NULL),(494,'1',NULL,'2016-03-15 17:32:01',NULL),(495,'1',NULL,'2016-03-15 17:33:02',NULL),(496,'1',NULL,'2016-03-15 17:34:01',NULL),(497,'1',NULL,'2016-03-15 17:35:01',NULL),(498,'1',NULL,'2016-03-15 17:36:01',NULL),(499,'1',NULL,'2016-03-15 17:37:01',NULL),(500,'1',NULL,'2016-03-15 17:38:01',NULL),(501,'1',NULL,'2016-03-15 17:39:01',NULL),(502,'1',NULL,'2016-03-15 17:40:02',NULL),(503,'1',NULL,'2016-03-15 17:41:01',NULL),(504,'1',NULL,'2016-03-15 17:42:01',NULL),(505,'1',NULL,'2016-03-15 17:43:01',NULL),(506,'1',NULL,'2016-03-15 17:44:01',NULL),(507,'1',NULL,'2016-03-15 17:45:01',NULL),(508,'1',NULL,'2016-03-15 17:46:01',NULL),(509,'1',NULL,'2016-03-15 17:47:01',NULL),(510,'1',NULL,'2016-03-15 17:48:01',NULL),(511,'1',NULL,'2016-03-15 17:49:01',NULL),(512,'1',NULL,'2016-03-15 17:50:01',NULL),(513,'1',NULL,'2016-03-15 17:51:01',NULL),(514,'1',NULL,'2016-03-15 17:52:01',NULL),(515,'1',NULL,'2016-03-15 17:53:01',NULL),(516,'1',NULL,'2016-03-15 17:54:01',NULL),(517,'1',NULL,'2016-03-15 17:55:01',NULL),(518,'1',NULL,'2016-03-15 17:56:01',NULL),(519,'1',NULL,'2016-03-15 17:57:01',NULL),(520,'1',NULL,'2016-03-15 17:58:01',NULL),(521,'1',NULL,'2016-03-15 17:59:01',NULL),(522,'1',NULL,'2016-03-15 18:00:01',NULL),(523,'1',NULL,'2016-03-15 18:01:01',NULL),(524,'1',NULL,'2016-03-15 18:02:01',NULL),(525,'1',NULL,'2016-03-15 18:03:01',NULL),(526,'1',NULL,'2016-03-15 18:04:01',NULL),(527,'1',NULL,'2016-03-15 18:05:01',NULL),(528,'1',NULL,'2016-03-15 18:06:01',NULL),(529,'1',NULL,'2016-03-15 18:07:01',NULL),(530,'1',NULL,'2016-03-15 18:08:01',NULL),(531,'1',NULL,'2016-03-15 18:09:01',NULL),(532,'1',NULL,'2016-03-15 18:10:01',NULL),(533,'1',NULL,'2016-03-15 18:11:01',NULL),(534,'1',NULL,'2016-03-15 18:12:01',NULL),(535,'1',NULL,'2016-03-15 18:13:01',NULL),(536,'1',NULL,'2016-03-15 18:14:01',NULL),(537,'1',NULL,'2016-03-15 18:15:01',NULL),(538,'1',NULL,'2016-03-15 18:16:01',NULL),(539,'1',NULL,'2016-03-15 18:17:01',NULL),(540,'1',NULL,'2016-03-15 18:18:01',NULL),(541,'1',NULL,'2016-03-15 18:19:01',NULL),(542,'1',NULL,'2016-03-15 18:20:01',NULL),(543,'1',NULL,'2016-03-15 18:21:01',NULL),(544,'1',NULL,'2016-03-15 18:22:01',NULL),(545,'1',NULL,'2016-03-15 18:23:01',NULL),(546,'1',NULL,'2016-03-15 18:24:01',NULL),(547,'1',NULL,'2016-03-15 18:25:01',NULL),(548,'1',NULL,'2016-03-15 18:26:01',NULL),(549,'1',NULL,'2016-03-15 18:27:01',NULL),(550,'1',NULL,'2016-03-15 18:28:01',NULL),(551,'1',NULL,'2016-03-15 18:29:02',NULL),(552,'1',NULL,'2016-03-15 18:30:01',NULL),(553,'1',NULL,'2016-03-15 18:31:01',NULL),(554,'1',NULL,'2016-03-15 18:32:02',NULL),(555,'1',NULL,'2016-03-15 18:33:01',NULL),(556,'1',NULL,'2016-03-15 18:34:01',NULL),(557,'1',NULL,'2016-03-15 18:35:01',NULL),(558,'1',NULL,'2016-03-15 18:36:01',NULL),(559,'1',NULL,'2016-03-15 18:37:01',NULL),(560,'1',NULL,'2016-03-15 18:38:01',NULL),(561,'1',NULL,'2016-03-15 18:39:01',NULL),(562,'1',NULL,'2016-03-15 18:40:01',NULL),(563,'1',NULL,'2016-03-15 18:41:01',NULL),(564,'1',NULL,'2016-03-15 18:42:01',NULL),(565,'1',NULL,'2016-03-15 18:43:01',NULL),(566,'1',NULL,'2016-03-15 18:44:01',NULL),(567,'1',NULL,'2016-03-15 18:45:01',NULL),(568,'1',NULL,'2016-03-15 18:46:01',NULL),(569,'1',NULL,'2016-03-15 18:47:01',NULL),(570,'1',NULL,'2016-03-15 18:48:01',NULL),(571,'1',NULL,'2016-03-15 18:49:01',NULL),(572,'1',NULL,'2016-03-15 18:50:01',NULL),(573,'1',NULL,'2016-03-15 18:51:01',NULL),(574,'1',NULL,'2016-03-15 18:52:01',NULL),(575,'1',NULL,'2016-03-15 18:53:01',NULL),(576,'1',NULL,'2016-03-15 18:54:01',NULL),(577,'1',NULL,'2016-03-15 18:55:01',NULL),(578,'1',NULL,'2016-03-15 18:56:01',NULL),(579,'1',NULL,'2016-03-15 18:57:01',NULL),(580,'1',NULL,'2016-03-15 18:58:01',NULL),(581,'1',NULL,'2016-03-15 18:59:01',NULL),(582,'1',NULL,'2016-03-15 19:00:01',NULL),(583,'1',NULL,'2016-03-15 19:01:01',NULL),(584,'1',NULL,'2016-03-15 19:02:01',NULL),(585,'1',NULL,'2016-03-15 19:03:01',NULL),(586,'1',NULL,'2016-03-15 19:04:01',NULL),(587,'1',NULL,'2016-03-15 19:05:01',NULL),(588,'1',NULL,'2016-03-15 19:06:01',NULL),(589,'1',NULL,'2016-03-15 19:07:01',NULL),(590,'1',NULL,'2016-03-15 19:08:01',NULL),(591,'1',NULL,'2016-03-15 19:09:01',NULL),(592,'1',NULL,'2016-03-15 19:10:01',NULL),(593,'1',NULL,'2016-03-15 19:11:01',NULL),(594,'1',NULL,'2016-03-15 19:12:01',NULL),(595,'1',NULL,'2016-03-15 19:13:01',NULL),(596,'1',NULL,'2016-03-15 19:14:01',NULL),(597,'1',NULL,'2016-03-15 19:15:01',NULL),(598,'1',NULL,'2016-03-15 19:16:01',NULL),(599,'1',NULL,'2016-03-15 19:17:01',NULL),(600,'1',NULL,'2016-03-15 19:18:01',NULL),(601,'1',NULL,'2016-03-15 19:19:01',NULL),(602,'1',NULL,'2016-03-16 10:02:03',NULL),(603,'1',NULL,'2016-03-16 10:03:01',NULL),(604,'1',NULL,'2016-03-16 10:04:01',NULL),(605,'1',NULL,'2016-03-16 10:05:01',NULL),(606,'1',NULL,'2016-03-16 10:06:01',NULL),(607,'1',NULL,'2016-03-16 10:07:01',NULL),(608,'1',NULL,'2016-03-16 10:08:01',NULL),(609,'1',NULL,'2016-03-16 10:09:01',NULL),(610,'1',NULL,'2016-03-16 10:10:01',NULL),(611,'1',NULL,'2016-03-16 10:11:01',NULL),(612,'1',NULL,'2016-03-16 10:12:01',NULL),(613,'1',NULL,'2016-03-16 10:13:01',NULL),(614,'1',NULL,'2016-03-16 10:14:01',NULL),(615,'1',NULL,'2016-03-16 10:15:01',NULL),(616,'1',NULL,'2016-03-16 10:16:01',NULL),(617,'1',NULL,'2016-03-16 10:17:01',NULL),(618,'1',NULL,'2016-03-16 10:18:01',NULL),(619,'1',NULL,'2016-03-16 10:19:01',NULL),(620,'1',NULL,'2016-03-16 10:20:01',NULL),(621,'1',NULL,'2016-03-16 10:21:01',NULL),(622,'1',NULL,'2016-03-16 10:22:01',NULL),(623,'1',NULL,'2016-03-16 10:23:01',NULL),(624,'1',NULL,'2016-03-16 10:24:01',NULL),(625,'1',NULL,'2016-03-16 10:25:01',NULL),(626,'1',NULL,'2016-03-16 10:26:01',NULL),(627,'1',NULL,'2016-03-16 10:27:01',NULL),(628,'1',NULL,'2016-03-16 10:28:01',NULL),(629,'1',NULL,'2016-03-16 10:29:01',NULL),(630,'1',NULL,'2016-03-16 10:30:02',NULL),(631,'1',NULL,'2016-03-16 10:31:01',NULL),(632,'1',NULL,'2016-03-16 10:32:01',NULL),(633,'1',NULL,'2016-03-16 10:33:01',NULL),(634,'1',NULL,'2016-03-16 10:34:01',NULL),(635,'1',NULL,'2016-03-16 10:35:01',NULL),(636,'1',NULL,'2016-03-16 10:36:01',NULL),(637,'1',NULL,'2016-03-16 10:37:01',NULL),(638,'1',NULL,'2016-03-16 10:38:01',NULL),(639,'1',NULL,'2016-03-16 10:39:01',NULL),(640,'1',NULL,'2016-03-16 10:40:01',NULL),(641,'1',NULL,'2016-03-16 10:41:01',NULL),(642,'1',NULL,'2016-03-16 10:42:01',NULL),(643,'1',NULL,'2016-03-16 10:43:02',NULL),(644,'1',NULL,'2016-03-16 10:44:01',NULL),(645,'1',NULL,'2016-03-16 10:45:01',NULL),(646,'1',NULL,'2016-03-16 10:46:01',NULL),(647,'1',NULL,'2016-03-16 10:47:01',NULL),(648,'1',NULL,'2016-03-16 10:48:01',NULL),(649,'1',NULL,'2016-03-16 10:49:01',NULL),(650,'1',NULL,'2016-03-16 10:50:01',NULL),(651,'1',NULL,'2016-03-16 10:51:02',NULL),(652,'1',NULL,'2016-03-16 10:52:01',NULL),(653,'1',NULL,'2016-03-16 10:53:01',NULL),(654,'1',NULL,'2016-03-16 10:54:01',NULL),(655,'1',NULL,'2016-03-16 10:55:01',NULL),(656,'1',NULL,'2016-03-16 10:56:01',NULL),(657,'1',NULL,'2016-03-16 10:57:01',NULL),(658,'1',NULL,'2016-03-16 10:58:01',NULL),(659,'1',NULL,'2016-03-16 10:59:01',NULL),(660,'1',NULL,'2016-03-16 11:00:01',NULL),(661,'1',NULL,'2016-03-16 11:01:01',NULL),(662,'1',NULL,'2016-03-16 11:02:01',NULL),(663,'1',NULL,'2016-03-16 11:03:01',NULL),(664,'1',NULL,'2016-03-16 11:04:01',NULL),(665,'1',NULL,'2016-03-16 11:05:01',NULL),(666,'1',NULL,'2016-03-16 11:06:01',NULL),(667,'1',NULL,'2016-03-16 11:07:01',NULL),(668,'1',NULL,'2016-03-16 11:08:01',NULL),(669,'1',NULL,'2016-03-16 11:09:01',NULL),(670,'1',NULL,'2016-03-16 11:10:01',NULL),(671,'1',NULL,'2016-03-16 11:11:01',NULL),(672,'1',NULL,'2016-03-16 11:12:01',NULL),(673,'1',NULL,'2016-03-16 11:13:01',NULL),(674,'1',NULL,'2016-03-16 11:14:01',NULL),(675,'1',NULL,'2016-03-16 11:15:01',NULL),(676,'1',NULL,'2016-03-16 11:16:01',NULL),(677,'1',NULL,'2016-03-16 11:17:01',NULL),(678,'1',NULL,'2016-03-16 11:18:01',NULL),(679,'1',NULL,'2016-03-16 11:19:01',NULL),(680,'1',NULL,'2016-03-16 11:20:01',NULL),(681,'1',NULL,'2016-03-16 11:21:01',NULL),(682,'1',NULL,'2016-03-16 11:22:01',NULL),(683,'1',NULL,'2016-03-16 11:23:01',NULL),(684,'1',NULL,'2016-03-16 11:24:01',NULL),(685,'1',NULL,'2016-03-16 11:25:01',NULL),(686,'1',NULL,'2016-03-16 11:26:01',NULL),(687,'1',NULL,'2016-03-16 11:27:01',NULL),(688,'1',NULL,'2016-03-16 11:28:01',NULL),(689,'1',NULL,'2016-03-16 11:29:01',NULL),(690,'1',NULL,'2016-03-16 11:30:01',NULL),(691,'1',NULL,'2016-03-16 11:31:01',NULL),(692,'1',NULL,'2016-03-16 11:32:02',NULL),(693,'1',NULL,'2016-03-16 11:33:01',NULL),(694,'1',NULL,'2016-03-16 11:34:01',NULL),(695,'1',NULL,'2016-03-16 11:35:02',NULL),(696,'1',NULL,'2016-03-16 11:36:01',NULL),(697,'1',NULL,'2016-03-16 11:37:01',NULL),(698,'1',NULL,'2016-03-16 11:38:01',NULL),(699,'1',NULL,'2016-03-16 11:39:01',NULL),(700,'1',NULL,'2016-03-16 11:40:01',NULL),(701,'1',NULL,'2016-03-16 11:41:01',NULL),(702,'1',NULL,'2016-03-16 11:42:01',NULL),(703,'1',NULL,'2016-03-16 11:43:01',NULL),(704,'1',NULL,'2016-03-16 11:44:01',NULL),(705,'1',NULL,'2016-03-16 11:45:01',NULL),(706,'1',NULL,'2016-03-16 11:46:01',NULL),(707,'1',NULL,'2016-03-16 11:47:01',NULL),(708,'1',NULL,'2016-03-16 11:48:01',NULL),(709,'1',NULL,'2016-03-16 11:49:01',NULL),(710,'1',NULL,'2016-03-16 11:50:01',NULL),(711,'1',NULL,'2016-03-16 11:51:01',NULL),(712,'1',NULL,'2016-03-16 11:52:01',NULL),(713,'1',NULL,'2016-03-16 11:53:01',NULL),(714,'1',NULL,'2016-03-16 11:54:01',NULL),(715,'1',NULL,'2016-03-16 11:55:01',NULL),(716,'1',NULL,'2016-03-16 11:56:01',NULL),(717,'1',NULL,'2016-03-16 11:57:01',NULL),(718,'1',NULL,'2016-03-16 11:58:01',NULL),(719,'1',NULL,'2016-03-16 11:59:01',NULL),(720,'1',NULL,'2016-03-16 12:00:01',NULL),(721,'1',NULL,'2016-03-16 12:01:01',NULL),(722,'1',NULL,'2016-03-16 12:02:01',NULL),(723,'1',NULL,'2016-03-16 12:03:01',NULL),(724,'1',NULL,'2016-03-16 12:04:01',NULL),(725,'1',NULL,'2016-03-16 12:05:01',NULL),(726,'1',NULL,'2016-03-16 12:06:01',NULL),(727,'1',NULL,'2016-03-16 12:07:01',NULL),(728,'1',NULL,'2016-03-16 12:08:01',NULL),(729,'1',NULL,'2016-03-16 12:09:01',NULL),(730,'1',NULL,'2016-03-16 12:10:01',NULL),(731,'1',NULL,'2016-03-16 12:11:01',NULL),(732,'1',NULL,'2016-03-16 12:12:01',NULL),(733,'1',NULL,'2016-03-16 12:13:01',NULL),(734,'1',NULL,'2016-03-16 12:14:01',NULL),(735,'1',NULL,'2016-03-16 12:15:01',NULL),(736,'1',NULL,'2016-03-16 12:16:01',NULL),(737,'1',NULL,'2016-03-16 12:17:01',NULL),(738,'1',NULL,'2016-03-16 12:18:01',NULL),(739,'1',NULL,'2016-03-16 12:19:01',NULL),(740,'1',NULL,'2016-03-16 12:20:01',NULL),(741,'1',NULL,'2016-03-16 12:21:01',NULL),(742,'1',NULL,'2016-03-16 12:22:01',NULL),(743,'1',NULL,'2016-03-16 12:23:01',NULL),(744,'1',NULL,'2016-03-16 12:24:01',NULL),(745,'1',NULL,'2016-03-16 12:25:01',NULL),(746,'1',NULL,'2016-03-16 12:26:01',NULL),(747,'1',NULL,'2016-03-16 12:27:01',NULL),(748,'1',NULL,'2016-03-16 12:28:01',NULL),(749,'1',NULL,'2016-03-16 12:29:01',NULL),(750,'1',NULL,'2016-03-16 12:30:01',NULL),(751,'1',NULL,'2016-03-16 12:31:01',NULL),(752,'1',NULL,'2016-03-16 12:32:01',NULL),(753,'1',NULL,'2016-03-16 12:33:01',NULL),(754,'1',NULL,'2016-03-16 12:34:01',NULL),(755,'1',NULL,'2016-03-16 12:35:01',NULL),(756,'1',NULL,'2016-03-16 12:36:01',NULL),(757,'1',NULL,'2016-03-16 12:37:01',NULL),(758,'1',NULL,'2016-03-16 12:38:01',NULL),(759,'1',NULL,'2016-03-16 12:39:01',NULL),(760,'1',NULL,'2016-03-16 12:40:01',NULL),(761,'1',NULL,'2016-03-16 12:41:01',NULL),(762,'1',NULL,'2016-03-16 12:42:01',NULL),(763,'1',NULL,'2016-03-16 12:43:01',NULL),(764,'1',NULL,'2016-03-16 12:44:01',NULL),(765,'1',NULL,'2016-03-16 12:45:01',NULL),(766,'1',NULL,'2016-03-16 12:46:01',NULL),(767,'1',NULL,'2016-03-16 12:47:01',NULL),(768,'1',NULL,'2016-03-16 12:48:01',NULL),(769,'1',NULL,'2016-03-16 12:49:01',NULL),(770,'1',NULL,'2016-03-16 12:50:01',NULL),(771,'1',NULL,'2016-03-16 12:51:01',NULL),(772,'1',NULL,'2016-03-16 12:52:01',NULL),(773,'1',NULL,'2016-03-16 12:53:01',NULL),(774,'1',NULL,'2016-03-16 12:54:01',NULL),(775,'1',NULL,'2016-03-16 12:55:01',NULL),(776,'1',NULL,'2016-03-16 12:56:01',NULL),(777,'1',NULL,'2016-03-16 12:57:01',NULL),(778,'1',NULL,'2016-03-16 12:58:01',NULL),(779,'1',NULL,'2016-03-16 12:59:01',NULL),(780,'1',NULL,'2016-03-16 13:00:01',NULL),(781,'1',NULL,'2016-03-16 13:01:01',NULL),(782,'1',NULL,'2016-03-16 13:02:01',NULL),(783,'1',NULL,'2016-03-16 13:03:01',NULL),(784,'1',NULL,'2016-03-16 13:04:01',NULL),(785,'1',NULL,'2016-03-16 13:05:01',NULL),(786,'1',NULL,'2016-03-16 13:06:01',NULL),(787,'1',NULL,'2016-03-16 13:07:01',NULL),(788,'1',NULL,'2016-03-16 13:08:01',NULL),(789,'1',NULL,'2016-03-16 13:09:01',NULL),(790,'1',NULL,'2016-03-16 13:10:01',NULL),(791,'1',NULL,'2016-03-16 13:11:01',NULL),(792,'1',NULL,'2016-03-16 13:12:01',NULL),(793,'1',NULL,'2016-03-16 13:13:01',NULL),(794,'1',NULL,'2016-03-16 13:14:01',NULL),(795,'1',NULL,'2016-03-16 13:15:01',NULL),(796,'1',NULL,'2016-03-16 13:16:01',NULL),(797,'1',NULL,'2016-03-16 13:17:01',NULL),(798,'1',NULL,'2016-03-16 13:18:01',NULL),(799,'1',NULL,'2016-03-16 13:19:01',NULL),(800,'1',NULL,'2016-03-16 13:20:01',NULL),(801,'1',NULL,'2016-03-16 13:21:01',NULL),(802,'1',NULL,'2016-03-16 13:22:02',NULL),(803,'1',NULL,'2016-03-16 13:23:01',NULL),(804,'1',NULL,'2016-03-16 13:24:01',NULL),(805,'1',NULL,'2016-03-16 13:25:01',NULL),(806,'1',NULL,'2016-03-16 13:26:01',NULL),(807,'1',NULL,'2016-03-16 13:27:01',NULL),(808,'1',NULL,'2016-03-16 13:28:01',NULL),(809,'1',NULL,'2016-03-16 13:29:01',NULL),(810,'1',NULL,'2016-03-16 13:30:01',NULL),(811,'1',NULL,'2016-03-16 13:31:01',NULL),(812,'1',NULL,'2016-03-16 13:32:01',NULL),(813,'1',NULL,'2016-03-16 13:33:01',NULL),(814,'1',NULL,'2016-03-16 13:34:01',NULL),(815,'1',NULL,'2016-03-16 13:35:01',NULL),(816,'1',NULL,'2016-03-16 13:36:01',NULL),(817,'1',NULL,'2016-03-16 13:37:01',NULL),(818,'1',NULL,'2016-03-16 13:38:01',NULL),(819,'1',NULL,'2016-03-16 13:39:01',NULL),(820,'1',NULL,'2016-03-16 13:40:01',NULL),(821,'1',NULL,'2016-03-16 13:41:01',NULL),(822,'1',NULL,'2016-03-16 13:42:01',NULL),(823,'1',NULL,'2016-03-16 13:43:01',NULL),(824,'1',NULL,'2016-03-16 13:44:01',NULL),(825,'1',NULL,'2016-03-16 13:45:01',NULL),(826,'1',NULL,'2016-03-16 13:46:02',NULL),(827,'1',NULL,'2016-03-16 13:47:01',NULL),(828,'1',NULL,'2016-03-16 13:48:01',NULL),(829,'1',NULL,'2016-03-16 13:49:01',NULL),(830,'1',NULL,'2016-03-16 13:50:01',NULL),(831,'1',NULL,'2016-03-16 13:51:02',NULL),(832,'1',NULL,'2016-03-16 13:52:01',NULL),(833,'1',NULL,'2016-03-16 13:53:01',NULL),(834,'1',NULL,'2016-03-16 13:54:01',NULL),(835,'1',NULL,'2016-03-16 13:55:01',NULL),(836,'1',NULL,'2016-03-16 13:56:01',NULL),(837,'1',NULL,'2016-03-16 13:57:01',NULL),(838,'1',NULL,'2016-03-16 13:58:01',NULL),(839,'1',NULL,'2016-03-16 13:59:01',NULL),(840,'1',NULL,'2016-03-16 14:00:01',NULL),(841,'1',NULL,'2016-03-16 14:01:01',NULL),(842,'1',NULL,'2016-03-16 14:02:01',NULL),(843,'1',NULL,'2016-03-16 14:03:01',NULL),(844,'1',NULL,'2016-03-16 14:04:01',NULL),(845,'1',NULL,'2016-03-16 14:05:01',NULL),(846,'1',NULL,'2016-03-16 14:06:01',NULL),(847,'1',NULL,'2016-03-16 14:07:01',NULL),(848,'1',NULL,'2016-03-16 14:08:01',NULL),(849,'1',NULL,'2016-03-16 14:09:01',NULL),(850,'1',NULL,'2016-03-16 14:10:01',NULL),(851,'1',NULL,'2016-03-16 14:11:01',NULL),(852,'1',NULL,'2016-03-16 14:12:01',NULL),(853,'1',NULL,'2016-03-16 14:13:01',NULL),(854,'1',NULL,'2016-03-16 14:14:01',NULL),(855,'1',NULL,'2016-03-16 14:15:01',NULL),(856,'1',NULL,'2016-03-16 14:16:01',NULL),(857,'1',NULL,'2016-03-16 14:17:01',NULL),(858,'1',NULL,'2016-03-16 14:18:01',NULL),(859,'1',NULL,'2016-03-16 14:19:01',NULL),(860,'1',NULL,'2016-03-16 14:20:02',NULL),(861,'1',NULL,'2016-03-16 14:21:01',NULL),(862,'1',NULL,'2016-03-16 14:22:01',NULL),(863,'1',NULL,'2016-03-16 14:23:01',NULL),(864,'1',NULL,'2016-03-16 14:24:01',NULL),(865,'1',NULL,'2016-03-16 14:25:01',NULL),(866,'1',NULL,'2016-03-16 14:26:01',NULL),(867,'1',NULL,'2016-03-16 14:27:01',NULL),(868,'1',NULL,'2016-03-16 14:28:01',NULL),(869,'1',NULL,'2016-03-16 14:29:01',NULL),(870,'1',NULL,'2016-03-16 14:30:01',NULL),(871,'1',NULL,'2016-03-16 14:31:01',NULL),(872,'1',NULL,'2016-03-16 14:32:01',NULL),(873,'1',NULL,'2016-03-16 14:33:01',NULL),(874,'1',NULL,'2016-03-16 14:34:01',NULL),(875,'1',NULL,'2016-03-16 14:35:01',NULL),(876,'1',NULL,'2016-03-16 14:36:01',NULL),(877,'1',NULL,'2016-03-16 14:37:01',NULL),(878,'1',NULL,'2016-03-16 14:38:01',NULL),(879,'1',NULL,'2016-03-16 14:39:01',NULL),(880,'1',NULL,'2016-03-16 14:40:01',NULL),(881,'1',NULL,'2016-03-16 14:41:01',NULL),(882,'1',NULL,'2016-03-16 14:42:01',NULL),(883,'1',NULL,'2016-03-16 14:43:01',NULL),(884,'1',NULL,'2016-03-16 14:44:01',NULL),(885,'1',NULL,'2016-03-16 14:45:01',NULL),(886,'1',NULL,'2016-03-16 14:46:01',NULL),(887,'1',NULL,'2016-03-16 14:47:01',NULL),(888,'1',NULL,'2016-03-16 14:48:01',NULL),(889,'1',NULL,'2016-03-16 14:49:01',NULL),(890,'1',NULL,'2016-03-16 14:50:01',NULL),(891,'1',NULL,'2016-03-16 14:51:01',NULL),(892,'1',NULL,'2016-03-16 14:52:01',NULL),(893,'1',NULL,'2016-03-16 14:53:01',NULL),(894,'1',NULL,'2016-03-16 14:54:01',NULL),(895,'1',NULL,'2016-03-16 14:55:01',NULL),(896,'1',NULL,'2016-03-16 14:56:01',NULL),(897,'1',NULL,'2016-03-16 14:57:01',NULL),(898,'1',NULL,'2016-03-16 14:58:01',NULL),(899,'1',NULL,'2016-03-16 14:59:01',NULL),(900,'1',NULL,'2016-03-16 15:00:01',NULL),(901,'1',NULL,'2016-03-16 15:01:01',NULL),(902,'1',NULL,'2016-03-16 15:02:01',NULL),(903,'1',NULL,'2016-03-16 15:03:01',NULL),(904,'1',NULL,'2016-03-16 15:04:01',NULL),(905,'1',NULL,'2016-03-16 15:05:01',NULL),(906,'1',NULL,'2016-03-16 15:06:01',NULL),(907,'1',NULL,'2016-03-16 15:07:01',NULL),(908,'1',NULL,'2016-03-16 15:08:01',NULL),(909,'1',NULL,'2016-03-16 15:09:01',NULL),(910,'1',NULL,'2016-03-16 15:10:01',NULL),(911,'1',NULL,'2016-03-16 15:11:01',NULL),(912,'1',NULL,'2016-03-16 15:12:01',NULL),(913,'1',NULL,'2016-03-16 15:13:01',NULL),(914,'1',NULL,'2016-03-16 15:14:02',NULL),(915,'1',NULL,'2016-03-16 15:15:01',NULL),(916,'1',NULL,'2016-03-16 15:16:01',NULL),(917,'1',NULL,'2016-03-16 15:17:01',NULL),(918,'1',NULL,'2016-03-16 15:18:01',NULL),(919,'1',NULL,'2016-03-16 15:19:01',NULL),(920,'1',NULL,'2016-03-16 15:20:01',NULL),(921,'1',NULL,'2016-03-16 15:21:01',NULL),(922,'1',NULL,'2016-03-16 15:22:01',NULL),(923,'1',NULL,'2016-03-16 15:23:01',NULL),(924,'1',NULL,'2016-03-16 15:24:01',NULL),(925,'1',NULL,'2016-03-16 15:25:01',NULL),(926,'1',NULL,'2016-03-16 15:26:01',NULL),(927,'1',NULL,'2016-03-16 15:27:01',NULL),(928,'1',NULL,'2016-03-16 15:28:01',NULL),(929,'1',NULL,'2016-03-16 15:29:01',NULL),(930,'1',NULL,'2016-03-16 15:30:01',NULL),(931,'1',NULL,'2016-03-16 15:31:01',NULL),(932,'1',NULL,'2016-03-16 15:32:01',NULL),(933,'1',NULL,'2016-03-16 15:33:01',NULL),(934,'1',NULL,'2016-03-16 15:34:02',NULL),(935,'1',NULL,'2016-03-16 15:35:01',NULL),(936,'1',NULL,'2016-03-16 15:36:01',NULL),(937,'1',NULL,'2016-03-16 15:37:01',NULL),(938,'1',NULL,'2016-03-16 15:38:01',NULL),(939,'1',NULL,'2016-03-16 15:39:01',NULL),(940,'1',NULL,'2016-03-16 15:40:01',NULL),(941,'1',NULL,'2016-03-16 15:41:01',NULL),(942,'1',NULL,'2016-03-16 15:42:01',NULL),(943,'1',NULL,'2016-03-16 15:43:01',NULL),(944,'1',NULL,'2016-03-16 15:44:01',NULL),(945,'1',NULL,'2016-03-16 15:45:01',NULL),(946,'1',NULL,'2016-03-16 15:46:01',NULL),(947,'1',NULL,'2016-03-16 15:47:01',NULL),(948,'1',NULL,'2016-03-16 15:48:01',NULL),(949,'1',NULL,'2016-03-16 15:49:01',NULL),(950,'1',NULL,'2016-03-16 15:50:01',NULL),(951,'1',NULL,'2016-03-16 15:51:01',NULL),(952,'1',NULL,'2016-03-16 15:52:01',NULL),(953,'1',NULL,'2016-03-16 15:53:01',NULL),(954,'1',NULL,'2016-03-16 15:54:01',NULL),(955,'1',NULL,'2016-03-16 15:55:01',NULL),(956,'1',NULL,'2016-03-16 15:56:01',NULL),(957,'1',NULL,'2016-03-16 15:57:02',NULL),(958,'1',NULL,'2016-03-16 15:58:01',NULL),(959,'1',NULL,'2016-03-16 15:59:01',NULL),(960,'1',NULL,'2016-03-16 16:00:02',NULL),(961,'1',NULL,'2016-03-16 16:01:01',NULL),(962,'1',NULL,'2016-03-16 16:02:01',NULL),(963,'1',NULL,'2016-03-16 16:03:01',NULL),(964,'1',NULL,'2016-03-16 16:04:01',NULL),(965,'1',NULL,'2016-03-16 16:05:01',NULL),(966,'1',NULL,'2016-03-16 16:06:01',NULL),(967,'1',NULL,'2016-03-16 16:07:01',NULL),(968,'1',NULL,'2016-03-16 16:08:01',NULL),(969,'1',NULL,'2016-03-16 16:09:01',NULL),(970,'1',NULL,'2016-03-16 16:10:01',NULL),(971,'1',NULL,'2016-03-16 16:11:01',NULL),(972,'1',NULL,'2016-03-16 16:12:01',NULL),(973,'1',NULL,'2016-03-16 16:13:01',NULL),(974,'1',NULL,'2016-03-16 16:14:01',NULL),(975,'1',NULL,'2016-03-16 16:15:01',NULL),(976,'1',NULL,'2016-03-16 16:16:01',NULL),(977,'1',NULL,'2016-03-16 16:17:01',NULL),(978,'1',NULL,'2016-03-16 16:18:01',NULL),(979,'1',NULL,'2016-03-16 16:19:01',NULL),(980,'1',NULL,'2016-03-16 16:20:01',NULL),(981,'1',NULL,'2016-03-16 16:21:01',NULL),(982,'1',NULL,'2016-03-16 16:22:01',NULL),(983,'1',NULL,'2016-03-16 16:23:01',NULL),(984,'1',NULL,'2016-03-16 16:24:01',NULL),(985,'1',NULL,'2016-03-16 16:25:01',NULL),(986,'1',NULL,'2016-03-16 16:26:01',NULL),(987,'1',NULL,'2016-03-16 16:27:01',NULL),(988,'1',NULL,'2016-03-16 16:28:01',NULL),(989,'1',NULL,'2016-03-16 16:29:01',NULL),(990,'1',NULL,'2016-03-16 16:30:01',NULL),(991,'1',NULL,'2016-03-16 16:31:01',NULL),(992,'1',NULL,'2016-03-16 16:32:01',NULL),(993,'1',NULL,'2016-03-16 16:33:01',NULL),(994,'1',NULL,'2016-03-16 16:34:01',NULL),(995,'1',NULL,'2016-03-16 16:35:01',NULL),(996,'1',NULL,'2016-03-16 16:36:01',NULL),(997,'1',NULL,'2016-03-16 16:37:01',NULL),(998,'1',NULL,'2016-03-16 16:38:01',NULL),(999,'1',NULL,'2016-03-16 16:39:02',NULL),(1000,'1',NULL,'2016-03-16 16:40:01',NULL),(1001,'1',NULL,'2016-03-16 16:41:01',NULL),(1002,'1',NULL,'2016-03-16 16:42:01',NULL),(1003,'1',NULL,'2016-03-16 16:43:01',NULL),(1004,'1',NULL,'2016-03-16 16:44:01',NULL),(1005,'1',NULL,'2016-03-16 16:45:01',NULL),(1006,'1',NULL,'2016-03-16 16:46:01',NULL),(1007,'1',NULL,'2016-03-16 16:47:01',NULL),(1008,'1',NULL,'2016-03-16 16:48:01',NULL),(1009,'1',NULL,'2016-03-16 16:49:01',NULL),(1010,'1',NULL,'2016-03-16 16:50:01',NULL),(1011,'1',NULL,'2016-03-16 16:51:01',NULL),(1012,'1',NULL,'2016-03-16 16:52:01',NULL),(1013,'1',NULL,'2016-03-16 16:53:01',NULL),(1014,'1',NULL,'2016-03-16 16:54:01',NULL),(1015,'1',NULL,'2016-03-16 16:55:01',NULL),(1016,'1',NULL,'2016-03-16 16:56:01',NULL),(1017,'1',NULL,'2016-03-16 16:57:01',NULL),(1018,'1',NULL,'2016-03-16 16:58:01',NULL),(1019,'1',NULL,'2016-03-16 16:59:01',NULL),(1020,'1',NULL,'2016-03-16 17:00:01',NULL),(1021,'1',NULL,'2016-03-16 17:01:01',NULL),(1022,'1',NULL,'2016-03-16 17:02:01',NULL),(1023,'1',NULL,'2016-03-16 17:03:01',NULL),(1024,'1',NULL,'2016-03-16 17:04:01',NULL),(1025,'1',NULL,'2016-03-16 17:05:01',NULL),(1026,'1',NULL,'2016-03-16 17:06:01',NULL),(1027,'1',NULL,'2016-03-16 17:07:01',NULL),(1028,'1',NULL,'2016-03-16 17:08:01',NULL),(1029,'1',NULL,'2016-03-16 17:09:01',NULL),(1030,'1',NULL,'2016-03-16 17:10:01',NULL),(1031,'1',NULL,'2016-03-16 17:11:02',NULL),(1032,'1',NULL,'2016-03-16 17:12:01',NULL),(1033,'1',NULL,'2016-03-16 17:13:01',NULL),(1034,'1',NULL,'2016-03-16 17:14:01',NULL),(1035,'1',NULL,'2016-03-16 17:15:01',NULL),(1036,'1',NULL,'2016-03-16 17:16:01',NULL),(1037,'1',NULL,'2016-03-16 17:17:01',NULL),(1038,'1',NULL,'2016-03-16 17:18:01',NULL),(1039,'1',NULL,'2016-03-16 17:19:01',NULL),(1040,'1',NULL,'2016-03-16 17:20:01',NULL),(1041,'1',NULL,'2016-03-16 17:21:01',NULL),(1042,'1',NULL,'2016-03-16 17:22:01',NULL),(1043,'1',NULL,'2016-03-16 17:23:01',NULL),(1044,'1',NULL,'2016-03-16 17:24:01',NULL),(1045,'1',NULL,'2016-03-16 17:25:01',NULL),(1046,'1',NULL,'2016-03-16 17:26:01',NULL),(1047,'1',NULL,'2016-03-16 17:27:01',NULL),(1048,'1',NULL,'2016-03-16 17:28:01',NULL),(1049,'1',NULL,'2016-03-16 17:29:01',NULL),(1050,'1',NULL,'2016-03-16 17:30:01',NULL),(1051,'1',NULL,'2016-03-16 17:31:01',NULL),(1052,'1',NULL,'2016-03-16 17:32:01',NULL),(1053,'1',NULL,'2016-03-16 17:33:01',NULL),(1054,'1',NULL,'2016-03-16 17:34:01',NULL),(1055,'1',NULL,'2016-03-16 17:35:01',NULL),(1056,'1',NULL,'2016-03-16 17:36:01',NULL),(1057,'1',NULL,'2016-03-16 17:37:01',NULL),(1058,'1',NULL,'2016-03-16 17:38:01',NULL),(1059,'1',NULL,'2016-03-16 17:39:01',NULL),(1060,'1',NULL,'2016-03-16 17:40:01',NULL),(1061,'1',NULL,'2016-03-16 17:41:01',NULL),(1062,'1',NULL,'2016-03-16 17:42:02',NULL),(1063,'1',NULL,'2016-03-16 17:43:01',NULL),(1064,'1',NULL,'2016-03-16 17:44:01',NULL),(1065,'1',NULL,'2016-03-16 17:45:01',NULL),(1066,'1',NULL,'2016-03-16 17:46:01',NULL),(1067,'1',NULL,'2016-03-16 17:47:01',NULL),(1068,'1',NULL,'2016-03-16 17:48:02',NULL),(1069,'1',NULL,'2016-03-16 17:49:01',NULL),(1070,'1',NULL,'2016-03-16 17:50:01',NULL),(1071,'1',NULL,'2016-03-16 17:51:01',NULL),(1072,'1',NULL,'2016-03-16 17:52:01',NULL),(1073,'1',NULL,'2016-03-16 17:53:01',NULL),(1074,'1',NULL,'2016-03-16 17:54:01',NULL),(1075,'1',NULL,'2016-03-16 17:55:01',NULL),(1076,'1',NULL,'2016-03-16 17:56:01',NULL),(1077,'1',NULL,'2016-03-16 17:57:01',NULL),(1078,'1',NULL,'2016-03-16 17:58:01',NULL),(1079,'1',NULL,'2016-03-16 17:59:01',NULL),(1080,'1',NULL,'2016-03-16 18:00:01',NULL),(1081,'1',NULL,'2016-03-16 18:01:01',NULL),(1082,'1',NULL,'2016-03-16 18:02:01',NULL),(1083,'1',NULL,'2016-03-16 18:03:01',NULL),(1084,'1',NULL,'2016-03-16 18:04:01',NULL),(1085,'1',NULL,'2016-03-16 18:05:01',NULL),(1086,'1',NULL,'2016-03-16 18:06:01',NULL),(1087,'1',NULL,'2016-03-16 18:07:01',NULL),(1088,'1',NULL,'2016-03-16 18:08:01',NULL),(1089,'1',NULL,'2016-03-16 18:09:01',NULL),(1090,'1',NULL,'2016-03-16 18:10:01',NULL),(1091,'1',NULL,'2016-03-16 18:11:01',NULL),(1092,'1',NULL,'2016-03-16 18:12:01',NULL),(1093,'1',NULL,'2016-03-16 18:13:01',NULL),(1094,'1',NULL,'2016-03-16 18:14:01',NULL),(1095,'1',NULL,'2016-03-16 18:15:01',NULL),(1096,'1',NULL,'2016-03-16 18:16:02',NULL),(1097,'1',NULL,'2016-03-16 18:17:01',NULL),(1098,'1',NULL,'2016-03-16 18:18:01',NULL),(1099,'1',NULL,'2016-03-16 18:19:01',NULL),(1100,'1',NULL,'2016-03-16 18:20:01',NULL),(1101,'1',NULL,'2016-03-16 18:21:01',NULL),(1102,'1',NULL,'2016-03-16 18:22:01',NULL),(1103,'1',NULL,'2016-03-16 18:23:01',NULL),(1104,'1',NULL,'2016-03-16 18:24:01',NULL),(1105,'1',NULL,'2016-03-16 18:25:01',NULL),(1106,'1',NULL,'2016-03-16 18:26:01',NULL),(1107,'1',NULL,'2016-03-16 18:27:01',NULL),(1108,'1',NULL,'2016-03-16 18:28:01',NULL),(1109,'1',NULL,'2016-03-16 18:29:01',NULL),(1110,'1',NULL,'2016-03-16 18:30:01',NULL),(1111,'1',NULL,'2016-03-16 18:31:01',NULL),(1112,'1',NULL,'2016-03-16 18:32:01',NULL),(1113,'1',NULL,'2016-03-16 18:33:01',NULL),(1114,'1',NULL,'2016-03-16 18:34:01',NULL),(1115,'1',NULL,'2016-03-16 18:35:01',NULL),(1116,'1',NULL,'2016-03-16 18:36:01',NULL),(1117,'1',NULL,'2016-03-16 18:37:01',NULL),(1118,'1',NULL,'2016-03-16 18:38:01',NULL),(1119,'1',NULL,'2016-03-16 18:39:01',NULL),(1120,'1',NULL,'2016-03-16 18:40:01',NULL),(1121,'1',NULL,'2016-03-16 18:41:01',NULL),(1122,'1',NULL,'2016-03-16 18:42:01',NULL),(1123,'1',NULL,'2016-03-16 18:43:01',NULL),(1124,'1',NULL,'2016-03-16 18:44:01',NULL),(1125,'1',NULL,'2016-03-16 18:45:01',NULL),(1126,'1',NULL,'2016-03-16 18:46:01',NULL),(1127,'1',NULL,'2016-03-16 18:47:01',NULL),(1128,'1',NULL,'2016-03-16 18:48:02',NULL),(1129,'1',NULL,'2016-03-16 18:49:01',NULL),(1130,'1',NULL,'2016-03-16 18:50:01',NULL),(1131,'1',NULL,'2016-03-16 18:51:01',NULL),(1132,'1',NULL,'2016-03-16 18:52:01',NULL),(1133,'1',NULL,'2016-03-16 18:53:01',NULL),(1134,'1',NULL,'2016-03-16 18:54:01',NULL),(1135,'1',NULL,'2016-03-16 18:55:01',NULL),(1136,'1',NULL,'2016-03-16 18:56:01',NULL),(1137,'1',NULL,'2016-03-16 18:57:01',NULL),(1138,'1',NULL,'2016-03-16 18:58:01',NULL),(1139,'1',NULL,'2016-03-16 18:59:01',NULL),(1140,'1',NULL,'2016-03-16 19:00:01',NULL),(1141,'1',NULL,'2016-03-16 19:01:01',NULL),(1142,'1',NULL,'2016-03-16 19:02:01',NULL),(1143,'1',NULL,'2016-03-16 19:03:01',NULL),(1144,'1',NULL,'2016-03-16 19:04:01',NULL),(1145,'1',NULL,'2016-03-16 19:05:01',NULL),(1146,'1',NULL,'2016-03-16 19:06:01',NULL),(1147,'1',NULL,'2016-03-16 19:07:01',NULL),(1148,'1',NULL,'2016-03-16 19:08:01',NULL),(1149,'1',NULL,'2016-03-16 19:09:01',NULL),(1150,'1',NULL,'2016-03-16 19:10:01',NULL),(1151,'1',NULL,'2016-03-16 19:11:01',NULL),(1152,'1',NULL,'2016-03-16 19:12:01',NULL),(1153,'1',NULL,'2016-03-16 19:13:01',NULL),(1154,'1',NULL,'2016-03-16 19:14:01',NULL),(1155,'1',NULL,'2016-03-16 19:15:01',NULL),(1156,'1',NULL,'2016-03-16 19:16:01',NULL),(1157,'1',NULL,'2016-03-16 19:17:01',NULL),(1158,'1',NULL,'2016-03-16 19:18:01',NULL),(1159,'1',NULL,'2016-03-16 19:19:01',NULL),(1160,'1',NULL,'2016-03-16 19:20:01',NULL),(1161,'1',NULL,'2016-03-16 19:21:01',NULL),(1162,'1',NULL,'2016-03-16 19:22:01',NULL),(1163,'1',NULL,'2016-03-16 19:23:01',NULL),(1164,'1',NULL,'2016-03-16 19:24:01',NULL),(1165,'1',NULL,'2016-03-16 19:25:01',NULL),(1166,'1',NULL,'2016-03-16 19:26:01',NULL),(1167,'1',NULL,'2016-03-16 19:27:01',NULL),(1168,'1',NULL,'2016-03-16 19:28:01',NULL),(1169,'1',NULL,'2016-03-16 19:29:01',NULL),(1170,'1',NULL,'2016-03-16 19:30:01',NULL),(1171,'1',NULL,'2016-03-16 19:31:01',NULL),(1172,'1',NULL,'2016-03-16 19:32:01',NULL),(1173,'1',NULL,'2016-03-16 19:33:01',NULL),(1174,'1',NULL,'2016-03-17 10:07:01',NULL),(1175,'1',NULL,'2016-03-17 10:08:01',NULL),(1176,'1',NULL,'2016-03-17 10:09:01',NULL),(1177,'1',NULL,'2016-03-17 10:10:01',NULL),(1178,'1',NULL,'2016-03-17 10:11:01',NULL),(1179,'1',NULL,'2016-03-17 10:12:01',NULL),(1180,'1',NULL,'2016-03-17 10:13:01',NULL),(1181,'1',NULL,'2016-03-17 10:14:01',NULL),(1182,'1',NULL,'2016-03-17 10:15:01',NULL),(1183,'1',NULL,'2016-03-17 10:16:01',NULL),(1184,'1',NULL,'2016-03-17 10:17:01',NULL),(1185,'1',NULL,'2016-03-17 10:18:01',NULL),(1186,'1',NULL,'2016-03-17 10:19:01',NULL),(1187,'1',NULL,'2016-03-17 10:20:01',NULL),(1188,'1',NULL,'2016-03-17 10:21:01',NULL),(1189,'1',NULL,'2016-03-17 10:22:01',NULL),(1190,'1',NULL,'2016-03-17 10:23:01',NULL),(1191,'1',NULL,'2016-03-17 10:24:01',NULL),(1192,'1',NULL,'2016-03-17 10:25:01',NULL),(1193,'1',NULL,'2016-03-17 10:26:01',NULL),(1194,'1',NULL,'2016-03-17 10:27:01',NULL),(1195,'1',NULL,'2016-03-17 10:28:01',NULL),(1196,'1',NULL,'2016-03-17 10:29:01',NULL),(1197,'1',NULL,'2016-03-17 10:30:01',NULL),(1198,'1',NULL,'2016-03-17 10:31:01',NULL),(1199,'1',NULL,'2016-03-17 10:32:01',NULL),(1200,'1',NULL,'2016-03-17 10:33:01',NULL),(1201,'1',NULL,'2016-03-17 10:34:02',NULL),(1202,'1',NULL,'2016-03-17 10:35:01',NULL),(1203,'1',NULL,'2016-03-17 10:36:01',NULL),(1204,'1',NULL,'2016-03-17 10:37:01',NULL),(1205,'1',NULL,'2016-03-17 10:38:01',NULL),(1206,'1',NULL,'2016-03-17 10:39:01',NULL),(1207,'1',NULL,'2016-03-17 10:40:01',NULL),(1208,'1',NULL,'2016-03-17 10:41:01',NULL),(1209,'1',NULL,'2016-03-17 10:42:01',NULL),(1210,'1',NULL,'2016-03-17 10:43:01',NULL),(1211,'1',NULL,'2016-03-17 10:44:01',NULL),(1212,'1',NULL,'2016-03-17 10:45:01',NULL),(1213,'1',NULL,'2016-03-17 10:46:01',NULL),(1214,'1',NULL,'2016-03-17 10:47:01',NULL),(1215,'1',NULL,'2016-03-17 10:48:01',NULL),(1216,'1',NULL,'2016-03-17 10:49:01',NULL),(1217,'1',NULL,'2016-03-17 10:50:01',NULL),(1218,'1',NULL,'2016-03-17 10:51:01',NULL),(1219,'1',NULL,'2016-03-17 10:52:02',NULL),(1220,'1',NULL,'2016-03-17 10:53:01',NULL),(1221,'1',NULL,'2016-03-17 10:54:01',NULL),(1222,'1',NULL,'2016-03-17 10:55:01',NULL),(1223,'1',NULL,'2016-03-17 10:56:01',NULL),(1224,'1',NULL,'2016-03-17 10:57:01',NULL),(1225,'1',NULL,'2016-03-17 10:58:01',NULL),(1226,'1',NULL,'2016-03-17 10:59:01',NULL),(1227,'1',NULL,'2016-03-17 11:00:01',NULL),(1228,'1',NULL,'2016-03-17 11:01:01',NULL),(1229,'1',NULL,'2016-03-17 11:02:02',NULL),(1230,'1',NULL,'2016-03-17 11:03:01',NULL),(1231,'1',NULL,'2016-03-17 11:04:01',NULL),(1232,'1',NULL,'2016-03-17 11:05:01',NULL),(1233,'1',NULL,'2016-03-17 11:06:01',NULL),(1234,'1',NULL,'2016-03-17 11:07:01',NULL),(1235,'1',NULL,'2016-03-17 11:08:01',NULL),(1236,'1',NULL,'2016-03-17 11:09:01',NULL),(1237,'1',NULL,'2016-03-17 11:10:01',NULL),(1238,'1',NULL,'2016-03-17 11:11:01',NULL),(1239,'1',NULL,'2016-03-17 11:12:01',NULL),(1240,'1',NULL,'2016-03-17 11:13:01',NULL),(1241,'1',NULL,'2016-03-17 11:14:01',NULL),(1242,'1',NULL,'2016-03-17 11:15:01',NULL),(1243,'1',NULL,'2016-03-17 11:16:01',NULL),(1244,'1',NULL,'2016-03-17 11:17:01',NULL),(1245,'1',NULL,'2016-03-17 11:18:01',NULL),(1246,'1',NULL,'2016-03-17 11:19:01',NULL),(1247,'1',NULL,'2016-03-17 11:20:01',NULL),(1248,'1',NULL,'2016-03-17 11:21:01',NULL),(1249,'1',NULL,'2016-03-17 11:22:01',NULL),(1250,'1',NULL,'2016-03-17 11:23:01',NULL),(1251,'1',NULL,'2016-03-17 11:24:01',NULL),(1252,'1',NULL,'2016-03-17 11:25:01',NULL),(1253,'1',NULL,'2016-03-17 11:26:01',NULL),(1254,'1',NULL,'2016-03-17 11:27:01',NULL),(1255,'1',NULL,'2016-03-17 11:28:01',NULL),(1256,'1',NULL,'2016-03-17 11:29:01',NULL),(1257,'1',NULL,'2016-03-17 11:30:01',NULL),(1258,'1',NULL,'2016-03-17 11:31:01',NULL),(1259,'1',NULL,'2016-03-17 11:32:01',NULL),(1260,'1',NULL,'2016-03-17 11:33:01',NULL),(1261,'1',NULL,'2016-03-17 11:34:01',NULL),(1262,'1',NULL,'2016-03-17 11:35:01',NULL),(1263,'1',NULL,'2016-03-17 11:36:01',NULL),(1264,'1',NULL,'2016-03-17 11:37:01',NULL),(1265,'1',NULL,'2016-03-17 11:38:01',NULL),(1266,'1',NULL,'2016-03-17 11:39:02',NULL),(1267,'1',NULL,'2016-03-17 11:40:01',NULL),(1268,'1',NULL,'2016-03-17 11:41:01',NULL),(1269,'1',NULL,'2016-03-17 11:42:01',NULL),(1270,'1',NULL,'2016-03-17 11:43:01',NULL),(1271,'1',NULL,'2016-03-17 11:44:01',NULL),(1272,'1',NULL,'2016-03-17 11:45:01',NULL),(1273,'1',NULL,'2016-03-17 11:46:01',NULL),(1274,'1',NULL,'2016-03-17 11:47:02',NULL),(1275,'1',NULL,'2016-03-17 11:48:01',NULL),(1276,'1',NULL,'2016-03-17 11:49:01',NULL),(1277,'1',NULL,'2016-03-17 11:50:01',NULL),(1278,'1',NULL,'2016-03-17 11:51:01',NULL),(1279,'1',NULL,'2016-03-17 11:52:01',NULL),(1280,'1',NULL,'2016-03-17 11:53:01',NULL),(1281,'1',NULL,'2016-03-17 11:54:01',NULL),(1282,'1',NULL,'2016-03-17 11:55:01',NULL),(1283,'1',NULL,'2016-03-17 11:56:01',NULL),(1284,'1',NULL,'2016-03-17 11:57:01',NULL),(1285,'1',NULL,'2016-03-17 11:58:01',NULL),(1286,'1',NULL,'2016-03-17 11:59:01',NULL),(1287,'1',NULL,'2016-03-17 12:00:01',NULL),(1288,'1',NULL,'2016-03-17 12:01:01',NULL),(1289,'1',NULL,'2016-03-17 12:02:01',NULL),(1290,'1',NULL,'2016-03-17 12:03:01',NULL),(1291,'1',NULL,'2016-03-17 12:04:01',NULL),(1292,'1',NULL,'2016-03-17 12:05:01',NULL),(1293,'1',NULL,'2016-03-17 12:06:01',NULL),(1294,'1',NULL,'2016-03-17 12:07:01',NULL),(1295,'1',NULL,'2016-03-17 12:08:01',NULL),(1296,'1',NULL,'2016-03-17 12:09:01',NULL),(1297,'1',NULL,'2016-03-17 12:10:01',NULL),(1298,'1',NULL,'2016-03-17 12:11:01',NULL),(1299,'1',NULL,'2016-03-17 12:12:01',NULL),(1300,'1',NULL,'2016-03-17 12:13:01',NULL),(1301,'1',NULL,'2016-03-17 12:14:01',NULL),(1302,'1',NULL,'2016-03-17 12:15:01',NULL),(1303,'1',NULL,'2016-03-17 12:16:01',NULL),(1304,'1',NULL,'2016-03-17 12:17:01',NULL),(1305,'1',NULL,'2016-03-17 12:18:02',NULL),(1306,'1',NULL,'2016-03-17 12:19:01',NULL),(1307,'1',NULL,'2016-03-17 12:20:01',NULL),(1308,'1',NULL,'2016-03-17 12:21:02',NULL),(1309,'1',NULL,'2016-03-17 12:22:01',NULL),(1310,'1',NULL,'2016-03-17 12:23:01',NULL),(1311,'1',NULL,'2016-03-17 12:24:01',NULL),(1312,'1',NULL,'2016-03-17 12:25:01',NULL),(1313,'1',NULL,'2016-03-17 12:26:01',NULL),(1314,'1',NULL,'2016-03-17 12:27:01',NULL),(1315,'1',NULL,'2016-03-17 12:28:01',NULL),(1316,'1',NULL,'2016-03-17 12:29:01',NULL),(1317,'1',NULL,'2016-03-17 12:30:01',NULL),(1318,'1',NULL,'2016-03-17 12:31:01',NULL),(1319,'1',NULL,'2016-03-17 12:32:01',NULL),(1320,'1',NULL,'2016-03-17 12:33:01',NULL),(1321,'1',NULL,'2016-03-17 12:34:01',NULL),(1322,'1',NULL,'2016-03-17 12:35:01',NULL),(1323,'1',NULL,'2016-03-17 12:36:01',NULL),(1324,'1',NULL,'2016-03-17 12:37:01',NULL),(1325,'1',NULL,'2016-03-17 12:38:01',NULL),(1326,'1',NULL,'2016-03-17 12:39:01',NULL),(1327,'1',NULL,'2016-03-17 12:40:01',NULL),(1328,'1',NULL,'2016-03-17 12:41:02',NULL),(1329,'1',NULL,'2016-03-17 12:42:01',NULL),(1330,'1',NULL,'2016-03-17 12:43:01',NULL),(1331,'1',NULL,'2016-03-17 12:44:01',NULL),(1332,'1',NULL,'2016-03-17 12:45:01',NULL),(1333,'1',NULL,'2016-03-17 12:46:01',NULL),(1334,'1',NULL,'2016-03-17 12:47:01',NULL),(1335,'1',NULL,'2016-03-17 12:48:01',NULL),(1336,'1',NULL,'2016-03-17 12:49:01',NULL),(1337,'1',NULL,'2016-03-17 12:50:01',NULL),(1338,'1',NULL,'2016-03-17 12:51:01',NULL),(1339,'1',NULL,'2016-03-17 12:52:01',NULL),(1340,'1',NULL,'2016-03-17 12:53:01',NULL),(1341,'1',NULL,'2016-03-17 12:54:01',NULL),(1342,'1',NULL,'2016-03-17 12:55:01',NULL),(1343,'1',NULL,'2016-03-17 12:56:01',NULL),(1344,'1',NULL,'2016-03-17 12:57:01',NULL),(1345,'1',NULL,'2016-03-17 12:58:01',NULL),(1346,'1',NULL,'2016-03-17 12:59:01',NULL),(1347,'1',NULL,'2016-03-17 13:00:01',NULL),(1348,'1',NULL,'2016-03-17 13:01:01',NULL),(1349,'1',NULL,'2016-03-17 13:02:01',NULL),(1350,'1',NULL,'2016-03-17 13:03:01',NULL),(1351,'1',NULL,'2016-03-17 13:04:01',NULL),(1352,'1',NULL,'2016-03-17 13:05:01',NULL),(1353,'1',NULL,'2016-03-17 13:06:01',NULL),(1354,'1',NULL,'2016-03-17 13:07:01',NULL),(1355,'1',NULL,'2016-03-17 13:08:01',NULL),(1356,'1',NULL,'2016-03-17 13:09:01',NULL),(1357,'1',NULL,'2016-03-17 13:10:01',NULL),(1358,'1',NULL,'2016-03-17 13:11:01',NULL),(1359,'1',NULL,'2016-03-17 13:12:01',NULL),(1360,'1',NULL,'2016-03-17 13:13:01',NULL),(1361,'1',NULL,'2016-03-17 13:14:01',NULL),(1362,'1',NULL,'2016-03-17 13:15:01',NULL),(1363,'1',NULL,'2016-03-17 13:16:01',NULL),(1364,'1',NULL,'2016-03-17 13:17:01',NULL),(1365,'1',NULL,'2016-03-17 13:18:01',NULL),(1366,'1',NULL,'2016-03-17 13:19:01',NULL),(1367,'1',NULL,'2016-03-17 13:20:01',NULL),(1368,'1',NULL,'2016-03-17 13:21:01',NULL),(1369,'1',NULL,'2016-03-17 13:22:01',NULL),(1370,'1',NULL,'2016-03-17 13:23:01',NULL),(1371,'1',NULL,'2016-03-17 13:24:01',NULL),(1372,'1',NULL,'2016-03-17 13:25:01',NULL),(1373,'1',NULL,'2016-03-17 13:26:01',NULL),(1374,'1',NULL,'2016-03-17 13:27:01',NULL),(1375,'1',NULL,'2016-03-17 13:28:01',NULL),(1376,'1',NULL,'2016-03-17 13:29:01',NULL),(1377,'1',NULL,'2016-03-17 13:30:01',NULL),(1378,'1',NULL,'2016-03-17 13:31:01',NULL),(1379,'1',NULL,'2016-03-17 13:32:01',NULL),(1380,'1',NULL,'2016-03-17 13:33:01',NULL),(1381,'1',NULL,'2016-03-17 13:34:01',NULL),(1382,'1',NULL,'2016-03-17 13:35:01',NULL),(1383,'1',NULL,'2016-03-17 13:36:01',NULL),(1384,'1',NULL,'2016-03-17 13:37:01',NULL),(1385,'1',NULL,'2016-03-17 13:38:01',NULL),(1386,'1',NULL,'2016-03-17 13:39:01',NULL),(1387,'1',NULL,'2016-03-17 13:40:01',NULL),(1388,'1',NULL,'2016-03-17 13:41:01',NULL),(1389,'1',NULL,'2016-03-17 13:42:01',NULL),(1390,'1',NULL,'2016-03-17 13:43:01',NULL),(1391,'1',NULL,'2016-03-17 13:44:01',NULL),(1392,'1',NULL,'2016-03-17 13:45:01',NULL),(1393,'1',NULL,'2016-03-17 13:46:02',NULL),(1394,'1',NULL,'2016-03-17 13:47:01',NULL),(1395,'1',NULL,'2016-03-17 13:48:01',NULL),(1396,'1',NULL,'2016-03-17 13:49:01',NULL),(1397,'1',NULL,'2016-03-17 13:50:01',NULL),(1398,'1',NULL,'2016-03-17 13:51:01',NULL),(1399,'1',NULL,'2016-03-17 13:52:01',NULL),(1400,'1',NULL,'2016-03-17 13:53:01',NULL),(1401,'1',NULL,'2016-03-17 13:54:01',NULL),(1402,'1',NULL,'2016-03-17 13:55:01',NULL),(1403,'1',NULL,'2016-03-17 13:56:01',NULL),(1404,'1',NULL,'2016-03-17 13:57:01',NULL),(1405,'1',NULL,'2016-03-17 13:58:01',NULL),(1406,'1',NULL,'2016-03-17 13:59:01',NULL),(1407,'1',NULL,'2016-03-17 14:00:02',NULL),(1408,'1',NULL,'2016-03-17 14:01:01',NULL),(1409,'1',NULL,'2016-03-17 14:02:01',NULL),(1410,'1',NULL,'2016-03-17 14:03:01',NULL),(1411,'1',NULL,'2016-03-17 14:04:01',NULL),(1412,'1',NULL,'2016-03-17 14:05:01',NULL),(1413,'1',NULL,'2016-03-17 14:06:01',NULL),(1414,'1',NULL,'2016-03-17 14:07:01',NULL),(1415,'1',NULL,'2016-03-17 14:08:01',NULL),(1416,'1',NULL,'2016-03-17 14:09:01',NULL),(1417,'1',NULL,'2016-03-17 14:10:01',NULL),(1418,'1',NULL,'2016-03-17 14:11:01',NULL),(1419,'1',NULL,'2016-03-17 14:12:01',NULL),(1420,'1',NULL,'2016-03-17 14:13:01',NULL),(1421,'1',NULL,'2016-03-17 14:14:02',NULL),(1422,'1',NULL,'2016-03-17 14:15:01',NULL),(1423,'1',NULL,'2016-03-17 14:16:01',NULL),(1424,'1',NULL,'2016-03-17 14:17:01',NULL),(1425,'1',NULL,'2016-03-17 14:18:01',NULL),(1426,'1',NULL,'2016-03-17 14:19:01',NULL),(1427,'1',NULL,'2016-03-17 14:20:01',NULL),(1428,'1',NULL,'2016-03-17 14:21:01',NULL),(1429,'1',NULL,'2016-03-17 14:22:01',NULL),(1430,'1',NULL,'2016-03-17 14:23:01',NULL),(1431,'1',NULL,'2016-03-17 14:24:01',NULL),(1432,'1',NULL,'2016-03-17 14:25:01',NULL),(1433,'1',NULL,'2016-03-17 14:26:01',NULL),(1434,'1',NULL,'2016-03-17 14:27:01',NULL),(1435,'1',NULL,'2016-03-17 14:28:01',NULL),(1436,'1',NULL,'2016-03-17 14:29:01',NULL),(1437,'1',NULL,'2016-03-17 14:30:01',NULL),(1438,'1',NULL,'2016-03-17 14:31:01',NULL),(1439,'1',NULL,'2016-03-17 14:32:01',NULL),(1440,'1',NULL,'2016-03-17 14:33:01',NULL),(1441,'1',NULL,'2016-03-17 14:34:02',NULL),(1442,'1',NULL,'2016-03-17 14:35:01',NULL),(1443,'1',NULL,'2016-03-17 14:36:01',NULL),(1444,'1',NULL,'2016-03-17 14:37:01',NULL),(1445,'1',NULL,'2016-03-17 14:38:01',NULL),(1446,'1',NULL,'2016-03-17 14:39:01',NULL),(1447,'1',NULL,'2016-03-17 14:40:01',NULL),(1448,'1',NULL,'2016-03-17 14:41:01',NULL),(1449,'1',NULL,'2016-03-17 14:42:01',NULL),(1450,'1',NULL,'2016-03-17 14:43:01',NULL),(1451,'1',NULL,'2016-03-17 14:44:01',NULL),(1452,'1',NULL,'2016-03-17 14:45:01',NULL),(1453,'1',NULL,'2016-03-17 14:46:01',NULL),(1454,'1',NULL,'2016-03-17 14:47:01',NULL),(1455,'1',NULL,'2016-03-17 14:48:01',NULL),(1456,'1',NULL,'2016-03-17 14:49:01',NULL),(1457,'1',NULL,'2016-03-17 14:50:02',NULL),(1458,'1',NULL,'2016-03-17 14:51:01',NULL),(1459,'1',NULL,'2016-03-17 14:52:01',NULL),(1460,'1',NULL,'2016-03-17 14:53:01',NULL),(1461,'1',NULL,'2016-03-17 14:54:01',NULL),(1462,'1',NULL,'2016-03-17 14:55:01',NULL),(1463,'1',NULL,'2016-03-17 14:56:01',NULL),(1464,'1',NULL,'2016-03-17 14:57:01',NULL),(1465,'1',NULL,'2016-03-17 14:58:01',NULL),(1466,'1',NULL,'2016-03-17 14:59:01',NULL),(1467,'1',NULL,'2016-03-17 15:00:01',NULL),(1468,'1',NULL,'2016-03-17 15:01:01',NULL),(1469,'1',NULL,'2016-03-17 15:02:01',NULL),(1470,'1',NULL,'2016-03-17 15:03:01',NULL),(1471,'1',NULL,'2016-03-17 15:04:02',NULL),(1472,'1',NULL,'2016-03-17 15:05:01',NULL),(1473,'1',NULL,'2016-03-17 15:06:01',NULL),(1474,'1',NULL,'2016-03-17 15:07:02',NULL),(1475,'1',NULL,'2016-03-17 15:08:01',NULL),(1476,'1',NULL,'2016-03-17 15:09:01',NULL),(1477,'1',NULL,'2016-03-17 15:10:01',NULL),(1478,'1',NULL,'2016-03-17 15:11:01',NULL),(1479,'1',NULL,'2016-03-17 15:12:01',NULL),(1480,'1',NULL,'2016-03-17 15:13:01',NULL),(1481,'1',NULL,'2016-03-17 15:14:01',NULL),(1482,'1',NULL,'2016-03-17 15:15:01',NULL),(1483,'1',NULL,'2016-03-17 15:16:01',NULL),(1484,'1',NULL,'2016-03-17 15:17:01',NULL),(1485,'1',NULL,'2016-03-17 15:18:01',NULL),(1486,'1',NULL,'2016-03-17 15:19:01',NULL),(1487,'1',NULL,'2016-03-17 15:20:01',NULL),(1488,'1',NULL,'2016-03-17 15:21:01',NULL),(1489,'1',NULL,'2016-03-17 15:22:01',NULL),(1490,'1',NULL,'2016-03-17 15:23:02',NULL),(1491,'1',NULL,'2016-03-17 15:24:01',NULL),(1492,'1',NULL,'2016-03-17 15:25:01',NULL),(1493,'1',NULL,'2016-03-17 15:26:01',NULL),(1494,'1',NULL,'2016-03-17 15:27:02',NULL),(1495,'1',NULL,'2016-03-17 15:28:01',NULL),(1496,'1',NULL,'2016-03-17 15:29:01',NULL),(1497,'1',NULL,'2016-03-17 15:30:01',NULL),(1498,'1',NULL,'2016-03-17 15:31:01',NULL),(1499,'1',NULL,'2016-03-17 15:32:01',NULL),(1500,'1',NULL,'2016-03-17 15:33:01',NULL),(1501,'1',NULL,'2016-03-17 15:34:01',NULL),(1502,'1',NULL,'2016-03-17 15:35:01',NULL),(1503,'1',NULL,'2016-03-17 15:36:01',NULL),(1504,'1',NULL,'2016-03-17 15:37:01',NULL),(1505,'1',NULL,'2016-03-17 15:38:01',NULL),(1506,'1',NULL,'2016-03-17 15:39:01',NULL),(1507,'1',NULL,'2016-03-17 15:40:02',NULL),(1508,'1',NULL,'2016-03-17 15:41:01',NULL),(1509,'1',NULL,'2016-03-17 15:42:01',NULL),(1510,'1',NULL,'2016-03-17 15:43:01',NULL),(1511,'1',NULL,'2016-03-17 15:44:01',NULL),(1512,'1',NULL,'2016-03-17 15:45:01',NULL),(1513,'1',NULL,'2016-03-17 15:46:01',NULL),(1514,'1',NULL,'2016-03-17 15:47:02',NULL),(1515,'1',NULL,'2016-03-17 15:48:01',NULL),(1516,'1',NULL,'2016-03-17 15:49:01',NULL),(1517,'1',NULL,'2016-03-17 15:50:01',NULL),(1518,'1',NULL,'2016-03-17 15:51:01',NULL),(1519,'1',NULL,'2016-03-17 15:52:02',NULL),(1520,'1',NULL,'2016-03-17 15:53:01',NULL),(1521,'1',NULL,'2016-03-17 15:54:01',NULL),(1522,'1',NULL,'2016-03-17 15:55:01',NULL),(1523,'1',NULL,'2016-03-17 15:56:01',NULL),(1524,'1',NULL,'2016-03-17 15:57:01',NULL),(1525,'1',NULL,'2016-03-17 15:58:01',NULL),(1526,'1',NULL,'2016-03-17 15:59:01',NULL),(1527,'1',NULL,'2016-03-17 16:00:01',NULL),(1528,'1',NULL,'2016-03-17 16:01:01',NULL),(1529,'1',NULL,'2016-03-17 16:02:01',NULL),(1530,'1',NULL,'2016-03-17 16:03:01',NULL),(1531,'1',NULL,'2016-03-17 16:04:01',NULL),(1532,'1',NULL,'2016-03-17 16:05:02',NULL),(1533,'1',NULL,'2016-03-17 16:06:01',NULL),(1534,'1',NULL,'2016-03-17 16:07:01',NULL),(1535,'1',NULL,'2016-03-17 16:08:01',NULL),(1536,'1',NULL,'2016-03-17 16:09:01',NULL),(1537,'1',NULL,'2016-03-17 16:10:01',NULL),(1538,'1',NULL,'2016-03-17 16:11:01',NULL),(1539,'1',NULL,'2016-03-17 16:12:01',NULL),(1540,'1',NULL,'2016-03-17 16:13:01',NULL),(1541,'1',NULL,'2016-03-17 16:14:02',NULL),(1542,'1',NULL,'2016-03-17 16:15:01',NULL),(1543,'1',NULL,'2016-03-17 16:16:02',NULL),(1544,'1',NULL,'2016-03-17 16:17:01',NULL),(1545,'1',NULL,'2016-03-17 16:18:01',NULL),(1546,'1',NULL,'2016-03-17 16:19:01',NULL),(1547,'1',NULL,'2016-03-17 16:20:01',NULL),(1548,'1',NULL,'2016-03-17 16:21:01',NULL),(1549,'1',NULL,'2016-03-17 16:22:01',NULL),(1550,'1',NULL,'2016-03-17 16:23:01',NULL),(1551,'1',NULL,'2016-03-17 16:24:01',NULL),(1552,'1',NULL,'2016-03-17 16:25:01',NULL),(1553,'1',NULL,'2016-03-17 16:26:02',NULL),(1554,'1',NULL,'2016-03-17 16:27:01',NULL),(1555,'1',NULL,'2016-03-17 16:28:01',NULL),(1556,'1',NULL,'2016-03-17 16:29:01',NULL),(1557,'1',NULL,'2016-03-17 16:30:01',NULL),(1558,'1',NULL,'2016-03-17 16:31:01',NULL),(1559,'1',NULL,'2016-03-17 16:32:01',NULL),(1560,'1',NULL,'2016-03-17 16:33:02',NULL),(1561,'1',NULL,'2016-03-17 16:34:01',NULL),(1562,'1',NULL,'2016-03-17 16:35:01',NULL),(1563,'1',NULL,'2016-03-17 16:36:01',NULL),(1564,'1',NULL,'2016-03-17 16:37:02',NULL),(1565,'1',NULL,'2016-03-17 16:38:01',NULL),(1566,'1',NULL,'2016-03-17 16:39:01',NULL),(1567,'1',NULL,'2016-03-17 16:40:01',NULL),(1568,'1',NULL,'2016-03-17 16:41:01',NULL),(1569,'1',NULL,'2016-03-17 16:42:02',NULL),(1570,'1',NULL,'2016-03-17 16:43:01',NULL),(1571,'1',NULL,'2016-03-17 16:44:01',NULL),(1572,'1',NULL,'2016-03-17 16:45:01',NULL),(1573,'1',NULL,'2016-03-17 16:46:01',NULL),(1574,'1',NULL,'2016-03-17 16:47:01',NULL),(1575,'1',NULL,'2016-03-17 16:48:01',NULL),(1576,'1',NULL,'2016-03-17 16:49:01',NULL),(1577,'1',NULL,'2016-03-17 16:50:01',NULL),(1578,'1',NULL,'2016-03-17 16:51:01',NULL),(1579,'1',NULL,'2016-03-17 16:52:01',NULL),(1580,'1',NULL,'2016-03-17 16:53:01',NULL),(1581,'1',NULL,'2016-03-17 16:54:01',NULL),(1582,'1',NULL,'2016-03-17 16:55:01',NULL),(1583,'1',NULL,'2016-03-17 16:56:01',NULL),(1584,'1',NULL,'2016-03-17 16:57:01',NULL),(1585,'1',NULL,'2016-03-17 16:58:01',NULL),(1586,'1',NULL,'2016-03-17 16:59:01',NULL),(1587,'1',NULL,'2016-03-17 17:00:01',NULL),(1588,'1',NULL,'2016-03-17 17:01:01',NULL),(1589,'1',NULL,'2016-03-17 17:02:01',NULL),(1590,'1',NULL,'2016-03-17 17:03:01',NULL),(1591,'1',NULL,'2016-03-17 17:04:01',NULL),(1592,'1',NULL,'2016-03-17 17:05:01',NULL),(1593,'1',NULL,'2016-03-17 17:06:01',NULL),(1594,'1',NULL,'2016-03-17 17:07:01',NULL),(1595,'1',NULL,'2016-03-17 17:08:01',NULL),(1596,'1',NULL,'2016-03-17 17:09:01',NULL),(1597,'1',NULL,'2016-03-17 17:10:01',NULL),(1598,'1',NULL,'2016-03-17 17:11:01',NULL),(1599,'1',NULL,'2016-03-17 17:12:01',NULL),(1600,'1',NULL,'2016-03-17 17:13:01',NULL),(1601,'1',NULL,'2016-03-17 17:14:01',NULL),(1602,'1',NULL,'2016-03-17 17:15:01',NULL),(1603,'1',NULL,'2016-03-17 17:16:01',NULL),(1604,'1',NULL,'2016-03-17 17:17:01',NULL),(1605,'1',NULL,'2016-03-17 17:18:01',NULL),(1606,'1',NULL,'2016-03-17 17:19:02',NULL),(1607,'1',NULL,'2016-03-17 17:20:01',NULL),(1608,'1',NULL,'2016-03-17 17:21:01',NULL),(1609,'1',NULL,'2016-03-17 17:22:01',NULL),(1610,'1',NULL,'2016-03-17 17:23:01',NULL),(1611,'1',NULL,'2016-03-17 17:24:01',NULL),(1612,'1',NULL,'2016-03-17 17:25:01',NULL),(1613,'1',NULL,'2016-03-17 17:26:01',NULL),(1614,'1',NULL,'2016-03-17 17:27:02',NULL),(1615,'1',NULL,'2016-03-17 17:28:01',NULL),(1616,'1',NULL,'2016-03-17 17:29:01',NULL),(1617,'1',NULL,'2016-03-17 17:30:01',NULL),(1618,'1',NULL,'2016-03-17 17:31:01',NULL),(1619,'1',NULL,'2016-03-17 17:32:01',NULL),(1620,'1',NULL,'2016-03-17 17:33:01',NULL),(1621,'1',NULL,'2016-03-17 17:34:01',NULL),(1622,'1',NULL,'2016-03-17 17:35:01',NULL),(1623,'1',NULL,'2016-03-17 17:36:01',NULL),(1624,'1',NULL,'2016-03-17 17:37:01',NULL),(1625,'1',NULL,'2016-03-17 17:38:01',NULL),(1626,'1',NULL,'2016-03-17 17:39:01',NULL),(1627,'1',NULL,'2016-03-17 17:40:01',NULL),(1628,'1',NULL,'2016-03-17 17:41:01',NULL),(1629,'1',NULL,'2016-03-17 17:42:01',NULL),(1630,'1',NULL,'2016-03-17 17:43:01',NULL),(1631,'1',NULL,'2016-03-17 17:44:01',NULL),(1632,'1',NULL,'2016-03-17 17:45:01',NULL),(1633,'1',NULL,'2016-03-17 17:46:01',NULL),(1634,'1',NULL,'2016-03-17 17:47:01',NULL),(1635,'1',NULL,'2016-03-17 17:48:01',NULL),(1636,'1',NULL,'2016-03-17 17:49:01',NULL),(1637,'1',NULL,'2016-03-17 17:50:01',NULL),(1638,'1',NULL,'2016-03-17 17:51:01',NULL),(1639,'1',NULL,'2016-03-17 17:52:01',NULL),(1640,'1',NULL,'2016-03-17 17:53:02',NULL),(1641,'1',NULL,'2016-03-17 17:54:01',NULL),(1642,'1',NULL,'2016-03-17 17:55:01',NULL),(1643,'1',NULL,'2016-03-17 17:56:01',NULL),(1644,'1',NULL,'2016-03-17 17:57:01',NULL),(1645,'1',NULL,'2016-03-17 17:58:02',NULL),(1646,'1',NULL,'2016-03-17 17:59:01',NULL),(1647,'1',NULL,'2016-03-17 18:00:01',NULL),(1648,'1',NULL,'2016-03-17 18:01:01',NULL),(1649,'1',NULL,'2016-03-17 18:02:01',NULL),(1650,'1',NULL,'2016-03-17 18:03:02',NULL),(1651,'1',NULL,'2016-03-17 18:04:01',NULL),(1652,'1',NULL,'2016-03-17 18:05:01',NULL),(1653,'1',NULL,'2016-03-17 18:06:01',NULL),(1654,'1',NULL,'2016-03-17 18:07:02',NULL),(1655,'1',NULL,'2016-03-17 18:08:02',NULL),(1656,'1',NULL,'2016-03-17 18:09:01',NULL),(1657,'1',NULL,'2016-03-17 18:10:01',NULL),(1658,'1',NULL,'2016-03-17 18:11:01',NULL),(1659,'1',NULL,'2016-03-17 18:12:01',NULL),(1660,'1',NULL,'2016-03-17 18:13:01',NULL),(1661,'1',NULL,'2016-03-17 18:14:01',NULL),(1662,'1',NULL,'2016-03-17 18:15:01',NULL),(1663,'1',NULL,'2016-03-17 18:16:01',NULL),(1664,'1',NULL,'2016-03-17 18:17:01',NULL),(1665,'1',NULL,'2016-03-17 18:18:01',NULL),(1666,'1',NULL,'2016-03-17 18:19:02',NULL),(1667,'1',NULL,'2016-03-17 18:20:01',NULL),(1668,'1',NULL,'2016-03-17 18:21:01',NULL),(1669,'1',NULL,'2016-03-17 18:22:01',NULL),(1670,'1',NULL,'2016-03-17 18:23:01',NULL);

/*Table structure for table `ob_field` */

DROP TABLE IF EXISTS `ob_field`;

CREATE TABLE `ob_field` (
  `Id` int(10) NOT NULL auto_increment,
  `FieldName` varchar(200) default NULL,
  `FieldType` varchar(200) default NULL,
  `FieldValidation` varchar(100) default NULL,
  `RequiredCheck` varchar(100) default NULL,
  `Priority` int(10) default NULL,
  `ClientId` int(10) default NULL,
  `createdate` datetime default NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Data for the table `ob_field` */

insert  into `ob_field`(`Id`,`FieldName`,`FieldType`,`FieldValidation`,`RequiredCheck`,`Priority`,`ClientId`,`createdate`) values (1,'dsfsadf','TextBox','Numeric',NULL,1,5,NULL),(2,'dsfsadf','TextBox','Numeric','1',2,5,NULL),(3,'dsfsadf','TextBox','Numeric','1',3,5,'2016-02-24 18:26:35'),(10,'Contact','TextBox','Char','1',1,6,'2016-03-01 18:16:34'),(9,'tyryr','DropDown','Char','1',4,5,'2016-02-24 19:22:11'),(11,'Name','TextBox','Char','1',2,6,'2016-03-01 18:16:43'),(12,'Address','TextArea','Char','1',3,6,'2016-03-01 18:16:54'),(13,'Complain Type','DropDown','Char','1',4,6,'2016-03-01 18:17:44'),(14,'MSISDN','TextBox','Numeric','1',1,1,'2016-03-05 15:55:17');

/*Table structure for table `ob_field_value` */

DROP TABLE IF EXISTS `ob_field_value`;

CREATE TABLE `ob_field_value` (
  `Id` int(10) NOT NULL auto_increment,
  `FieldId` int(10) default NULL,
  `FieldValueName` varchar(300) default NULL,
  `ClientId` varchar(20) default NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `ob_field_value` */

insert  into `ob_field_value`(`Id`,`FieldId`,`FieldValueName`,`ClientId`) values (1,8,'rwerew','5'),(2,8,'er','5'),(3,8,'dgdg','5'),(4,8,'rt','5'),(5,9,'tyry','5'),(6,9,'56464','5'),(7,13,'Service','6'),(8,13,'Develeary','6'),(9,13,'Data Recovery','6');

/*Table structure for table `ob_pr` */

DROP TABLE IF EXISTS `ob_pr`;

CREATE TABLE `ob_pr` (
  `id` int(10) NOT NULL auto_increment,
  `Ecr` varchar(50) default NULL,
  `Parent_id` varchar(11) default NULL,
  `Label` int(11) default NULL,
  `CampaignId` varchar(20) default NULL,
  `ClientId` varchar(100) default NULL,
  `createdate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;

/*Data for the table `ob_pr` */

insert  into `ob_pr`(`id`,`Ecr`,`Parent_id`,`Label`,`CampaignId`,`ClientId`,`createdate`) values (63,'Driviing Licence','61',4,'3','1','2016-03-07 18:55:32'),(45,'Inquiry',NULL,1,'1','6','2016-03-02 16:33:55'),(44,'Comlain',NULL,1,'2','6','2016-03-02 16:33:45'),(62,'Voter ID Card','61',4,'3','1','2016-03-07 18:55:11'),(57,'Pospaid',NULL,1,'3','1','2016-03-07 18:50:35'),(60,'New Sim','57',2,'3','1','2016-03-07 18:54:28'),(59,'Old Sim','57',2,'3','1','2016-03-07 18:51:48'),(61,'Document','60',3,'3','1','2016-03-07 18:54:57'),(47,'Hardware','44',2,'2','6','2016-03-02 16:34:46'),(48,'Software','44',2,'2','6','2016-03-02 16:34:56'),(49,'Hardisk','47',3,'2','6','2016-03-02 16:35:27'),(50,'window','48',3,'2','6','2016-03-02 16:35:41'),(51,'Data Recovery','49',4,'2','6','2016-03-02 16:36:14'),(52,'Recover New','50',4,'2','6','2016-03-02 16:36:48'),(53,'Urgent Basis','51',5,'2','6','2016-03-02 16:37:36'),(54,'Normaly Time','52',5,'2','6','2016-03-02 16:38:04'),(56,'Prepaid',NULL,1,'3','1','2016-03-07 18:50:25');

/*Table structure for table `ob_up_data` */

DROP TABLE IF EXISTS `ob_up_data`;

CREATE TABLE `ob_up_data` (
  `id` int(10) NOT NULL auto_increment,
  `ClientId` varchar(10) default NULL,
  `CampaignName` varchar(100) default NULL,
  `Header` varchar(200) default NULL,
  `Field` varchar(200) default NULL,
  `createdate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

/*Data for the table `ob_up_data` */

insert  into `ob_up_data`(`id`,`ClientId`,`CampaignName`,`Header`,`Field`,`createdate`) values (1,'6','Campaign One','MSISDN','7876765654','2016-03-01 14:02:21'),(2,'6','Campaign One','Client Name','Chandresh Tripathi','2016-03-01 14:02:21'),(3,'6','Campaign One','Address','Laxmi Nagar','2016-03-01 14:02:21'),(4,'6','Campaign One','Complain Type','Data Recovery','2016-03-01 14:02:21'),(5,'6','Campaign One','MSISDN','8755454321','2016-03-01 15:39:15'),(6,'6','Campaign One','Client Name','Anil Kumar','2016-03-01 15:39:15'),(7,'6','Campaign One','Address','Noida','2016-03-01 15:39:15'),(8,'6','Campaign One','Complain Type','Vodafone complain','2016-03-01 15:39:15'),(9,'6','Campaign One','MSISDN','7877767876','2016-03-01 17:20:32'),(10,'6','Campaign One','Client Name','Rajesh Kumar','2016-03-01 17:20:32'),(11,'6','Campaign One','Address','Delhi 92 Shakarpur','2016-03-01 17:20:32'),(12,'6','Campaign One','Complain Type','System Complain','2016-03-01 17:20:32'),(13,'6','Campaign Two','Mobile No','8987876565','2016-03-01 17:29:43'),(14,'6','Campaign Two','Client Name','Prakesh Kumnar','2016-03-01 17:29:43'),(15,'6','Campaign Two','Address','Punjab','2016-03-01 17:29:43'),(16,'6','Campaign Two','Contact 2','7876766656','2016-03-01 17:29:43'),(17,'6','Campaign Two','Complain Type','Service Complain','2016-03-01 17:29:43'),(18,'6','Campaign Two','Complain Id','110012','2016-03-01 17:29:43'),(19,'6','Campaign Two','Mobile No','6656545434','2016-03-01 17:31:44'),(20,'6','Campaign Two','Client Name','Umesh Shingh','2016-03-01 17:31:44'),(21,'6','Campaign Two','Address','New Delhi','2016-03-01 17:31:44'),(22,'6','Campaign Two','Contact 2','7876765654','2016-03-01 17:31:44'),(23,'6','Campaign Two','Complain Type','Tab Problem','2016-03-01 17:31:44'),(24,'6','Campaign Two','Complain Id','110013','2016-03-01 17:31:44'),(25,'6','Campaign Three','Name','Chandan','2016-03-01 17:42:14'),(26,'6','Campaign Three','Address1','Shakarpur','2016-03-01 17:42:14'),(27,'6','Campaign Three','Address2','Laxmi Nagar','2016-03-01 17:42:14'),(28,'6','Campaign Three','Contact 1','8787676565','2016-03-01 17:42:14'),(29,'6','Campaign Three','Contact 2','8987876765','2016-03-01 17:42:14'),(30,'6','Campaign Three','Complain Type','Data Recovery','2016-03-01 17:42:14'),(31,'6','Campaign Three','Complain Id','110034','2016-03-01 17:42:14'),(32,'6','Campaign Three','Land Mark','Near Hera Sweet','2016-03-01 17:42:14');

/*Table structure for table `pages_master` */

DROP TABLE IF EXISTS `pages_master`;

CREATE TABLE `pages_master` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `page_name` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `pages_master` */

insert  into `pages_master`(`id`,`page_name`) values (1,'Page 1'),(2,'Page 2'),(3,'Page 3'),(4,'Page 4'),(5,'Page 5'),(6,'Page 6'),(7,'Page 7'),(8,'Page 8'),(9,'Page 9'),(10,'Page 10');

/*Table structure for table `plan_master` */

DROP TABLE IF EXISTS `plan_master`;

CREATE TABLE `plan_master` (
  `id` int(10) NOT NULL auto_increment,
  `clientID` varchar(20) NOT NULL,
  `clientName` varchar(100) NOT NULL,
  `PlanAmount` double default NULL,
  `InboundCallCharge` double default NULL,
  `OutboundCallCharge` double default NULL,
  `SmsCallCharge` double default NULL,
  `EmailCallCharge` double default NULL,
  `CreateDate` datetime NOT NULL,
  PRIMARY KEY  (`id`,`CreateDate`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `plan_master` */

insert  into `plan_master`(`id`,`clientID`,`clientName`,`PlanAmount`,`InboundCallCharge`,`OutboundCallCharge`,`SmsCallCharge`,`EmailCallCharge`,`CreateDate`) values (1,'1','Mas Call Net',500,0.35,0.45,2.5,3.5,'2016-03-26 10:41:28'),(2,'3','Dialdesk',2000,0.45,0.65,3,2.5,'2016-03-26 11:07:48');

/*Table structure for table `registration_master` */

DROP TABLE IF EXISTS `registration_master`;

CREATE TABLE `registration_master` (
  `company_id` int(10) unsigned NOT NULL auto_increment,
  `create_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modify_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `email_verify` varchar(10) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `reg_office_address1` varchar(255) NOT NULL,
  `reg_office_address2` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `auth_person` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `phone_no` bigint(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comm_address1` varchar(255) NOT NULL,
  `comm_address2` varchar(255) NOT NULL,
  `comm_city` varchar(50) NOT NULL,
  `comm_state` varchar(50) NOT NULL,
  `comm_pincode` varchar(10) NOT NULL,
  `contact_person1` varchar(255) NOT NULL,
  `cp1_designation` varchar(255) NOT NULL,
  `cp1_phone` varchar(255) NOT NULL,
  `cp1_email` varchar(255) NOT NULL,
  `contact_person2` varchar(255) NOT NULL,
  `cp2_designation` varchar(255) NOT NULL,
  `cp2_phone` varchar(255) NOT NULL,
  `cp2_email` varchar(255) NOT NULL,
  `contact_person3` varchar(255) NOT NULL,
  `cp3_designation` varchar(255) NOT NULL,
  `cp3_phone` varchar(255) NOT NULL,
  `cp3_email` varchar(255) NOT NULL,
  `upload_file` varchar(255) NOT NULL,
  `incorporation_certificate` varchar(255) NOT NULL,
  `pancard` varchar(255) NOT NULL,
  `bill_address_prof` varchar(255) NOT NULL,
  `authorized_id_prof` varchar(255) NOT NULL,
  `auth_person_address_prof` varchar(255) NOT NULL,
  `password` varchar(255) default NULL,
  `admin_verification` varchar(255) default NULL,
  PRIMARY KEY  (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `registration_master` */

insert  into `registration_master`(`company_id`,`create_date`,`modify_date`,`ip`,`status`,`email_verify`,`company_name`,`reg_office_address1`,`reg_office_address2`,`city`,`state`,`pincode`,`auth_person`,`designation`,`phone_no`,`email`,`comm_address1`,`comm_address2`,`comm_city`,`comm_state`,`comm_pincode`,`contact_person1`,`cp1_designation`,`cp1_phone`,`cp1_email`,`contact_person2`,`cp2_designation`,`cp2_phone`,`cp2_email`,`contact_person3`,`cp3_designation`,`cp3_phone`,`cp3_email`,`upload_file`,`incorporation_certificate`,`pancard`,`bill_address_prof`,`authorized_id_prof`,`auth_person_address_prof`,`password`,`admin_verification`) values (1,'2016-02-13 10:49:59','0000-00-00 00:00:00','192.168.137.154',1,'yes','Mas Call Net','Vaishali','Laxmi Nagar','Alahabad','UP','110012','Chandresh Mani Tripathi','PHP Developer',7838982398,'chandresh.mani001@gmail.com','Vaishali','Laxmi Nagar','Alahabad','1','110012','Ravi Kumar','Java Developer','1000000000','ravi@gmail.com','Anil Kumar','PHP Developer','8987876765','anil@gmail.com','Mahesh Kumar','Java Developer','9898787676','mahesh@gmail.com','','360115certificate of incorporation.JPG','','','','','india123','yes'),(2,'2016-02-13 12:34:46','0000-00-00 00:00:00','192.168.137.8',1,'yes','Ispark Data Connect pvt ltd','B 24 Okhla','Phase 2','Alahabad','UP','110020','Kapil Mohan','Director',9873192648,'kapil.mohan@teammas.in','B 24 Okhla','','Alahabad','1','njhij','Kapil Mohan','','9873192648','kapil.mohan@teammas.in','','','0','','','','0','','','','','','','','kapil',NULL),(3,'2016-02-18 11:18:13','0000-00-00 00:00:00','192.168.137.154',1,'yes','Dialdesk','Laxminagar','Shakarpur','Alahabad','UP','110012','Chandresh Mani Tripathi','PHP Developer',3333333333,'chandresh12345@gmail.com','Laxminagar','Shakarpur','','','110012','Ravi Kumar','PHP Developer','8987876765','ravi@gmail.com','Anil Kuamr','PHP Developer','8978787676','anil@gmail.com','Umesh Kumar','Java Developer','7876767656','umesh@gmail.com','','','','','','','india123',NULL),(4,'2016-02-19 10:44:36','0000-00-00 00:00:00','192.168.137.161',1,'no','i spark New','Shakarpur Khash Laxmi Nagar','kaushambi','Alahabad','UP','476888','shilpa jain','developer',8130486101,'shilpa.jain2@gmail.com','vaishali','kaushambi','Alahabad','1','476888','kajal','Hr','9179923187','kajal@gmail.com','Chandresh Mani Tripathi','PHP  Developer','0','','','','0','','','','','','','','12345',NULL),(5,'2016-02-19 11:24:04','0000-00-00 00:00:00','192.168.137.161',1,'yes','i spark','ansal plaza \\nvaishali sector 4\\n3rd floor...','','Gorakhpur','Bihar','478888','kajal','php developer',9179923187,'shilpa.jain@teammas.in','ansal plaza \\nvaishali sector 4\\n3rd floor...','','Gorakhpur','2','478888','shilpa jain','HR','9179923187','shilpa.jain@teammas.in','','','0','','','','0','','','','','','','','1423@shilpa',NULL),(6,'2016-02-20 10:54:38','0000-00-00 00:00:00','192.168.137.154',1,'yes','Mas Call Net New','','','Alahabad','UP','100012','','',4444444444,'chandresh321@gmail.com','Laxmi Nagar','Shakarpur','Alahabad','1','100012','Ravi Kumar','PHP Developer','7777777777','ravi@gmail.com','','','','','','','','','','504136Address Proof- Telephone Bill March-2014-pg 1.jpg,288244needslike-plan-new-ppt-100115-8-638.jpg','687984CivilianCard-3.jpg','539320PAN-CARD.jpg','52107403hyvsk01-PoA_GT421_336120e.jpg','168776CivilianCard-3.jpg','india12',NULL),(7,'2016-02-27 18:10:28','0000-00-00 00:00:00','192.168.137.154',1,'yes','Dials Communication','HNo 90 Shakarpur Khash Laxmi Nagar Delhi 92','HNo 90 Shakarpur Khash Laxmi Nagar Delhi 92','Alahabad','UP','110012','Chandresh Kumar Tripathi','PHP Developer',2222222222,'chandresh.tripathi@teammas.in','HNo 90 Shakarpur Khash Laxmi Nagar Delhi 92','HNo 90 Shakarpur Khash Laxmi Nagar Delhi 92','Alahabad','1','110012','Anil kumar','Java Developer','1111111111','anil@gmail.com','Ravi Kumar','PHP Developer','1111111111','ravi@gmial.com','Shilpa','PHP Developer','1111111111','shilpa@gmail.com','','','','','','','india123','yes'),(8,'2016-03-21 12:04:24','0000-00-00 00:00:00','192.168.137.154',1,'yes','Chandresh Mani','LaxmiNagar','','Alahabad','UP','110012','Chandresh','',1111111111,'chandresh123@gmail.com','LaxmiNagar','','Alahabad','1','110012','Ravi','','8888888888','ravi@gmail.com','','','','','','','','','','','','','','','india123',NULL);

/*Table structure for table `report_master` */

DROP TABLE IF EXISTS `report_master`;

CREATE TABLE `report_master` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `report_name` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `report_master` */

insert  into `report_master`(`id`,`report_name`) values (1,'Date wise calling MIS'),(2,'Category wise MIS'),(3,'Type / Subtype MIS'),(4,'SR Status wise MIS');

/*Table structure for table `reportmatrix_master` */

DROP TABLE IF EXISTS `reportmatrix_master`;

CREATE TABLE `reportmatrix_master` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `client_id` int(11) default NULL,
  `user_name` varchar(255) default NULL,
  `user_designation` varchar(255) default NULL,
  `user_mobile` bigint(20) default NULL,
  `user_email` varchar(255) default NULL,
  `report` varchar(255) default NULL,
  `report_type` varchar(255) default NULL,
  `report_value` varchar(255) default NULL,
  `send_type` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `reportmatrix_master` */

insert  into `reportmatrix_master`(`id`,`client_id`,`user_name`,`user_designation`,`user_mobile`,`user_email`,`report`,`report_type`,`report_value`,`send_type`) values (1,1,'Chandresh Mani Tripathi','PHP Developer',9999999999,'','Date wise calling MIS','monthly','tuesday','sms'),(2,1,'Chandresh Mani Tripathi','PHP Developer',NULL,'chandresh.mani001@gmail.com','Date wise calling MIS','daily','2','email');

/*Table structure for table `state_master` */

DROP TABLE IF EXISTS `state_master`;

CREATE TABLE `state_master` (
  `Id` int(10) NOT NULL auto_increment,
  `StateName` varchar(200) default NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `state_master` */

insert  into `state_master`(`Id`,`StateName`) values (1,'UP'),(2,'Bihar');

/*Table structure for table `tbl_user` */

DROP TABLE IF EXISTS `tbl_user`;

CREATE TABLE `tbl_user` (
  `id` int(10) NOT NULL auto_increment,
  `UserName` varchar(50) default NULL,
  `Password` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_uname` (`UserName`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_user` */

insert  into `tbl_user`(`id`,`UserName`,`Password`) values (1,'admin','admin');

/*Table structure for table `tmp_call_master` */

DROP TABLE IF EXISTS `tmp_call_master`;

CREATE TABLE `tmp_call_master` (
  `Id` int(10) NOT NULL default '0',
  `ClientId` bigint(12) default NULL,
  `SrNo` bigint(12) default NULL,
  `MSISDN` bigint(12) default NULL,
  `Category1` varchar(200) default NULL,
  `Category2` varchar(200) default NULL,
  `Category3` varchar(200) default NULL,
  `Category4` varchar(200) default NULL,
  `Category5` varchar(200) default NULL,
  `Field1` varchar(500) default NULL,
  `Field2` varchar(500) default NULL,
  `Field3` varchar(500) default NULL,
  `Field4` varchar(500) default NULL,
  `Field5` varchar(500) default NULL,
  `Field6` varchar(500) default NULL,
  `Field7` varchar(500) default NULL,
  `Field8` varchar(500) default NULL,
  `Field9` varchar(500) default NULL,
  `Field10` varchar(500) default NULL,
  `Field11` varchar(500) default NULL,
  `Field12` varchar(500) default NULL,
  `Field13` varchar(500) default NULL,
  `Field14` varchar(500) default NULL,
  `Field15` varchar(500) default NULL,
  `Field16` varchar(500) default NULL,
  `Field17` varchar(500) default NULL,
  `Field18` varchar(500) default NULL,
  `Field19` varchar(500) default NULL,
  `Field20` varchar(500) default NULL,
  `CallDate` datetime default NULL,
  `AgentId` int(10) default NULL,
  `LeadId` bigint(12) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `tmp_call_master` */

/*Table structure for table `tmp_ob_data` */

DROP TABLE IF EXISTS `tmp_ob_data`;

CREATE TABLE `tmp_ob_data` (
  `id` int(10) NOT NULL auto_increment,
  `ClientId` int(10) default NULL,
  `CampaignName` varchar(100) default NULL,
  `Field1` varchar(200) default NULL,
  `Field2` varchar(200) default NULL,
  `Field3` varchar(200) default NULL,
  `Field4` varchar(200) default NULL,
  `Field5` varchar(200) default NULL,
  `Field6` varchar(200) default NULL,
  `Field7` varchar(200) default NULL,
  `Field8` varchar(200) default NULL,
  `Field9` varchar(200) default NULL,
  `Field10` varchar(200) default NULL,
  `createdate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `tmp_ob_data` */

insert  into `tmp_ob_data`(`id`,`ClientId`,`CampaignName`,`Field1`,`Field2`,`Field3`,`Field4`,`Field5`,`Field6`,`Field7`,`Field8`,`Field9`,`Field10`,`createdate`) values (1,6,'Complaint','Data1','Data2','Data3','Data4','Data5','Data6','Data7',NULL,NULL,NULL,'2016-02-29 11:40:47'),(2,5,'final',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-29 11:41:43'),(3,5,'request',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-29 11:42:44'),(4,6,'Complaint','Data1','Data2','Data3','Data4','Data5','Data6','Data7',NULL,NULL,NULL,'2016-02-29 16:21:31'),(5,6,'Request','VAL','VAL','VAL','VAL','VAL',NULL,NULL,NULL,NULL,NULL,'2016-02-29 16:23:46');

/*Table structure for table `tmp_registration_master` */

DROP TABLE IF EXISTS `tmp_registration_master`;

CREATE TABLE `tmp_registration_master` (
  `company_id` int(10) unsigned NOT NULL auto_increment,
  `sameAs` varchar(10) default NULL,
  `create_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `modify_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `email_verify` varchar(10) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `reg_office_address1` varchar(255) NOT NULL,
  `reg_office_address2` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `auth_person` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `phone_no` bigint(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comm_address1` varchar(255) NOT NULL,
  `comm_address2` varchar(255) NOT NULL,
  `comm_city` varchar(50) NOT NULL,
  `comm_state` varchar(50) NOT NULL,
  `comm_pincode` varchar(10) NOT NULL,
  `contact_person1` varchar(255) NOT NULL,
  `cp1_designation` varchar(255) NOT NULL,
  `cp1_phone` varchar(255) NOT NULL,
  `cp1_email` varchar(255) NOT NULL,
  `contact_person2` varchar(255) NOT NULL,
  `cp2_designation` varchar(255) NOT NULL,
  `cp2_phone` varchar(255) NOT NULL,
  `cp2_email` varchar(255) NOT NULL,
  `contact_person3` varchar(255) NOT NULL,
  `cp3_designation` varchar(255) NOT NULL,
  `cp3_phone` varchar(255) NOT NULL,
  `cp3_email` varchar(255) NOT NULL,
  `upload_file` varchar(255) NOT NULL,
  `incorporation_certificate` varchar(255) NOT NULL,
  `pancard` varchar(255) NOT NULL,
  `bill_address_prof` varchar(255) NOT NULL,
  `authorized_id_prof` varchar(255) NOT NULL,
  `auth_person_address_prof` varchar(255) NOT NULL,
  `password` varchar(255) default NULL,
  PRIMARY KEY  (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tmp_registration_master` */

/*Table structure for table `training_master` */

DROP TABLE IF EXISTS `training_master`;

CREATE TABLE `training_master` (
  `id` int(11) NOT NULL auto_increment,
  `ClientId` varchar(11) default NULL,
  `Field1` varchar(50) default NULL,
  `Field2` varchar(50) default NULL,
  `Field3` varchar(50) default NULL,
  `Field4` varchar(50) default NULL,
  `Field5` varchar(50) default NULL,
  `Field6` varchar(50) default NULL,
  `Field7` varchar(50) default NULL,
  `Field8` varchar(50) default NULL,
  `Field9` varchar(50) default NULL,
  `Field10` varchar(50) default NULL,
  `createdate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `training_master` */

insert  into `training_master`(`id`,`ClientId`,`Field1`,`Field2`,`Field3`,`Field4`,`Field5`,`Field6`,`Field7`,`Field8`,`Field9`,`Field10`,`createdate`) values (1,'2','Chrysanthemum.jpg','Desert.jpg','Lighthouse.jpg','Penguins.jpg',NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-08 10:33:31'),(2,'2','Agreement - Tandem Healthcare Pvt. Ltd..docx','bill no 101.jpeg','Branch Developement Plan.pptx',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-02-13 13:09:50'),(3,'6','image_gallery.gif','pluslogo.jpg','pluslogo1.jpg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-03-09 18:51:07');

/*Table structure for table `vfo_master` */

DROP TABLE IF EXISTS `vfo_master`;

CREATE TABLE `vfo_master` (
  `id` int(11) NOT NULL auto_increment,
  `clientId` varchar(20) default NULL,
  `parent_id` varchar(20) default NULL,
  `contactNo` decimal(20,0) default NULL,
  `createdate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `vfo_master` */

/* Function  structure for function  `getClientName` */

/*!50003 DROP FUNCTION IF EXISTS `getClientName` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`%` FUNCTION `getClientName`(cid INT) RETURNS char(200) CHARSET utf8
    DETERMINISTIC
BEGIN
DECLARE mClient VARCHAR(100);
 SET mClient = (SELECT `company_name` FROM `registration_master` WHERE `company_id` = cid);
 RETURN mClient;
END */$$
DELIMITER ;

/* Function  structure for function  `getSrno` */

/*!50003 DROP FUNCTION IF EXISTS `getSrno` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`%` FUNCTION `getSrno`(cid INT) RETURNS char(200) CHARSET utf8
    DETERMINISTIC
BEGIN
DECLARE mClient VARCHAR(100);
 SET mClient = (SELECT max(SrNo)+1 FROM `call_master` WHERE `ClientId` = cid);
 RETURN mClient;
END */$$
DELIMITER ;

/* Function  structure for function  `getSrnoOut` */

/*!50003 DROP FUNCTION IF EXISTS `getSrnoOut` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`%` FUNCTION `getSrnoOut`(cid INT) RETURNS char(200) CHARSET utf8
    DETERMINISTIC
BEGIN
DECLARE mClient VARCHAR(100);
 SET mClient = (SELECT MAX(SrNo)+1 FROM `call_master_out` WHERE `ClientId` = cid);
 RETURN mClient;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
