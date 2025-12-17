-- Migration pour créer la table `tarifs` pour la tarification variée

CREATE TABLE `tarifs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `article_id` INT NOT NULL,
  `zone_id` INT NOT NULL,
  `prix` DECIMAL(10, 2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- S'assurer qu'il ne peut y avoir qu'un seul prix pour un article dans une zone donnée
  UNIQUE KEY `uk_article_zone` (`article_id`, `zone_id`),
  
  -- Lier à la table `articles`
  CONSTRAINT `fk_tarifs_article_id` 
    FOREIGN KEY (`article_id`) 
    REFERENCES `articles`(`id`) 
    ON DELETE CASCADE, -- Si l'article est supprimé, ses tarifs le sont aussi
    
  -- Lier à la table `zones`
  CONSTRAINT `fk_tarifs_zone_id` 
    FOREIGN KEY (`zone_id`) 
    REFERENCES `zones`(`id`) 
    ON DELETE CASCADE -- Si une zone est supprimée, ses tarifs le sont aussi
) ENGINE=InnoDB;

-- Ajouter des index pour améliorer les performances
ALTER TABLE `tarifs` ADD INDEX `idx_article_id` (`article_id`);
ALTER TABLE `tarifs` ADD INDEX `idx_zone_id` (`zone_id`);
