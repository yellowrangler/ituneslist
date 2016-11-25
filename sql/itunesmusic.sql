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

http://manchester/ituneslist/app/ajax/getitunescsv.php?location=Orleans&filename=TarryMusicSongs11252016.csv&truncate=yes
http://manchester/ituneslist/app/ajax/getitunescsv.php?location=Camden&filename=TammyMusicSongs11252016.csv

SELECT * 
FROM musictbl 
WHERE location = "Camden" 
AND song NOT in (
    SELECT song 
    FROM musictbl 
    WHERE location = "Orleans"
)
ORDER BY album, track ASC