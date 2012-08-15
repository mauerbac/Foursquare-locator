CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `fname` varchar(60) default NULL,
  `lname` varchar(60) default NULL,
  `token` varchar(60) default NULL,
  `twitter` varchar(60) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;