CREATE TABLE inventaires_equipements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_inventaire DATE NOT NULL,
    responsable_id INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (responsable_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE lignes_inventaire_equipement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inventaire_id INT NOT NULL,
    equipement_id INT NOT NULL,
    equipement_nom VARCHAR(255), -- Dénormalisé pour l'historique
    quantite_en_service INT DEFAULT 0,
    quantite_en_reparation INT DEFAULT 0,
    quantite_hors_service INT DEFAULT 0,
    FOREIGN KEY (inventaire_id) REFERENCES inventaires_equipements(id) ON DELETE CASCADE,
    FOREIGN KEY (equipement_id) REFERENCES equipements(id) ON DELETE CASCADE
);
