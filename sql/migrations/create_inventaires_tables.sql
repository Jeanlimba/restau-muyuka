CREATE TABLE inventaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_inventaire DATE NOT NULL,
    responsable_id INT,
    statut ENUM('En cours', 'Terminé', 'Annulé') DEFAULT 'En cours',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (responsable_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE lignes_inventaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inventaire_id INT NOT NULL,
    article_id INT NOT NULL,
    stock_theorique DECIMAL(10, 2) NOT NULL,
    stock_physique DECIMAL(10, 2) NOT NULL,
    ecart DECIMAL(10, 2) GENERATED ALWAYS AS (stock_physique - stock_theorique) STORED,
    FOREIGN KEY (inventaire_id) REFERENCES inventaires(id) ON DELETE CASCADE,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);
