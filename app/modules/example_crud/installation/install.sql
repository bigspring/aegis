CREATE TABLE `ae_examples` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `another_field` varchar(255) NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `creator_id` int(11) unsigned NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modifier_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;