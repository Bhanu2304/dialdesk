/*
SQLyog Community v12.14 (32 bit)
MySQL - 5.5.8-log : Database - db_bill
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_bill` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db_bill`;

/*Table structure for table `branch_master` */

DROP TABLE IF EXISTS `branch_master`;

CREATE TABLE `branch_master` (
  `id` INT(100) NOT NULL AUTO_INCREMENT,
  `branch_name` VARCHAR(100) NOT NULL,
  `branch_code` VARCHAR(100) DEFAULT NULL,
  `createdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `branch_master` */

INSERT  INTO `branch_master`(`id`,`branch_name`,`branch_code`,`createdate`) VALUES 
(1,'Delhi','DE','2015-09-29 17:52:14');

/*Table structure for table `category_master` */

DROP TABLE IF EXISTS `category_master`;

CREATE TABLE `category_master` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category` VARCHAR(200) NOT NULL,
  `status` VARCHAR(20) DEFAULT NULL,
  `save_by` VARCHAR(100) DEFAULT NULL,
  `createdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `category_master` */

INSERT  INTO `category_master`(`id`,`category`,`status`,`save_by`,`createdate`) VALUES 
(1,'Voice\r\n','1','Save by Admin on 02 Sep 2013 ; \r\n','2015-09-23 17:46:26'),
(2,'Field\r\n','1','Save by Admin on 02 Sep 2013 ; \r\n','2015-09-23 17:46:24'),
(3,'Integrated Voice and Field\r\n','1','Save by Admin on 02 Sep 2013 ; \r\n','2015-09-23 17:46:23'),
(4,'BackOffice\r\n','1','Save by Admin on 02 Sep 2013 ; \r\n','2015-09-23 17:46:22');

/*Table structure for table `client_master` */

DROP TABLE IF EXISTS `client_master`;

CREATE TABLE `client_master` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(100) DEFAULT NULL,
  `branch_name` varchar(200) NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `client_master` */

insert  into `client_master`(`id`,`client_name`,`branch_name`,`createdate`) values 
(1,'Vodafone','Delhi','2015-09-29 17:52:29');

/*Table structure for table `company_master` */

DROP TABLE IF EXISTS `company_master`;

CREATE TABLE `company_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(200) DEFAULT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `company_master` */

insert  into `company_master`(`id`,`company_name`,`createdate`) values 
(1,'Mas Callnet India Pvt Ltd','2015-09-25 11:13:24');

/*Table structure for table `cost_master` */

DROP TABLE IF EXISTS `cost_master`;

CREATE TABLE `cost_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(200) DEFAULT NULL,
  `branch` varchar(200) DEFAULT NULL,
  `stream` varchar(200) DEFAULT NULL,
  `process` varchar(200) DEFAULT NULL,
  `category` varchar(200) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `client` varchar(200) DEFAULT NULL,
  `total_man_date` varchar(200) DEFAULT NULL,
  `shrinkage` varchar(200) DEFAULT NULL,
  `attrition` varchar(200) DEFAULT NULL,
  `shift` varchar(200) DEFAULT NULL,
  `working_days` varchar(200) DEFAULT NULL,
  `target_mandate` varchar(200) DEFAULT NULL,
  `over_saldays` varchar(200) DEFAULT NULL,
  `training_days` varchar(200) DEFAULT NULL,
  `incentive_allowed` varchar(200) DEFAULT NULL,
  `training_attrition` varchar(200) DEFAULT NULL,
  `deduction_allowed` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `process_manager` varchar(200) DEFAULT NULL,
  `emailid` varchar(200) DEFAULT NULL,
  `contact_no` varchar(200) DEFAULT NULL,
  `po_required` varchar(200) DEFAULT NULL,
  `jcc_no` varchar(200) DEFAULT NULL,
  `grn` varchar(200) DEFAULT NULL,
  `bill_to` varchar(200) DEFAULT NULL,
  `as_client` varchar(200) DEFAULT NULL,
  `b_Address1` varchar(200) DEFAULT NULL,
  `b_Address2` varchar(200) DEFAULT NULL,
  `b_Address3` varchar(200) DEFAULT NULL,
  `b_Address4` varchar(200) DEFAULT NULL,
  `b_Address5` varchar(200) DEFAULT NULL,
  `ship_to` varchar(200) DEFAULT NULL,
  `as_bill_to` varchar(200) DEFAULT NULL,
  `a_address1` varchar(200) DEFAULT NULL,
  `a_address2` varchar(200) DEFAULT NULL,
  `a_address3` varchar(200) DEFAULT NULL,
  `a_address4` varchar(200) DEFAULT NULL,
  `a_address5` varchar(200) DEFAULT NULL,
  `cost_center` varchar(200) NOT NULL,
  `view_ahmedabad` varchar(100) DEFAULT 'Yes',
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `cost_master` */

insert  into `cost_master`(`id`,`company_name`,`branch`,`stream`,`process`,`category`,`type`,`client`,`total_man_date`,`shrinkage`,`attrition`,`shift`,`working_days`,`target_mandate`,`over_saldays`,`training_days`,`incentive_allowed`,`training_attrition`,`deduction_allowed`,`description`,`process_manager`,`emailid`,`contact_no`,`po_required`,`jcc_no`,`grn`,`bill_to`,`as_client`,`b_Address1`,`b_Address2`,`b_Address3`,`b_Address4`,`b_Address5`,`ship_to`,`as_bill_to`,`a_address1`,`a_address2`,`a_address3`,`a_address4`,`a_address5`,`cost_center`,`view_ahmedabad`,`createdate`) values 
(1,'Mas Callnet India Pvt Ltd','Delhi','1','ACTIVE FIELD RETENTION\r\n','Voice\r\n','Contractual\r\n','Vodafone','','','','','','','Yes','','Yes','','','','','','','No','No','No','',NULL,'','','','','','',NULL,'','','','','','BSS/OS/DE/1','Yes','2015-10-05 16:48:11');

/*Table structure for table `inv_deduct_particulars` */

DROP TABLE IF EXISTS `inv_deduct_particulars`;

CREATE TABLE `inv_deduct_particulars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cost_center_id` int(11) DEFAULT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `cost_center` varchar(100) DEFAULT NULL,
  `fin_year` varchar(100) DEFAULT NULL,
  `month_for` varchar(100) DEFAULT NULL,
  `particulars` varchar(200) DEFAULT NULL,
  `rate` varchar(200) DEFAULT NULL,
  `qty` varchar(200) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `initial_id` int(11) DEFAULT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `inv_deduct_particulars` */

insert  into `inv_deduct_particulars`(`id`,`cost_center_id`,`company_name`,`username`,`branch_name`,`cost_center`,`fin_year`,`month_for`,`particulars`,`rate`,`qty`,`amount`,`initial_id`,`createdate`) values 
(1,1,'Mas Callnet India Pvt Ltd','branch','Delhi','BSS/OS/DE/1','2015-16','Nov','RGS 1','5','10','50',3,'2015-10-06 18:57:59'),
(2,1,'Mas Callnet India Pvt Ltd','branch','Delhi','BSS/OS/DE/1','2015-16','Nov','RGS 2','5','10','50',3,'2015-10-06 17:20:44');

/*Table structure for table `inv_particulars` */

DROP TABLE IF EXISTS `inv_particulars`;

CREATE TABLE `inv_particulars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cost_center_id` int(11) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `cost_center` varchar(100) DEFAULT NULL,
  `fin_year` varchar(100) DEFAULT NULL,
  `month_for` varchar(100) DEFAULT NULL,
  `particulars` varchar(200) DEFAULT NULL,
  `rate` varchar(200) DEFAULT NULL,
  `qty` varchar(200) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `initial_id` int(11) DEFAULT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `inv_particulars` */

insert  into `inv_particulars`(`id`,`cost_center_id`,`company_name`,`username`,`branch_name`,`cost_center`,`fin_year`,`month_for`,`particulars`,`rate`,`qty`,`amount`,`initial_id`,`createdate`) values 
(1,1,'Mas Callnet India Pvt Ltd','branch','Delhi','BSS/OS/DE/1','2015-16','Nov','RG 1','12','10','120',3,'2015-10-06 18:57:59'),
(2,1,'Mas Callnet India Pvt Ltd','branch','Delhi','BSS/OS/DE/1','2015-16','Nov','RG 2','11','10','110',3,'2015-10-06 17:20:16'),
(5,1,'Mas Callnet India Pvt Ltd','branch','Delhi','BSS/OS/DE/1','2014-15','Jun','particular','22','22','484',1,'2015-10-05 16:48:55'),
(6,1,'Mas Callnet India Pvt Ltd','branch','Delhi','BSS/OS/DE/1','2014-15','Jun','particular','33','33','1089',1,'2015-10-05 16:49:05'),
(7,1,'Mas Callnet India Pvt Ltd','branch','Delhi','BSS/OS/DE/1','2014-15','Jun','asldjf','22','22','484',2,'2015-10-06 14:42:24'),
(8,1,'Mas Callnet India Pvt Ltd','branch','Delhi','BSS/OS/DE/1','2014-15','Jun','asdf','22','2','44',2,'2015-10-06 14:42:26');

/*Table structure for table `pages` */

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `pages` */

insert  into `pages`(`id`,`page_name`) values 
(1,'add branch'),
(2,'add client'),
(3,'cost center'),
(4,'initial invoice'),
(5,'view invoice'),
(6,'branch view invoice'),
(7,'download pdf'),
(8,'manage access'),
(9,'edit po invoice'),
(10,'Dashboard'),
(11,'Check PO'),
(12,'view bill for grn'),
(13,'check grn'),
(14,'download grn'),
(15,'Create User');

/*Table structure for table `process_master` */

DROP TABLE IF EXISTS `process_master`;

CREATE TABLE `process_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stream` varchar(100) NOT NULL,
  `process_name` varchar(100) NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

/*Data for the table `process_master` */

insert  into `process_master`(`id`,`stream`,`process_name`,`createdate`) values 
(1,'BUSINESS SUPPORT SERVICES\r\n','MANPOWER OUTSOURCING\r\n','2015-09-24 15:52:41'),
(2,'BUSINESS SUPPORT SERVICES\r\n','CUSTOMER PROFILING\r\n','2015-09-23 17:27:21'),
(3,'BUSINESS SUPPORT SERVICES\r\n','ACTIVE FIELD COLLECTION\r\n','2015-09-23 17:27:27'),
(4,'BUSINESS SUPPORT SERVICES\r\n','ACTIVE FIELD RETENTION\r\n','2015-09-23 17:27:32'),
(5,'BUSINESS SUPPORT SERVICES\r\n','VS FIELD COLLECTIONS\r\n','2015-09-23 17:27:36'),
(6,'BUSINESS SUPPORT SERVICES\r\n','DATA & IMAGE MANAGEMENT\r\n','2015-09-23 17:27:43'),
(7,'BUSINESS SUPPORT SERVICES\r\n','SOFTWARE SUPPORT\r\n','2015-09-23 17:27:48'),
(8,'BUSINESS SUPPORT SERVICES\r\n','BSS-OTHERS\r\n','2015-09-23 17:27:52'),
(9,'CREDIT MANAGEMENT\r\n','COLLECTION MANAGEMENT\r\n','2015-09-23 17:28:25'),
(10,'CREDIT MANAGEMENT\r\n','EXPOSURE MANAGEMENT\r\n','2015-09-23 17:28:27'),
(11,'CREDIT MANAGEMENT\r\n','CMG -OTHERS\r\n','2015-09-23 17:28:33'),
(12,'CUSTOMER SERVICES\r\n','C-SAT\r\n','2015-09-23 17:29:58'),
(13,'CUSTOMER SERVICES\r\n','LEAD VALIDATION\r\n','2015-09-23 17:30:03'),
(14,'CUSTOMER SERVICES\r\n','ONBOARDING\r\n','2015-09-23 17:30:08'),
(15,'CUSTOMER SERVICES\r\n','CIP MANAGEMENT\r\n','2015-09-23 17:30:09'),
(16,'CUSTOMER SERVICES\r\n','PRO-ACTIVE CHURN MANAGEMENT\r\n','2015-09-23 17:30:13'),
(17,'CUSTOMER SERVICES\r\n','POST PAID RETENTION\r\n','2015-09-23 17:30:18'),
(18,'CUSTOMER SERVICES\r\n','INBOUND CUSTOMER SERVICES\r\n','2015-09-23 17:30:23'),
(19,'CUSTOMER SERVICES\r\n','CS-OTHERS\r\n','2015-09-23 17:30:28'),
(20,'SALES & MARKETING\r\n','PRE TO POST CONVERSION\r\n','2015-09-23 17:31:11'),
(21,'SALES & MARKETING\r\n','PREPAID RETENTION & UPSELLING\r\n','2015-09-23 17:31:20'),
(22,'SALES & MARKETING\r\n','GPRS UPSELLING\r\n','2015-09-23 17:31:25'),
(23,'SALES & MARKETING\r\n','POST PAID UPELLING\r\n','2015-09-23 17:31:29'),
(24,'SALES & MARKETING\r\n','NEW ACQUISITION & ADD ON\r\n','2015-09-23 17:31:34'),
(25,'SALES & MARKETING\r\n','S & M -OTHERS\r\n','2015-09-23 17:31:37'),
(26,'SALES & MARKETING\r\n','DIALDESK\r\n','2015-09-23 17:31:43'),
(27,'CUSTOMER SERVICES\r\n','MNP REJECTION\r\n','2015-09-23 17:32:00'),
(28,'BACK OFFICE\r\n','BACK OFFICE\r\n','2015-09-23 17:32:12'),
(29,'CUSTOMER SERVICES\r\n','PREPAID RETENTION & UPSELLING\r\n','2015-09-23 17:32:20'),
(30,'CUSTOMER SERVICES\r\n','SOFT COLLECTION & RETENTION\r\n','2015-09-23 17:32:28'),
(31,'CUSTOMER SERVICES\r\n','TELEVERIFICATION\r\n','2015-09-23 17:32:38'),
(32,'CUSTOMER SERVICES\r\n','INFORMATIVE\r\n','2015-09-23 17:32:47'),
(33,'CUSTOMER SERVICES\r\n','VIRTUAL ACCOUNT MANAGEMENT\r\n','2015-09-23 17:32:57'),
(34,'SALES & MARKETING\r\n','CUSTOMER PROFILING\r\n','2015-09-23 17:33:22'),
(35,'SALES & MARKETING\r\n','CUSTOMER ACQUISITION\r\n','2015-09-23 17:33:25'),
(36,'SALES & MARKETING\r\n','UPSELLING & CROSSELLING\r\n','2015-09-23 17:33:27'),
(37,'DIALDESK\r\n','DIALDESK\r\n','2015-09-23 17:34:17'),
(38,'CREDIT MANAGEMENT\r\n','CUSTOMER PROFILING\r\n','2015-09-23 17:34:21'),
(39,'BUSINESS SUPPORT SERVICES\r\n','SCANNING\r\n','2015-09-23 17:34:26');

/*Table structure for table `tbl_client_master` */

DROP TABLE IF EXISTS `tbl_client_master`;

CREATE TABLE `tbl_client_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(50) DEFAULT NULL,
  `createdate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_client_master` */

/*Table structure for table `tbl_invoice` */

DROP TABLE IF EXISTS `tbl_invoice`;

CREATE TABLE `tbl_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(50) DEFAULT NULL,
  `branch_name` varchar(50) DEFAULT NULL,
  `cost_center` varchar(50) DEFAULT NULL,
  `finance_year` varchar(50) DEFAULT NULL,
  `month` varchar(50) DEFAULT NULL,
  `invoiceDate` varchar(50) DEFAULT NULL,
  `app_tax_cal` varchar(50) DEFAULT NULL,
  `invoiceDescription` varchar(200) DEFAULT NULL,
  `jcc_no` varchar(200) DEFAULT NULL,
  `bill_no` varchar(200) NOT NULL,
  `po_no` varchar(200) DEFAULT NULL,
  `ser_tax_no` varchar(200) DEFAULT NULL,
  `ser_tax_category` varchar(200) DEFAULT NULL,
  `pan_no` varchar(200) DEFAULT NULL,
  `grn` varchar(200) DEFAULT NULL,
  `total` varchar(200) DEFAULT NULL,
  `tax` varchar(200) DEFAULT NULL,
  `grnd` varchar(200) DEFAULT NULL,
  `approve_po` varchar(50) NOT NULL,
  `approve_grn` varchar(50) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_invoice` */

insert  into `tbl_invoice`(`id`,`company_name`,`branch_name`,`cost_center`,`finance_year`,`month`,`invoiceDate`,`app_tax_cal`,`invoiceDescription`,`jcc_no`,`bill_no`,`po_no`,`ser_tax_no`,`ser_tax_category`,`pan_no`,`grn`,`total`,`tax`,`grnd`,`approve_po`,`approve_grn`,`username`,`status`,`createdate`) values 
(1,'Mas Callnet India Pvt Ltd','Delhi','BSS/OS/DE/1','2014-15','Jun','2015-10-19 15:54:07','1','ACTIVE FIELD RETENTION','','1/DE/2014-15','23456',NULL,NULL,NULL,'1223','1573','220.22','1793.22','Yes','Yes','branch',0,'2015-10-05 18:13:50'),
(2,'Mas Callnet India Pvt Ltd','Delhi','BSS/OS/DE/1','2014-15','Jun','2015-10-28 16:45:32','1','ACTIVE FIELD RETENTION','','1/DE/2014-15','',NULL,NULL,NULL,'','528','73.92','601.92','','','branch',0,'2015-10-07 11:59:05'),
(3,'Mas Callnet India Pvt Ltd','Delhi','BSS/OS/DE/1','1999','Nov','2015-10-06 17:19:40','1','ACTIVE FIELD RETENTION - Nov','','3/DE/1999','123',NULL,NULL,NULL,'','130','18.20','148.20','Yes','','branch',0,'2015-10-07 12:13:45');

/*Table structure for table `tbl_user` */

DROP TABLE IF EXISTS `tbl_user`;

CREATE TABLE `tbl_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `tbl_user` */

insert  into `tbl_user`(`id`,`username`,`password`,`role`,`email`,`branch_name`,`created`,`modified`) values 
(1,'admin','admin','admin',NULL,NULL,'2015-09-15 18:39:35','2015-09-15 18:39:50'),
(2,'branch','branch','branch',NULL,NULL,'2015-09-26 15:54:54',NULL),
(8,'wer','wer',NULL,'wer@gmail.com','wer','2015-10-06 11:13:48','2015-10-06 11:13:48'),
(9,'wer','wer',NULL,'wer@gmail.com','wer','2015-10-06 11:24:37','2015-10-06 11:24:37'),
(10,'wer','wer',NULL,'wer@gmail.com','wer','2015-10-06 11:25:21','2015-10-06 11:25:21');

/*Table structure for table `tmp_deduct_inv_particulars` */

DROP TABLE IF EXISTS `tmp_deduct_inv_particulars`;

CREATE TABLE `tmp_deduct_inv_particulars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cost_center_id` int(11) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `cost_center` varchar(100) DEFAULT NULL,
  `fin_year` varchar(100) DEFAULT NULL,
  `month_for` varchar(100) DEFAULT NULL,
  `particulars` varchar(200) DEFAULT NULL,
  `rate` varchar(200) DEFAULT NULL,
  `qty` varchar(200) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `initial_id` int(11) DEFAULT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tmp_deduct_inv_particulars` */

/*Table structure for table `tmp_inv_particulars` */

DROP TABLE IF EXISTS `tmp_inv_particulars`;

CREATE TABLE `tmp_inv_particulars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cost_center_id` int(11) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `cost_center` varchar(100) DEFAULT NULL,
  `fin_year` varchar(100) DEFAULT NULL,
  `month_for` varchar(100) DEFAULT NULL,
  `particulars` varchar(200) DEFAULT NULL,
  `rate` varchar(200) DEFAULT NULL,
  `qty` varchar(200) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `initial_id` int(11) DEFAULT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tmp_inv_particulars` */

/*Table structure for table `type_master` */

DROP TABLE IF EXISTS `type_master`;

CREATE TABLE `type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  `save_by` varchar(200) DEFAULT NULL,
  `codes` varchar(50) NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `type_master` */

insert  into `type_master`(`id`,`type`,`status`,`save_by`,`codes`,`createdate`) values 
(1,'Blended\r\n','1\r\n','Save by Admin on 31 Aug 2013 ; \r\n','BLD\r\n','2015-09-25 16:50:12'),
(2,'Contractual\r\n','1\r\n','Save by Admin on 31 Aug 2013 ; \r\n','OS\r\n','2015-09-25 16:50:14'),
(3,'Outbound\r\n','1\r\n','Save by Admin on 31 Aug 2013 ; \r\n','OB\r\n','2015-09-25 16:50:16'),
(4,'Field\r\n','1\r\n','Save by Admin on 31 Aug 2013 ; \r\n','FLD\r\n','2015-09-25 16:50:18'),
(5,'BACK OFFICE\r\n','1\r\n','Save by Admin on 31 Aug 2013 ; \r\n','BO\r\n','2015-09-25 16:50:20'),
(6,'Inbound\r\n','1\r\n','Save by Admin on 31 Aug 2013 ; \r\n','IB\r\n','2015-09-25 16:50:22');

/*Table structure for table `user_type` */

DROP TABLE IF EXISTS `user_type`;

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(200) NOT NULL,
  `page_access` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `user_type` */

insert  into `user_type`(`id`,`user_type`,`page_access`) values 
(1,'admin','1,2,3,4,5,6,7,8,9,10,11,12,13,14,15'),
(2,'branch','4,6,7,9,12');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
