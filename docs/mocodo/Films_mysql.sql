CREATE DATABASE IF NOT EXISTS `FILMS` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `FILMS`;

/*
CREATE TABLE `ACTOR` (
  `actor_name` VARCHAR(42),
  PRIMARY KEY (`actor_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

CREATE TABLE `PLAYS_IN` (
  `title` VARCHAR(42),
  `actor_name` VARCHAR(42),
  `role` VARCHAR(42),
  PRIMARY KEY (`title`, `actor_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
CREATE TABLE `USER` (
  `user_name` VARCHAR(42),
  PRIMARY KEY (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

CREATE TABLE `BELONGS_TO` (
  `title` VARCHAR(42),
  `genre_name` VARCHAR(42),
  PRIMARY KEY (`title`, `genre_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `FILM` (
  `title` VARCHAR(42),
  `duration` VARCHAR(42),
  `synopsis` VARCHAR(42),
  `release_date` VARCHAR(42),
  PRIMARY KEY (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `GENRE` (
  `genre_name` VARCHAR(42),
  `` VARCHAR(42),
  PRIMARY KEY (`genre_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `REVIEW` (
  `review_content` VARCHAR(42),
  `published_date` VARCHAR(42),
  `title` VARCHAR(42),
  `user_name` VARCHAR(42),
  PRIMARY KEY (`review_content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ALTER TABLE `PLAYS_IN` ADD FOREIGN KEY (`actor_name`) REFERENCES `ACTOR` (`actor_name`);
ALTER TABLE `PLAYS_IN` ADD FOREIGN KEY (`title`) REFERENCES `FILM` (`title`);
ALTER TABLE `BELONGS_TO` ADD FOREIGN KEY (`genre_name`) REFERENCES `GENRE` (`genre_name`);
ALTER TABLE `BELONGS_TO` ADD FOREIGN KEY (`title`) REFERENCES `FILM` (`title`);
-- ALTER TABLE `REVIEW` ADD FOREIGN KEY (`user_name`) REFERENCES `USER` (`user_name`);
ALTER TABLE `REVIEW` ADD FOREIGN KEY (`title`) REFERENCES `FILM` (`title`);