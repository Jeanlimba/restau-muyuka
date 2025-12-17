ALTER TABLE `ventes` CHANGE `statut` `statut` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_cours';
ALTER TABLE `tables` CHANGE `statut` `statut` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'libre';
UPDATE `tables` SET `statut` = 'libre' WHERE `statut` NOT IN ('occupee', 'en_attente_paiement');
