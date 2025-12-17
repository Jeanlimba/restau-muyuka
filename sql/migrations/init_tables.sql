-- Migration pour initialiser les tables avec un minimum de 10 tables par zone

-- Table des tables (si elle n'existe pas)
CREATE TABLE IF NOT EXISTS tables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL,
    zone ENUM('salle', 'terrasse', 'vip') NOT NULL,
    capacite INT DEFAULT 4,
    description TEXT,
    statut ENUM('libre', 'occupée', 'réservée') DEFAULT 'libre',
    actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_table_zone (numero, zone)
);

-- Insertion des tables par défaut (10 tables par zone)
-- Grande Salle (Tables 1-10)
INSERT IGNORE INTO tables (numero, zone, capacite, description) VALUES
(1, 'salle', 4, 'Table centrale près de l\'entrée'),
(2, 'salle', 4, 'Table près de la fenêtre'),
(3, 'salle', 6, 'Grande table pour groupe'),
(4, 'salle', 4, 'Table coin salon'),
(5, 'salle', 2, 'Table romantique'),
(6, 'salle', 4, 'Table standard'),
(7, 'salle', 8, 'Table familiale'),
(8, 'salle', 4, 'Table près du bar'),
(9, 'salle', 4, 'Table centrale'),
(10, 'salle', 6, 'Table pour réunions');

-- Terrasse (Tables 1-10)
INSERT IGNORE INTO tables (numero, zone, capacite, description) VALUES
(1, 'terrasse', 4, 'Table avec vue jardin'),
(2, 'terrasse', 4, 'Table ombragée'),
(3, 'terrasse', 6, 'Grande table terrasse'),
(4, 'terrasse', 4, 'Table près de la balustrade'),
(5, 'terrasse', 2, 'Table intime terrasse'),
(6, 'terrasse', 4, 'Table centrale terrasse'),
(7, 'terrasse', 8, 'Table familiale terrasse'),
(8, 'terrasse', 4, 'Table ensoleillée'),
(9, 'terrasse', 4, 'Table coin terrasse'),
(10, 'terrasse', 6, 'Table pour apéritif');

-- Salon VIP (Tables 1-10)
INSERT IGNORE INTO tables (numero, zone, capacite, description) VALUES
(1, 'vip', 4, 'Table VIP privative'),
(2, 'vip', 4, 'Table avec canapé'),
(3, 'vip', 6, 'Table VIP grande'),
(4, 'vip', 4, 'Table coin VIP'),
(5, 'vip', 2, 'Table VIP romantique'),
(6, 'vip', 4, 'Table VIP standard'),
(7, 'vip', 8, 'Table VIP réunion'),
(8, 'vip', 4, 'Table VIP près du bar privé'),
(9, 'vip', 4, 'Table VIP centrale'),
(10, 'vip', 6, 'Table VIP prestige');