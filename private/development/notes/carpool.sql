create database carpool;
grant all on carpool.* to 'carpool'@'localhost' IDENTIFIED BY 'c4rp001';
flush privileges;

DROP TABLE IF EXISTS `carpool`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `carpool` (
  `roswell_id` int(11) NOT NULL,
  `department` varchar(128) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `zip` varchar(5) NOT NULL,
  `start_hr` varchar(2) NOT NULL,
  `start_min` varchar(2) NOT NULL,
  `start_ampm` varchar(2) NOT NULL,
  `end_hr` varchar(2) NOT NULL,
  `end_min` varchar(2) NOT NULL,
  `end_ampm` varchar(2) NOT NULL,
  PRIMARY KEY (`roswell_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;
