-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 28 avr. 2024 à 07:53
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `we_love_food`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `rating` int(11) NOT NULL,
  `review` int(11) NOT NULL DEFAULT 3,
  `ranking` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `recipe_id`, `comment`, `created_at`, `rating`, `review`, `ranking`) VALUES
(1, 1, 2, 'test', '2023-10-21', 0, 0, 0),
(2, 351, 2, 'Mon commentaire', '2023-10-21', 0, 0, 0),
(3, 0, 0, '', '2023-10-21', 0, 0, 0),
(4, 0, 0, '', '2023-10-21', 0, 0, 0),
(5, 0, 0, '', '2023-10-21', 0, 0, 0),
(6, 0, 0, 'test', '2023-10-21', 0, 0, 0),
(7, 1, 0, 'test', '2023-10-21', 0, 0, 0),
(8, 1, 0, 'test', '2023-10-21', 0, 0, 0),
(9, 5, 0, 'test', '2023-10-21', 0, 0, 0),
(10, 0, 0, 'test', '2023-10-21', 0, 0, 0),
(11, 0, 0, 'test', '2023-10-21', 0, 0, 0),
(12, 0, 0, 'test', '2023-10-21', 0, 0, 0),
(13, 0, 0, 'test2', '2023-10-21', 0, 0, 0),
(14, 0, 0, 'test2', '2023-10-21', 0, 0, 0),
(15, 0, 0, 'test2', '2023-10-21', 0, 0, 0),
(16, 1, 0, 'test', '2023-10-21', 0, 0, 0),
(17, 1, 1, 'test', '2023-10-21', 0, 0, 0),
(18, 1, 102, 'Mon commentaire', '2023-10-21', 0, 0, 0),
(19, 1, 2, 'TEST', '2023-10-21', 0, 0, 0),
(20, 1, 0, 'test', '2023-10-21', 0, 0, 0),
(21, 1, 0, 'test', '2023-10-21', 0, 0, 0),
(22, 1, 0, 'dsq', '2023-10-21', 0, 0, 0),
(23, 1, 0, 'test', '2023-10-21', 0, 0, 0),
(24, 1, 1, 'ttesti', '2023-10-21', 0, 0, 0),
(25, 1, 0, 'test', '2023-10-21', 0, 0, 0),
(26, 1, 4, 'test', '2023-10-21', 0, 0, 0),
(27, 93, 0, 'dsq', '2023-10-21', 0, 0, 0),
(28, 93, 0, 'dsq', '2023-10-21', 0, 0, 0),
(29, 101, 0, 'dsq', '2023-10-22', 0, 0, 0),
(30, 101, 0, 'rzqsqdqs', '2023-10-22', 0, 0, 0),
(31, 0, 2, 'test', '2023-11-01', 0, 3, 0),
(32, 0, 2, 'Ceci est un test', '2023-11-01', 0, 3, 0),
(33, 0, 2, 'testtt', '2023-11-01', 0, 3, 0),
(34, 0, 2, 'testtt2', '2023-11-01', 0, 3, 0),
(35, 0, 2, 'dsqdsq', '2023-11-01', 0, 3, 0),
(36, 351, 2, 'test user ', '2023-11-01', 0, 3, 0),
(37, 357, 2, 'test adibouh', '2023-11-01', 0, 3, 0),
(38, 357, 2, 'test d\'Adi', '2023-11-01', 0, 3, 0),
(39, 351, 2, 'yrdy', '2023-11-01', 0, 3, 0),
(40, 351, 2, 'Je teste un commentaire', '2023-11-01', 0, 3, 0),
(41, 351, 2, 'Un nouveau test avec un autre compte', '2023-11-01', 0, 3, 0),
(42, 357, 2, 'Test 2e', '2023-11-01', 0, 3, 0),
(43, 357, 0, 'Quelle bonne pizza !', '2023-11-01', 0, 3, 0),
(44, 357, 0, '\r\nQuelle nonne pizza !', '2023-11-01', 0, 3, 0),
(45, 357, 4, 'test', '2023-11-01', 0, 3, 0),
(46, 357, 0, 'test', '2023-11-01', 0, 3, 0),
(47, 357, 0, 'test', '2023-11-01', 0, 3, 0),
(48, 357, 0, 'test', '2023-11-01', 0, 3, 0),
(49, 357, 0, '', '2023-11-01', 0, 3, 0),
(50, 357, 0, 'c', '2023-11-01', 0, 3, 0),
(75, 351, 0, 'test 3', '2023-11-01', 0, 3, 0),
(52, 351, 0, 'dsq', '2023-11-01', 0, 3, 0),
(53, 351, 0, 'dsq', '2023-11-01', 0, 3, 0),
(54, 351, 0, 'dsq', '2023-11-01', 0, 3, 0),
(55, 351, 0, 'dsq', '2023-11-01', 0, 3, 0),
(56, 351, 0, 'dsq', '2023-11-01', 0, 3, 0),
(57, 351, 2, 'dsq', '2023-11-01', 0, 3, 0),
(58, 351, 4, 'dsq', '2023-11-01', 0, 3, 0),
(59, 351, 102, 'dqs', '2023-11-01', 0, 3, 0),
(60, 351, 0, 'dsq', '2023-11-01', 0, 3, 0),
(61, 351, 0, 'dqs', '2023-11-01', 0, 3, 0),
(62, 351, 0, 'dqs', '2023-11-01', 0, 3, 0),
(63, 351, 0, 'dqsdqs', '2023-11-01', 0, 3, 0),
(64, 351, 0, 'dsq', '2023-11-01', 0, 3, 0),
(65, 351, 0, 'test', '2023-11-01', 0, 3, 0),
(66, 351, 0, 'test', '2023-11-01', 0, 3, 0),
(67, 351, 0, 'test', '2023-11-01', 0, 3, 0),
(68, 351, 2, 'test', '2023-11-01', 0, 3, 0),
(69, 351, 2, '', '2023-11-01', 0, 3, 0),
(70, 351, 0, '', '2023-11-01', 0, 3, 0),
(71, 351, 0, 'dsq', '2023-11-01', 0, 3, 0),
(72, 351, 0, '', '2023-11-01', 0, 3, 0),
(74, 351, 0, 'test', '2023-11-01', 0, 3, 0),
(76, 351, 0, '', '2023-11-01', 0, 3, 0),
(77, 351, 0, 'dsq', '2023-11-01', 0, 3, 0),
(78, 351, 0, 'dsq', '2023-11-01', 0, 3, 0),
(79, 351, 0, '1123123', '2023-11-01', 0, 3, 0),
(80, 351, 248, 'test', '2023-11-01', 0, 3, 0),
(81, 351, 2, '', '2023-11-01', 0, 3, 0),
(82, 351, 244, 'Un commentaire', '2023-11-01', 0, 3, 0),
(83, 351, 248, '<script> alert(\"You have a virus\"); </script>', '2023-11-02', 0, 3, 0),
(84, 351, 248, '<script> alert(\"You have a virus\"); </script>', '2023-11-02', 0, 3, 0),
(85, 351, 248, '<', '2023-11-02', 0, 3, 0),
(86, 351, 248, 'test', '2023-11-02', 0, 3, 0),
(87, 0, 0, '', '2023-11-02', 0, 3, 0),
(88, 0, 0, '', '2023-11-02', 0, 3, 0),
(89, 0, 0, 'test 2', '2023-11-02', 0, 3, 0),
(90, 0, 0, 'test 2', '2023-11-02', 0, 3, 0),
(91, 0, 0, 'test 2', '2023-11-02', 0, 3, 0),
(92, 0, 0, 'test 2te', '2023-11-02', 0, 3, 0),
(93, 0, 248, 'test2', '2023-11-02', 0, 3, 0),
(94, 351, 248, 'test 3', '2023-11-02', 0, 3, 0),
(95, 357, 248, 'test 4', '2023-11-02', 0, 3, 0),
(96, 357, 248, '&lt;', '2023-11-02', 0, 3, 0),
(97, 357, 248, 'test', '2023-11-02', 0, 3, 0),
(98, 357, 248, 'test 12', '2023-11-02', 0, 3, 0),
(99, 357, 247, 'Test de commentaire', '2023-11-02', 0, 3, 0),
(100, 357, 247, 'Test de commentaire', '2023-11-02', 0, 3, 0),
(101, 357, 247, 'test', '2023-11-02', 0, 3, 0),
(102, 357, 247, 'test 2', '2023-11-02', 0, 3, 0),
(103, 357, 249, 'Que c&#039;est bon !', '2023-11-02', 0, 3, 0),
(104, 351, 248, 'test', '2023-11-02', 0, 3, 0),
(105, 351, 248, 'test 2', '2023-11-02', 0, 3, 0),
(106, 351, 248, 'test', '2023-11-03', 0, 3, 0),
(107, 351, 242, 'test', '2023-11-03', 0, 3, 0),
(108, 351, 247, 'test', '2024-04-10', 0, 3, 0),
(109, 351, 247, 'test de commentaire', '2024-04-10', 0, 3, 0);

