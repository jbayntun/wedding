-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2015 at 05:54 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wedding`
--

-- --------------------------------------------------------
DROP TABLE IF EXISTS `location`;
DROP TABLE IF EXISTS `contribution`;
DROP TABLE IF EXISTS `gift`;
DROP TABLE IF EXISTS `guest`;
DROP TABLE IF EXISTS `group`;
DROP TABLE IF EXISTS `response`;


--
-- Table structure for table `response`
--
CREATE TABLE IF NOT EXISTS `response`
(
  `id`              int(1)          NOT NULL,
  `description`     varchar(128)    NOT NULL,

  PRIMARY KEY (`id`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `group`
--
CREATE TABLE IF NOT EXISTS `group`
(
  `id`              int(4)          NOT NULL AUTO_INCREMENT,
  `name`            varchar(64)     NOT NULL,
  `username`        varchar(64)     NOT NULL,
  `password`        varchar(256)    NOT NULL,
  `value`           varchar(32)     ,
  `notes`           text            NULL,

  PRIMARY KEY (`id`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `guest`
--
CREATE TABLE IF NOT EXISTS `guest`
(
  `id`              int(4)          NOT NULL AUTO_INCREMENT,
  `group_id`        int(4)          NOT NULL,
  `response_id`     int(1)          NOT NULL,
  `first_name`      varchar(64)     NOT NULL,
  `last_name`       varchar(64)     NOT NULL,
  `email`           varchar(128)    NULL,
  `phone`           varchar(12)     NULL,

  PRIMARY KEY (`id`),
  FOREIGN KEY (`group_id`)    REFERENCES `group`    (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`response_id`) REFERENCES `response` (`id`) ON DELETE RESTRICT
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `gift`
--
CREATE TABLE IF NOT EXISTS `gift`
(
  `id`              int(4)          NOT NULL AUTO_INCREMENT,
  `title`           varchar(64)     NOT NULL,
  `description`     text            NOT NULL,
  `cost`            numeric(15,2)   NOT NULL,
  `picture`         varchar(512)    NULL,
  `fulfilled`       bool            NOT NULL DEFAULT FALSE,

  PRIMARY KEY (`id`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `contribution`
--
CREATE TABLE IF NOT EXISTS `contribution`
(
  `group_id`        int(4)          NOT NULL,
  `gift_id`         int(4)          NOT NULL,
  `quantity`        int(2)          NOT NULL DEFAULT 1,

  PRIMARY KEY (`group_id`, `gift_id`),
  FOREIGN KEY (`group_id`)  REFERENCES `group` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`gift_id`)   REFERENCES `gift`  (`id`) ON DELETE CASCADE
  
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `location`
--
CREATE TABLE IF NOT EXISTS `location`
(
  `id`              int(4)          NOT NULL AUTO_INCREMENT,
  `event_title`     varchar(64)     NOT NULL,
  `description`     varchar(1024)   NOT NULL,
  `start_time`      varchar(64)     NOT NULL,
  `notes`           varchar(1024)   NULL,
  `address`         varchar(256)    NOT NULL,
  `html`            text            NOT NULL,

  PRIMARY KEY (`id`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Sample data
--

-- --------------------------------------------------------

INSERT INTO `response`
    (`id`, `description`)
VALUES
    (0, 'Yes'),
    (1, 'No'),
    (2, 'Unknown');

INSERT INTO `group`
    (`name`, `username`, `password`)
VALUES
    ('admin', 'Admin!', '');

INSERT INTO `guest`
    (`group_id`, `response_id`, `first_name`, `last_name`)
VALUES
    (1, 1, 'Admin', 'admin');
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;