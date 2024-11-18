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

-------- Création d'une TABLE PRODUITS avec ses TRADUCTIONS ------------
-- Créer une table produits 
CREATE TABLE produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2),
    categorie_id INT,
    FOREIGN KEY (categorie_id) REFERENCES categories(id)
);

-- Insérer un produit complet 
INSERT INTO produits (nom, description, prix, categorie_id) 
VALUES ('Playstation 5', 'Console de jeu de nouvelle génération', 499.99, 
        (SELECT id FROM categories WHERE nom = 'Consoles')
);

----- pas utile pour le moment
-- Pour gérer les traductions, créer une table produits traductions 
CREATE TABLE produits_traductions (
    produit_id INT,
    langue VARCHAR(10),
    description TEXT,
    FOREIGN KEY (produit_id) REFERENCES produits(id),
    PRIMARY KEY (produit_id, langue)
);
-- Insérer un produit
INSERT INTO produits (nom, description) VALUES ('Playstation 5');
-- Insérer une traduction manuelle
INSERT INTO produits_traductions (produit_id, langue, description) VALUES (1, 'en', 'Next-gen gaming console');
INSERT INTO produits_traductions (produit_id, langue, description) VALUES (1, 'fr', 'Console de jeu de nouvelle génération');
---- fin du pas utile

-------- Création d'une TABLE CATEGORIES avec ses SOUS-CATEGORIES et TRADUCTIONS------------
-------- Chaque ID de catégories correspond à une ID de categories_traductions ------------
-------- En fonction de la langue, la traduction d'une catégorie sera utilisée ------------

-- Créer une table catégories 
CREATE TABLE categories ( id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT DEFAULT NULL,
    FOREIGN KEY (parent_id) REFERENCES categories(id)
);

-- Pour gérer les traductions, créer une table catégorie traductions 
CREATE TABLE categories_traductions (
    categorie_id INT,
    langue VARCHAR(10),
    nom VARCHAR(255),
    description TEXT,
    PRIMARY KEY (categorie_id, langue),
    FOREIGN KEY (categorie_id) REFERENCES categories(id)
);
-- Insérer une catégorie principale
INSERT INTO categories (parent_id) VALUES (NULL); -- ID 1 : High-tech
INSERT INTO categories (parent_id) VALUES ((SELECT id FROM categories WHERE id = 2)); -- ID 2 : Jeux-vidéo
-- Insérer une sous-catégorie
INSERT INTO categories (parent_id) VALUES ((SELECT id FROM categories WHERE id = 3)); -- ID 3 : Consoles

-- Insérer les traductions pour la catégorie principale
INSERT INTO categories_traductions (categorie_id, langue, nom, description) VALUES (1, 'en', 'High-tech', 'High-tech category');
INSERT INTO categories_traductions (categorie_id, langue, nom, description) VALUES (1, 'fr', 'High-tech', 'Catégorie High-tech');
INSERT INTO categories_traductions (categorie_id, langue, nom, description) VALUES (2, 'en', 'Video-Games', 'Video Games category');
INSERT INTO categories_traductions (categorie_id, langue, nom, description) VALUES (2, 'fr', 'Jeux-Vidéos', 'Catégorie Jeux-Vidéos');
-- Insérer les traductions pour la sous-catégorie
INSERT INTO categories_traductions (categorie_id, langue, nom, description) VALUES (3, 'en', 'Consoles', 'Sub-category Consoles');
INSERT INTO categories_traductions (categorie_id, langue, nom, description) VALUES (3, 'fr', 'Consoles', 'Sous-catégorie Consoles');

------------------ Exemple de requête pour une catégorie console (id 3)
------------------ Où l'utilisateur à choisi une langue 'fr'
------------------ Nom de produit 'Playstation 5'
------------------ On récupère ID, description et nom de produit
SELECT p.id, p.nom, p.description
FROM produits p
JOIN categories_traductions c ON c.categorie_id = 3
WHERE c.langue = 'fr' AND p.nom = 'Playstation 5';

----- Exemple de langue préférée récupérée à partir des préférences de l'utilisateur
------ $preferredLanguage = 'en'; // Ou récupéré dynamiquement à partir des préférences de l'utilisateur
------- $searchTerm = 'votre_terme_de_recherche'
-- Sélection des produits avec leurs traductions de sous-catégories et catégories principales
SELECT 
    p.id, 
    p.nom AS produit, 
    p.description, 
    p.prix,
    tradsc.nom AS sous_categorie,
    tradcp.nom AS categorie_principale
FROM 
    produits p
    -- Jointure pour obtenir la sous-catégorie du produit
    JOIN categories sous_categories ON sous_categories.id = p.categorie_id
    -- Jointure pour obtenir la catégorie principale de la sous-catégorie
    LEFT JOIN categories categories_principales ON categories_principales.id = sous_categories.parent_id
    -- Jointure pour obtenir la traduction en anglais de la sous-catégorie
    LEFT JOIN categories_traductions tradsc ON sous_categories.id = tradsc.categorie_id AND tradsc.langue = $preferredLanguage
    -- Jointure pour obtenir la traduction en anglais de la catégorie principale
    LEFT JOIN categories_traductions tradcp ON categories_principales.id = tradcp.categorie_id AND tradcp.langue = $preferredLanguage
WHERE 
    -- tradsc.langue = 'en'
    MATCH(p.nom) AGAINST($searchTerm IN NATURAL LANGUAGE MODE) OR
    MATCH(tsc.nom) AGAINST($searchTerm IN NATURAL LANGUAGE MODE) OR
    MATCH(tcp.nom) AGAINST($searchTerm IN NATURAL LANGUAGE MODE);
    -- tradsc.langue = 'en' AND p.nom = 'Playstation 5';

ALTER TABLE produits ADD FULLTEXT(nom);
ALTER TABLE categories_traductions ADD FULLTEXT(nom);

