CREATE TABLE IF NOT EXISTS `creatures` (
  `creature_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,  
  `health` int(10) NOT NULL,
  `creature_type` enum('vampire','zombie','ninja','pirate') NOT NULL,
  PRIMARY KEY (`creature_id`)  
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

# some test data follow:
/*
INSERT INTO `creatures` (`creature_id`, `name`, `health`, `creature_type`) VALUES
('Blade', 10000, 'vampire'),
('Selene (Underworld)', 10000, 'vampire'),
('random zombie 1', 5000, 'zombie'),
('random zombie 2', 5000, 'zombie'),
('Leonardo (TMNT)', 2500, 'ninja'),
('Raphael (TMNT)', 2500, 'ninja'),
('Michelangelo (TMNT)', 2500, 'ninja'),
('Donatello (TMNT)', 2500, 'ninja'),
('Barbarossa', 2500, 'pirate'),
('Blackbeard', 2500, 'pirate');
*/

