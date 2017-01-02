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
  `fieldcontent` varchar(500) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fieldtype` (`fieldtype`),
  CONSTRAINT `bb1_bewerbungsformular_fields_ibfk_2` FOREIGN KEY (`fieldtype`) REFERENCES `bb1_bewerbungsformular_fieldtypes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `bb1_bewerbungsformular_fieldtypes` (`ID`, `typename`) VALUES
(1, 'dropdown'),
(2, 'number'),
(3, 'text'),
(4, 'textarea'),
(5, 'checkbox');