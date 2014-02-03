-- Create syntax for TABLE 'ae_user_groups'
CREATE TABLE `ae_user_groups` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Insert default data for TABLE 'ae_user_groups'
INSERT INTO `ae_user_groups` (`id`, `title`, `description`)
VALUES
	(1, 'Administrator', 'Administrative users that have access to the back end'),
	(100, 'User', 'General front end users with no access to the back end');

-- Create syntax for TABLE 'ae_user_logins'
CREATE TABLE `ae_user_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `login_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'ae_users'
CREATE TABLE `ae_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL DEFAULT '',
  `lastname` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL,
  `user_group_id` int(11) NOT NULL DEFAULT '100',
  `token` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Insert default data for TABLE 'ae_users'
INSERT INTO `ae_users` (`id`, `email`, `firstname`, `lastname`, `password`, `user_group_id`, `token`, `identifier`, `enabled`, `created`, `createdby`, `modified`, `modifiedby`)
VALUES (1,'aegis@bigspring.co.uk','change','me','32910af88d6192244308dd74451ad7e3a002ea2a5ad2b7abb10fc22c4200b503',1,'','',1,'0000-00-00 00:00:00',1,'0000-00-00 00:00:00',1);