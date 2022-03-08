CREATE DATABASE IF NOT EXISTS `avgTwo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `avgTwo`;

CREATE TABLE `categories` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(50) NOT NULL,
	`description` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `media_categories` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`category_id` int(11) NOT NULL,
	`media_id` int(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `media_categories` ADD UNIQUE KEY `category_id` (`category_id`,`media_id`);

CREATE TABLE `media` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL,
	`description` text NOT NULL,
	`filepath` varchar(255) NOT NULL,
	`uploaded_date` datetime NOT NULL DEFAULT current_timestamp(),
	`type` varchar(10) NOT NULL,
	`thumbnail` varchar(255) NOT NULL DEFAULT '',
	`approved` tinyint(1) NOT NULL DEFAULT 1,
	`likes` int(11) NOT NULL DEFAULT 0,
	`dislikes` int(11) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `media` (`id`, `title`, `description`, `filepath`, `uploaded_date`, `type`, `thumbnail`, `approved`, `likes`, `dislikes`) VALUES
(1, 'Abandoned Building', '', 'media/images/abandoned-building.jpg', '2019-07-16 20:09:26', 'image', '', 1, 0, 0),
(2, 'Road', 'Going down the only road I\'ve even known.', 'media/images/road.jpg', '2019-07-16 20:12:00', 'image', '', 1, 0, 0),
(3, 'Stars', 'A wonderful view of the night sky.', 'media/images/stars.jpg', '2019-07-16 20:12:39', 'image', '', 1, 0, 0),
(4, 'Sample Video', 'This is just a sample video.', 'media/videos/sample.mp4', '2020-02-12 17:06:04', 'video', '', 1, 0, 0),
(5, 'Sample Audio', 'This is just a sample audio.', 'media/audios/sample.mp3', '2020-02-12 17:44:00', 'audio', '', 1, 0, 0),
(6, 'Beach', 'Awesome day at the beach!', 'media/images/beach.jpg', '2020-02-12 18:03:44', 'image', '', 1, 0, 0);