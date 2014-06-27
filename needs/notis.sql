/*
SQLyog Ultimate v9.10 
MySQL - 5.6.17 : Database - trippytripper
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`trippytripper` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `trippytripper`;

/*Table structure for table `notis` */

DROP TABLE IF EXISTS `notis`;

CREATE TABLE `notis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `notis_type` enum('friend','trip','email') DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `notis_read` char(1) DEFAULT '0',
  `from_user_name` varchar(255) DEFAULT NULL,
  `from_avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `notis` */

insert  into `notis`(`id`,`from_user_id`,`to_user_id`,`message`,`notis_type`,`create_time`,`notis_read`,`from_user_name`,`from_avatar`) values (1,2,1,'sent you a message','email',NULL,'0','demo','images/2_Look at Megan Fox Beautiful Face_1.jpg'),(2,3,1,'sent you a friend request','friend',NULL,'0','sonphuong','gravatar'),(3,4,1,'comment on Hanoi- Thai nguyen','trip',NULL,'0',NULL,'images/4_101_a-beautiful_face-388803.jpg');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
