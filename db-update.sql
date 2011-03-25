CREATE TABLE `bpmproject`.`mapping` (
	`prefix` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	`mapping1` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	`mapping2` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	`hash1` CHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	`hash2` CHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	PRIMARY KEY ( `prefix`(200) , `hash1` , `hash2` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;