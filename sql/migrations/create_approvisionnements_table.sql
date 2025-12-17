-- Création de la table pour suivre les approvisionnements d'articles
CREATE TABLE approvisionnements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_achat DECIMAL(10, 2) NOT NULL,
    fournisseur VARCHAR(255) NULL,
    user_id INT NOT NULL,
    observation TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