-- --------------------------------------------------------

--
-- Structure de la table `commentss`
--

CREATE TABLE `commentss` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `review` int(11) NOT NULL DEFAULT 3
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pwdreset`
--

CREATE TABLE `pwdreset` (
  `pwdReset_id` int(11) NOT NULL,
  `pwdReset_email` text NOT NULL,
  `pwdReset_selector` text NOT NULL,
  `pwdReset_token` longtext NOT NULL,
  `pwdReset_expires` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pwdreset`
--

INSERT INTO `pwdreset` (`pwdReset_id`, `pwdReset_email`, `pwdReset_selector`, `pwdReset_token`, `pwdReset_expires`) VALUES
(1, '', 'fbb548504b55b300', '$2y$10$q4499oI.dx3Q7KQXbFDwWenthg7mPfq31FJt45dwFAzK77u0U304q', '1711973423'),
(9, 'ptitbarba@hotmail.com', 'a035aa986e402603', '$2y$10$ND4FFsJ5uyuRHhKhZJ1JZuI97IIArcuEzyRWtqZylb1x7AMvaAqZe', '1713695216'),
(10, 'dsqdq', 'de292f59a7b4636f', '$2y$10$C8ybj9hRCM9/EcGEdy46meBdUPZpg/iUCxrVsGwx/3M0shNShpCau', '1713724171'),
(12, 'dqsd', 'a359cae042ab6c2d', '$2y$10$nDC6sGPnAsLVTWmFHzXz0e2T3qvXHRVuBJFvjBwclQYsAyDga3PSi', '1713727523'),
(24, 'ds', '0bef2a41db7afec5', '$2y$10$1zB4rI/pD3/WRqjdl5meyeKEczWrZKEMgIGuaGAAiowZ.Sc6aIZgG', '1713728475'),
(26, 'sqd', 'ed96e741bcc8e84d', '$2y$10$WE/nxqZo35l74Bbt3ZCdgOXY/JYEps0WMbbF1pDYaFhx75VOIhKQC', '1713728726'),
(31, 'dqsdq', '117c6cb15895be68', '$2y$10$cDgkNpSS3bc..NsJ4kTgRuBOXF7.rinriCg8uZ9rwO2Hv/9/5hsEW', '1713729138'),
(36, ' sq', '8883410cefc25cea', '$2y$10$GjQ5YSfBjMGENpg76S/58eyZdKFoUvvvZymV4gSHKF/deVnxWZrBq', '1713780678'),
(40, 'dq', '53afff8ac5dcc177', '$2y$10$kK.GiEio4.Kp7hketBQgQe9I32ebsKIZLcPqyA8oVT/HNYe5zfO/K', '1713795826'),
(42, 'dsq', 'f2c69cda0a7d922a', '$2y$10$6YPd9kuqvv8g3ZDstqSc4esXquhkLwk7/FlfSE9XmUDwwo878umWi', '1713797471'),
(43, 'dqs', 'c6970fc240530f82', '$2y$10$k6Q/n/vtLyVvWMqG60qbhORzcoC.KNoNSCIHbKEQqHmxL/RKrX0Au', '1713798200'),
(45, 'd', '6609a7ca9a72df76', '$2y$10$HLkcly2GKIrDOOLN0NihJedE7W2wdueECC0Wk49yrvCGtDXnYYhYq', '1713798441'),
(48, 'test@test.com', '0b2024c253a053cd', '$2y$10$mzj1xgF5VtO7SHU9JT6siet8BKAb34bhsfaTn84T93O3alQjdcdU2', '1713799408'),
(55, 'dsq@d.d', '9f409ac51687d044', '$2y$10$LSC43ALoQ0le1M2Ht81UfONzntLyVlw5lByFEvYBl3X1bJalZG5ja', '1713800211'),
(56, 'dsq@d.c', '64a1dfc7f329d973', '$2y$10$PMUtkNSdVucwxUnO4muMn.fFU1phkCevPl/0Mej42RWGzmP/nfQ4e', '1713801393'),
(57, 'dqs@d.dom', '860cd67699795628', '$2y$10$/DZqxAwegGzM1h8cbLJBzO3Gs7zILkrQ7juGHiUwYr1IQvd1SRB5m', '1713801462'),
(58, 'dsq@dsq.c', 'f1075b4aed578357', '$2y$10$5oEhon27rtiDauQDY0lOf.Tr1pkrGPeWz/LWmMGFpYREdm07rPqjm', '1713802778'),
(59, 'dsq@dsq.copù', '38ec0da91b8b5393', '$2y$10$ois65EYlS8WDnI8ESTNe4eeMP6XyVDS4Njk/j6EalkKBFAr/TaOOS', '1713805184'),
(60, 'dsq@fg.cm', 'e5d2c43e467864af', '$2y$10$SOpMwL.nWwscuUeTF3RsEOEC4ILLN772l1Q5TcX.AiCLgJF6LUDS.', '1713805278'),
(61, 'd@d.com', 'f2f1b09102a482c0', '$2y$10$qRHXoPNG9nUOYCvLbX2LNesA8sOgUVXcvOEkevFSUml2g2AGV9bZi', '1713805437'),
(62, 'dsq@dsq.com', '171627a792176703', '$2y$10$5uMa8ui.u4GP6jxc6OQ2FOJrETgPbj2FPc93OVQbrqWVMGDzptbnW', '1713805693'),
(63, 'dsq@dqs.c', 'b2e511794ba9f876', '$2y$10$znsgKL9ca//kPPeb1LoH5ORxU8c5BsaxBOXvA3WxF1MnjDGl3vWcW', '1713806383'),
(67, 'd@d.d', '6f05ffc6f4fa0d77', '$2y$10$yXDi74EklNvwxzDbGqG5D.TBltTF0Shia7M7Da9zXdfiYdtaORNDa', '1713911776'),
(68, 'd@d.c', '1316da38e4910c93', '$2y$10$uq/zA5imTFaau5BDQAlZOOUXSdX1t8iWOOzr6OcumOKt5fC3n9bDi', '1713961055');

