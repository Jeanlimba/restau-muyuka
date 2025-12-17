CREATE TABLE equipements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    quantite INT DEFAULT 1,
    etat ENUM('Neuf', 'En service', 'En réparation', 'Hors service') DEFAULT 'En service',
    date_achat DATE,
    valeur DECIMAL(10, 2),
    fournisseur VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
