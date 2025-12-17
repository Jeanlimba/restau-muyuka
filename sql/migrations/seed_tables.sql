-- Fichier de seeding pour peupler la table `tables`.
-- ATTENTION : Ce script supprime toutes les données existantes dans la table `tables`.

-- 1. Vider la table pour un nouveau départ.
TRUNCATE TABLE `tables`;

-- 2. Insérer 10 tables pour la "Grande Salle" (zone_id = 1)
INSERT INTO `tables` (numero, zone_id, capacite, description, statut, nom) VALUES
(1, 1, 4, 'Près de la fenêtre', 'libre', 'Salle 1'),
(2, 1, 4, '', 'libre', 'Salle 2'),
(3, 1, 2, 'Petite table pour deux', 'libre', 'Salle 3'),
(4, 1, 6, 'Table pour groupe', 'libre', 'Salle 4'),
(5, 1, 4, '', 'libre', 'Salle 5'),
(6, 1, 2, '', 'libre', 'Salle 6'),
(7, 1, 8, 'Grande table familiale', 'libre', 'Salle 7'),
(8, 1, 4, '', 'libre', 'Salle 8'),
(9, 1, 4, '', 'libre', 'Salle 9'),
(10, 1, 6, '', 'libre', 'Salle 10');

-- 3. Insérer 10 tables pour la "Terrasse" (zone_id = 2)
INSERT INTO `tables` (numero, zone_id, capacite, description, statut, nom) VALUES
(1, 2, 4, 'Table ensoleillée', 'libre', 'Terrasse 1'),
(2, 2, 2, '', 'libre', 'Terrasse 2'),
(3, 2, 4, 'Près du parasol', 'libre', 'Terrasse 3'),
(4, 2, 4, '', 'libre', 'Terrasse 4'),
(5, 2, 6, 'Banc pour groupe', 'libre', 'Terrasse 5'),
(6, 2, 2, '', 'libre', 'Terrasse 6'),
(7, 2, 4, '', 'libre', 'Terrasse 7'),
(8, 2, 2, 'Mange-debout', 'libre', 'Terrasse 8'),
(9, 2, 4, '', 'libre', 'Terrasse 9'),
(10, 2, 4, '', 'libre', 'Terrasse 10');

-- 4. Insérer 10 tables pour le "Salon VIP" (zone_id = 3)
INSERT INTO `tables` (numero, zone_id, capacite, description, statut, nom) VALUES
(1, 3, 6, 'Salon privé Alpha', 'libre', 'VIP 1'),
(2, 3, 6, 'Salon privé Beta', 'libre', 'VIP 2'),
(3, 3, 8, 'Grand salon', 'libre', 'VIP 3'),
(4, 3, 4, '', 'libre', 'VIP 4'),
(5, 3, 4, '', 'libre', 'VIP 5'),
(6, 3, 2, 'Table discrète', 'libre', 'VIP 6'),
(7, 3, 10, 'Table de réception', 'libre', 'VIP 7'),
(8, 3, 6, '', 'libre', 'VIP 8'),
(9, 3, 4, '', 'libre', 'VIP 9'),
(10, 3, 8, '', 'libre', 'VIP 10');
