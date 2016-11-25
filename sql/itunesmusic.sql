-- USE itunesmusic;
-- DROP TABLE librarytbl;
-- CREATE TABLE librarytbl (
--   id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
--   folder varchar(255) NULL,
--   name varchar(5) NULL,
--   xmldate varchar(255) NULL,
--   importdate datetime,
--   PRIMARY KEY (id)
-- ); 

USE itunesmusic;
DROP TABLE musictbl;
CREATE TABLE musictbl (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  artist varchar(255) NULL,
  album varchar(255) NULL,
  song varchar(255) NULL,
  track INT NULL,
  tracks INT NULL,
  totaltime varchar(255) NULL,
  genre varchar(255) NULL,
  filelocation varchar(255) NULL,
  location varchar(255) NULL,
  importdate datetime,
  PRIMARY KEY (id)
); 