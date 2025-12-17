-- Migration pour modifier la table `tables` et la lier à la table `zones`

-- 1. Ajouter la colonne `zone_id` qui sera une clé étrangère
ALTER TABLE `tables` ADD `zone_id` INT NULL AFTER `numero`;

-- 2. Mettre à jour la nouvelle colonne `zone_id` en se basant sur l'ancienne colonne `zone`
-- On fait correspondre le texte de l'ancienne colonne avec l'ID de la nouvelle table `zones`.
UPDATE `tables` SET `zone_id` = 1 WHERE `zone` = 'salle';
UPDATE `tables` SET `zone_id` = 2 WHERE `zone` = 'terrasse';
UPDATE `tables` SET `zone_id` = 3 WHERE `zone` = 'vip';

-- 3. Rendre la colonne non-nulle maintenant qu'elle est peuplée
ALTER TABLE `tables` MODIFY `zone_id` INT NOT NULL;

-- 4. Supprimer l'ancienne colonne `zone`
ALTER TABLE `tables` DROP COLUMN `zone`;

-- 5. Ajouter la contrainte de clé étrangère pour assurer l'intégrité des données
ALTER TABLE `tables` ADD CONSTRAINT `fk_tables_zone_id` 
FOREIGN KEY (`zone_id`) REFERENCES `zones`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- 6. Ajouter un index pour de meilleures performances sur les requêtes filtrant par zone
ALTER TABLE `tables` ADD INDEX `idx_zone_id` (`zone_id`);
