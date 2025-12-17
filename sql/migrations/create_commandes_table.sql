CREATE TABLE commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vente_id INT NOT NULL,
    user_id INT NOT NULL, -- L'utilisateur qui a pris la commande
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('En cours', 'Terminée', 'Annulée') DEFAULT 'En cours',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vente_id) REFERENCES ventes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
);