-- --------------------------------------------------------

--
-- Structure de la table `recipes`
--

CREATE TABLE `recipes` (
  `recipe_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `recipe` text NOT NULL,
  `author` varchar(512) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `step_1` text NOT NULL,
  `step_2` text NOT NULL,
  `step_3` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `recipes`
--

INSERT INTO `recipes` (`recipe_id`, `title`, `recipe`, `author`, `is_enabled`, `created_at`, `step_1`, `step_2`, `step_3`) VALUES
(2, 'Couscous', 'Etape 1 : de la semoule', 'mickael.andrieu@exemple.com', 0, '2023-10-20', '', '', NULL),
(3, 'Salade Romaine', 'Etape 1 : des flageolets, Etape 2 : ...0', 'mathieu.nebra@exemple.com', 1, '2023-10-20', '', '', NULL),
(4, 'Escalope Milanaise', 'Etape 1 : prenez une belle escalope', 'laurene.castor@exemple.com', 0, '2023-10-20', '', '', NULL),
(102, 'Sandwich Toulousain', '   Pour cette recette, il va falloir un pain de campagne...', 'Adrien Quijo', 1, '2023-10-20', '', '', NULL),
(101, 'Sandwich Toulousain', ' Pour cette recette, il va falloir un pain de campagne...', 'Adrien Quijo', 1, '2023-10-20', '', '', NULL),
(73, 'Pizza', 'Ask Rassarin', 'Adrien Quijo', 1, '2023-10-20', '', '', NULL),
(79, 'Sandwich Toulousain', 'Pour cette recette, il va falloir un pain de campagne...', 'Adrien Quijo', 1, '2023-10-20', '', '', NULL),
(113, 'Ma recette éditée avec l\'ID', 'ma super recette éditée avec l\'iD !', 'Adrien Quijo', 1, '2023-10-20', '', '', NULL),
(114, 'Ma nouvelle recette éditée', 'Voici ma nouvelle recette éditée avec amour !\r\nDu délice !', 'Adrien Quijo', 1, '2023-10-20', '', '', NULL),
(127, 'test', 'test', 'Adrien Quijo', 1, '2023-10-21', '', '', NULL),
(128, 'tesss', 'ttest', 'Adrien Quijo', 1, '2023-10-21', '', '', NULL),
(129, 'este', 'testtt', 'adrienquijo@gmail.com', 1, '2023-10-21', '', '', NULL),
(130, 'Recette spéciale', 'Voyons voir ça', 'adrienquijo@gmail.com', 1, '2023-10-21', '', '', NULL),
(161, 'test', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit magni deleniti laboriosam doloremque animi consequatur ipsam nostrum, accusamus amet quidem, aspernatur iure. Alias ut atque et dicta doloribus. Natus, facilis!\r\ntest', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(160, 'test 2', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit magni deleniti laboriosam doloremque animi consequatur ipsam nostrum, accusamus amet quidem, aspernatur iure. Alias ut atque et dicta doloribus. Natus, facilis!', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(159, 'test', 'test', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(162, 'test', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit magni deleniti laboriosam doloremque animi consequatur ipsam nostrum, accusamus amet quidem, aspernatur iure. Alias ut atque et dicta doloribus. Natus, facilis?', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(229, 'test', 'test', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(230, 'test', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit magni deleniti laboriosam doloremque animi consequatur ipsam nostrum, accusamus amet quidem, aspernatur iure. Alias ut atque et dicta doloribus. Natus, facilis?', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(231, 'test', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit magni deleniti laboriosam doloremque animi consequatur ipsam nostrum, accusamus amet quidem, aspernatur iure. Alias ut atque et dicta doloribus. Natus, facilis!', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(232, 'test', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit magni deleniti laboriosam doloremque animi consequatur ipsam nostrum, accusamus amet quidem, aspernatur iure. Alias ut atque et dicta doloribus. Natus, facilis!', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(233, 'test', 'test', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(234, 'test', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit magni deleniti laboriosam doloremque animi consequatur ipsam nostrum, accusamus amet quidem, aspernatur iure. Alias ut atque et dicta doloribus. Natus, facilis?', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(254, 'Ma dernière recette', 'miam ! c&#039;est bon ça !', 'ptitbarba@hotmail.com', 1, '2024-04-11', '', '', NULL),
(236, 'C&#039;est n', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit magni deleniti laboriosam doloremque animi consequatur ipsam nostrum, accusamus amet quidem, aspernatur iure. Alias ut atque et dicta doloribus. Natus, facilis?', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(237, 'Ma nouvelle recette', 'Voici ma nouvelle recette !\r\n\r\n1 : il faut la manger avec amour\r\n2 : Il faut la fabriquer avec amour ?', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(238, 'Ma nouvelle recette', 'Voici ma nouvelle recette !\r\n\r\n1 : il faut la manger avec amour\r\n2 : Il faut la fabriquer avec amour ?', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(239, 'Ma nouvelle recette', 'Voici ma nouvelle recette !\r\n\r\n1 : il faut la manger avec amour\r\n2 : Il faut la fabriquer avec amour ?', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(177, 'Ma nouvelle recette', 'Voici ma nouvelle recette !\r\n\r\n1 : il faut la manger avec amour\r\n2 : Il faut la fabriquer avec amour ?', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(249, 'Une recette Couscous', 'Miam miam', 'test5@test.com', 1, '2023-11-02', '', '', NULL),
(250, 'dsqdq', 'dsq', 'ptitbarba@hotmail.com', 1, '2023-11-05', '', '', NULL),
(251, 'dsqdsq', 'dsdsqdqs', 'ptitbarba@hotmail.com', 1, '2023-11-05', '', '', NULL),
(252, 'fsdfsdf', 'fdsdsfdsf', 'ptitbarba@hotmail.com', 1, '2023-11-10', '', '', NULL),
(240, 'Ma nouvelle recette 5', 'Voici ma nouvelle recette !\r\n\r\n1 : il faut la manger avec amour\r\n2 : Il faut la fabriquer avec amour ?', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(241, 'Ma nouvelle recette', 'Voici ma nouvelle recette !\r\n\r\n1 : il faut la manger avec amour\r\n2 : Il faut la fabriquer avec amour ?\r\n\r\ndsqdqs', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(242, 'Mes nouvelles recettes 5', 'Voici ma nouvelle recette !\r\n\r\n1 : il faut la manger avec amour\r\n2 : Il faut la fabriquer avec amour ?\r\n3 :\r\n4 :dsq\r\n-', 'ptitbarba@hotmail.com', 1, '2023-10-26', '', '', NULL),
(245, 'C&#039;est la recette d&#039;Adi -', 'Test de modification c&#039;est la recette de Adi.\r\nElle à l&#039;air bonne !\r\nIl vous faut bcp d&#039;amour :', 'ptitbarba@hotmail.com', 1, '2023-10-30', '', '', NULL),
(246, 'Ceci est ma recette', 'Voici ma nouvelle recette !\r\n\r\n1 : il faut la manger avec amour\r\n2 : Il faut la fabriquer avec amour ?\r\n3 :\r\n4 : dsq\r\n5 : Nouvelle entrée !', 'ptitbarba@hotmail.com', 1, '2023-10-30', '', '', NULL),
(244, 'c&#039;est ma nouvelle recedtte', 'C&#039;est ma nouvelle recette :\r\n1 : c&#039;est\r\n_\r\n-', 'ptitbarba@hotmail.com', 1, '2023-10-30', '', '', NULL),
(247, 'test de am nouvelle recette crée', 'Voici ma nouvelle recette !\r\n\r\n1 : il faut la manger avec amour\r\n2 : Il faut la fabriquer avec amour ?\r\n3 : edit\r\n4 : test 4\r\n5 : test 5', 'ptitbarba@hotmail.com', 1, '2023-10-30', '', '', NULL),
(256, 'd', '', 'ptitbarba@hotmail.com', 1, '2024-04-22', 'd', 'd', 'd');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(64) NOT NULL,
  `email` varchar(512) NOT NULL,
  `password` varchar(512) NOT NULL,
  `age` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `age`) VALUES
(1, 'Adrien Quijo', 'adrienquijo@gmail.com', 'test123', 37),
(2, 'Mathieu Fiquou', 'mathieu.fiquou@exemple.com', 'test123', 34),
(3, 'Mickaël Andrieu', 'mickael.andrieu@exemple.com', 'test123', 34),
(4, 'Mathieu Nebra', 'mathieu.nebra@exemple.com', 'test123', 34),
(5, 'Laurène Castor', 'laurene.castor@exemple.com', 'test123', 28),
(398, 'd', 'd@d.d', '$2y$12$s/sUmEnuPAF8LAppKNjx/Owd6SxLzk8Btzas4NkSBWbJBJg/SCCAK', 1),
(373, 'testii', 'testo@test.com', '$2y$12$dEBIVYFHyfiwt4nHN.T7FebBN.stcqbFIAsAODFnM7INgdeKs65EO', 1),
(356, 'dsqd', 'test@test.comddd', '$2y$12$7f40lqEI7PeisZ7KRBUUqevMbJKpKStqBhxKLLkTm0SKeVSrOvKty', 32),
(357, 'AdiBouh', 'test5@test.com', '$2y$12$q8u3DReJPqqxJMWoB5Jnq.UWgE1sm0jIL.GICK7YlsLTKlVd2mGai', 12),
(358, 'dfsdfsdf', 'ffsdfdsf@fsdfsdfs.com', '$2y$12$9X6y4RZgENTJnQTYW5ASDuC.yGrBioi6SmpU1dMkqGg5Q6D9vAfHK', 12),
(359, 'dsq', 'd@dsq.comd', '$2y$12$czRoh4PeVZxKHPw6PtM4duGjBunUcB/w2XnQiNaoAXdyGdZHjIj8.', 1),
(372, 'testi', 'testiiiiii@hotmail.com', '$2y$12$Nepl0UNs5lkTLQohCabsEeyUs9jAE1TjLR/c2TSXlPZWuNCPXngHu', 2),
(351, 'adi', 'ptitbarba@hotmail.com', '$2y$12$6G26gh2fmItumSmOaXZXO./9e97EjGcRXHgtGpRicPrnAk46iAmM.', 32);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Index pour la table `commentss`
--
ALTER TABLE `commentss`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Index pour la table `pwdreset`
--
ALTER TABLE `pwdreset`
  ADD PRIMARY KEY (`pwdReset_id`);

--
-- Index pour la table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipe_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT pour la table `commentss`
--
ALTER TABLE `commentss`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pwdreset`
--
ALTER TABLE `pwdreset`
  MODIFY `pwdReset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT pour la table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=399;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
