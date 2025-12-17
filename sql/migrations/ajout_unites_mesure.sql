-- Migration pour ajouter les tables nécessaires aux nouvelles fonctionnalités

-- Table des unités de mesure
CREATE TABLE IF NOT EXISTS unites_mesure (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    symbole VARCHAR(20) NOT NULL,
    description TEXT,
    actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_nom (nom)
);

-- Modification de la table articles pour ajouter les nouveaux champs
ALTER TABLE articles 
ADD COLUMN unite_mesure_id INT NULL,
ADD COLUMN type_tarification ENUM('standard', 'varie') DEFAULT 'standard',
ADD COLUMN actif BOOLEAN DEFAULT TRUE,
ADD FOREIGN KEY (unite_mesure_id) REFERENCES unites_mesure(id);

-- Suppression de la colonne stock (si elle existe)
-- ALTER TABLE articles DROP COLUMN stock;

-- Insertion d'unités de mesure par défaut
INSERT INTO unites_mesure (nom, symbole, description) VALUES
('Pièce', 'pce', 'Article vendu à la pièce'),
('Litre', 'L', 'Volume en litres'),
('Kilogramme', 'kg', 'Poids en kilogrammes'),
('Gramme', 'g', 'Poids en grammes'),
('Centilitre', 'cl', 'Volume en centilitres');