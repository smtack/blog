-- CREATE BLOG DATABASE

CREATE DATABASE `blog`;

-- USE DATABASE

USE `blog`;

-- CREATE USERS TABLE

CREATE TABLE `users` (
    `user_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_name` VARCHAR(50) NOT NULL,
    `user_username` VARCHAR(25) NOT NULL,
    `user_email` VARCHAR(250) NOT NULL,
    `user_password` VARCHAR(250) NOT NULL,
    `user_profile_picture` VARCHAR(255),
    `user_joined` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- CREATE FOLLOWS TABLE

CREATE TABLE `follows` (
    `follow_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `followed_id` INT NOT NULL
) ENGINE=INNODB;

-- CREATE POSTS TABLE

CREATE TABLE `posts` (
    `post_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `post_title` VARCHAR(255) NOT NULL,
    `post_slug` VARCHAR(300) NOT NULL,
    `post_text` VARCHAR(10000) NOT NULL,
    `post_image` VARCHAR(255),
    `post_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `post_by` INT NOT NULL
) ENGINE=INNODB;

-- CREATE BOOKMARKS TABLE

CREATE TABLE `bookmarks` (
    `bookmark_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `bookmarked_by` INT NOT NULL,
    `bookmarked_post` INT NOT NULL
) ENGINE=INNODB;

-- CREATE COMMENTS TABLE

CREATE TABLE `comments` (
    `comment_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `comment_text` VARCHAR(250) NOT NULL,
    `comment_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `comment_post` INT NOT NULL,
    `comment_by` INT NOT NULL
) ENGINE=INNODB;