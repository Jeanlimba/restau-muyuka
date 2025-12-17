-- Migration pour créer et peupler la table `zones`

-- 1. Création de la table `zones`
CREATE TABLE `zones` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `actif` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Insertion des zones par défaut
INSERT INTO `zones` (`nom`, `description`) VALUES
('Grande Salle', 'Zone principale du restaurant'),
('Terrasse', 'Zone extérieure'),
('VIP', 'Zone réservée pour les événements privés');
