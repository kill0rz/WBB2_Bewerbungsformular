DROP TABLE IF EXISTS `bb1_bewerbungsformular_fieldtypes`;
CREATE TABLE `bb1_bewerbungsformular_fieldtypes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `typename` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `bb1_bewerbungsformular_fields`;
CREATE TABLE `bb1_bewerbungsformular_fields` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `page` int(11) NOT NULL,
  `fieldtype` int(11) NOT NULL,
  `fieldname` varchar(500) NOT NULL,
  `fieldcontent` varchar(65353) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `fieldtype` (`fieldtype`),
  CONSTRAINT `bb1_bewerbungsformular_fields_ibfk_2` FOREIGN KEY (`fieldtype`) REFERENCES `bb1_bewerbungsformular_fieldtypes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `bb1_bewerbungsformular_fieldtypes` (`ID`, `typename`) VALUES
(1, 'dropdown'),
(2, 'number'),
(3, 'text'),
(4, 'textarea'),
(5, 'checkbox'),
(6, 'email');

DROP TABLE IF EXISTS `bb1_bewerbungsformular_options`;
CREATE TABLE `bb1_bewerbungsformular_options` (
  `ID` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `isonline` tinyint(1) NOT NULL DEFAULT '1',
  `startpage_title` varchar(500) NOT NULL DEFAULT 'Bewerbungsformular',
  `startpage_left` varchar(500) NULL,
  `startpage_right` varchar(500) NULL
);
INSERT INTO `bb1_bewerbungsformular_options` (`isonline`, `startpage_title`, `startpage_left`, `startpage_right`)
VALUES ('1', 'Bewerbungsformular', '', '');