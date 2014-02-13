<?php

'CREATE TABLE IF NOT EXISTS `encription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `encrypt` varchar(255) NOT NULL,
  `decrypt` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
)';

?>