USE `we_love_food`;
ALTER TABLE `recipes` ADD IF NOT EXISTS `step_1` text NOT NULL;
ALTER TABLE `recipes` CHANGE COLUMN `recipe` description VARCHAR(255);
ALTER TABLE `recipes` ADD IF NOT EXISTS `description` text NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `step_2` text NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `step_3` text NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `step_4` text NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `step_5` text NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `step_6` text NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `ingredient_1` varchar(128) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `ingredient_2` varchar(128) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `ingredient_3` varchar(128) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `ingredient_4` varchar(128) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `ingredient_5` varchar(128) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `ingredient_6` varchar(128) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `total_time` int(11) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `total_time_length` varchar(6) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `resting_time` int(11) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `resting_time_length` varchar(6) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `oven_time` int(11) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `oven_time_length` varchar(6) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `persons` int(11) NOT NULL;
ALTER TABLE `recipes` ADD IF NOT EXISTS `custom_ingredients` json NOT NULL;
-- ALTER TABLE `comments` FOREIGN KEY (`recipe_id`) REFERENCES `recipes`(`recipe_id`) ON DELETE CASCADE;
ALTER TABLE `comments` ADD CONSTRAINT `fk_recipe_id` FOREIGN KEY (`recipe_id`) REFERENCES `recipes`(`recipe_id`) ON DELETE CASCADE;
-- ALTER TABLE recipes  ADD IF NOT EXISTS FULLTEXT INDEX full_text_idx (title, author);
ALTER TABLE recipes  ADD FULLTEXT INDEX full_text_title_idx (title);
ALTER TABLE images  ADD IF NOT EXISTS `video_name` text NOT NULL;
ALTER TABLE images  ADD IF NOT EXISTS `video_path` text NOT NULL;
ALTER TABLE images  ADD IF NOT EXISTS `youtubeID` text NOT NULL;

-- Récupérer toutes les entrées qui n'ont aucun match avec la recipe_id
SELECT recipe_id FROM comments WHERE recipe_id NOT IN (SELECT recipe_id FROM recipes);
-- S'assurer que la table est de type innoDB
ALTER TABLE comments ENGINE=InnoDB;
