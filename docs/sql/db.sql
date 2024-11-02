CREATE TABLE IF NOT EXISTS `accounts` (
	`id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `activated` BOOLEAN DEFAULT FALSE,
  `activation_code` varchar(50) DEFAULT '',
  `password_reset_code` varchar(50) DEFAULT '',
  `new_email` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `login_codes` (
  `login_code` varchar(50) DEFAULT '',
  `last_login` datetime DEFAULT NULL,
  `accountID` int NOT NULL,
  PRIMARY KEY (`login_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;