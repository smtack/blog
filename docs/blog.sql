-- CREATE BLOG DATABASE

CREATE DATABASE `blog`;

-- USE DATABASE

USE `blog`;

-- CREATE USERS TABLE

CREATE TABLE `users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(50) NOT NULL,
  `user_username` VARCHAR(25) NOT NULL,
  `user_email` VARCHAR(250) NOT NULL,
  `user_password` VARCHAR(250) NOT NULL,
  `user_profile_picture` VARCHAR(255),
  `user_joined` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id)
) ENGINE=INNODB;

-- CREATE FOLLOWS TABLE

CREATE TABLE `follows` (
  `follow_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `followed_id` INT(11) NOT NULL,
  PRIMARY KEY (follow_id)
) ENGINE=INNODB;

-- CREATE POSTS TABLE

CREATE TABLE `posts` (
  `post_id` INT(11) NOT NULL AUTO_INCREMENT,
  `post_title` VARCHAR(255) NOT NULL,
  `post_slug` VARCHAR(300) NOT NULL,
  `post_text` VARCHAR(10000) NOT NULL,
  `post_image` VARCHAR(255),
  `post_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `post_by` INT(11) NOT NULL,
  PRIMARY KEY (post_id)
) ENGINE=INNODB;

-- CREATE BOOKMARKS TABLE

CREATE TABLE `bookmarks` (
  `bookmark_id` INT(11) NOT NULL AUTO_INCREMENT,
  `bookmarked_by` INT(11) NOT NULL,
  `bookmarked_post` INT(11) NOT NULL,
  PRIMARY KEY (bookmark_id)
) ENGINE=INNODB;

-- CREATE COMMENTS TABLE

CREATE TABLE `comments` (
  `comment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `comment_text` VARCHAR(250) NOT NULL,
  `comment_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `comment_post` INT(11) NOT NULL,
  `comment_by` INT(11) NOT NULL,
  PRIMARY KEY (comment_id)
) ENGINE=INNODB;