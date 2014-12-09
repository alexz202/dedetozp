ALTER TABLE  `tp_member` ADD  `belong` SMALLINT( 4 ) NOT NULL DEFAULT  '0' AFTER  `loginip`
CREATE TABLE IF NOT EXISTS `tp_member_belong` (
  `id` int(8) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `type` smallint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
