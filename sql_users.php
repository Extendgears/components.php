<?php 

define( 'SQL_USERS', '
CREATE TABLE `'.DB_PREFIX.DB_USERS.'` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(30) COLLATE utf32_german2_ci NOT NULL,
  `password` varchar(50) COLLATE utf32_german2_ci NOT NULL,
  `email` varchar(50) COLLATE utf32_german2_ci DEFAULT NULL,
  `keepLog` varchar(40) COLLATE utf32_german2_ci NOT NULL,
  `salt` varchar(15) COLLATE utf32_german2_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf32 COLLATE=utf32_german2_ci;');

?>
