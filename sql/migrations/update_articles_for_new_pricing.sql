-- Migration pour modifier la table `articles` pour la nouvelle gestion de tarification

-- 1. Ajouter la nouvelle colonne `prix` pour la tarification standard
ALTER TABLE `articles` ADD `prix` DECIMAL(10, 2) NULL DEFAULT 0.00 AFTER `type_tarification`;

-- 2. Transférer les anciens prix vers la nouvelle structure
-- Pour les articles à tarification standard, on copie le prix de la salle.
UPDATE `articles` SET `prix` = `prix_salle` WHERE `type_tarification` = 'standard';

-- Pour les articles à tarification variée, on insère les anciens prix dans la nouvelle table `tarifs`.
-- Note: Assurez-vous que les IDs des zones correspondent (1=salle, 2=terrasse, 3=vip).
INSERT INTO `tarifs` (article_id, zone_id, prix)
SELECT id, 1, prix_salle FROM `articles` WHERE `type_tarification` = 'varie' AND `prix_salle` > 0;

INSERT INTO `tarifs` (article_id, zone_id, prix)
SELECT id, 2, prix_terrasse FROM `articles` WHERE `type_tarification` = 'varie' AND `prix_terrasse` > 0;

INSERT INTO `tarifs` (article_id, zone_id, prix)
SELECT id, 3, prix_vip FROM `articles` WHERE `type_tarification` = 'varie' AND `prix_vip` > 0;


-- 3. Supprimer les anciennes colonnes de prix
ALTER TABLE `articles` 
  DROP COLUMN `prix_salle`,
  DROP COLUMN `prix_terrasse`,
  DROP COLUMN `prix_vip`;
