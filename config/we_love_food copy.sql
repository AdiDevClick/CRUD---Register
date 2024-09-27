-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

/*!40101 SET NAMES utf8mb4*/;

--
-- Base de données : `we_love_food`
--

--
-- Création de la DB we_love_food
--

CREATE DATABASE IF NOT EXISTS `we_love_food`;
USE `we_love_food`;

-- -------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `img_path` text NOT NULL,
  `img_name` text NOT NULL,
  `video_path` text NOT NULL,
  `video_name` text NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`image_id`),
  FOREIGN KEY (`recipe_id`) REFERENCES recipes(recipe_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
  `rating` int(11) NOT NULL,
  `review` int(11) NOT NULL DEFAULT 3,
  `ranking` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`),
  FOREIGN KEY (`recipe_id`) REFERENCES recipes(recipe_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pwdreset`
--

CREATE TABLE IF NOT EXISTS `pwdreset` (
  `pwdReset_id` int(11) NOT NULL AUTO_INCREMENT,
  `pwdReset_email` text NOT NULL,
  `pwdReset_selector` text NOT NULL,
  `pwdReset_token` longtext NOT NULL,
  `pwdReset_expires` text NOT NULL,
  PRIMARY KEY (`pwdReset_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `recipes`
--

CREATE TABLE IF NOT EXISTS `recipes` (
  `recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `recipe` text NOT NULL,
  `author` varchar(512) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `step_1` text NOT NULL,
  `step_2` text NOT NULL,
  `step_3` text NOT NULL,
  `step_4` text NOT NULL,
  `step_5` text NOT NULL,
  `step_6` text NOT NULL,
  `ingredient_1` varchar(128) NOT NULL,
  `ingredient_2` varchar(128) NOT NULL,
  `ingredient_3` varchar(128) NOT NULL,
  `ingredient_4` varchar(128) NOT NULL,
  `ingredient_5` varchar(128) NOT NULL,
  `ingredient_6` varchar(128) NOT NULL,
  `total_time` int(11) NOT NULL,
  `total_time_length` varchar(6) NOT NULL,
  `resting_time` int(11) NOT NULL,
  `resting_time_length` varchar(6) NOT NULL,
  `oven_time` int(11) NOT NULL,
  `oven_time_length` varchar(6) NOT NULL,
  `persons` int(11) NOT NULL,
  `custom_ingredients` json NOT NULL,
  PRIMARY KEY (`recipe_id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(64) NOT NULL,
  `email` varchar(512) NOT NULL,
  `password` varchar(512) NOT NULL,
  `age` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `recipe_id`, `comment`, `created_at`, `rating`, `review`, `ranking`) VALUES
(83, 351, 248, '<script> alert(\"You have a virus\"); </script>', '2023-11-02', 0, 3, 0),
(84, 351, 248, '<script> alert(\"You have a virus\"); </script>', '2023-11-02', 0, 3, 0);

-- COMMIT;
