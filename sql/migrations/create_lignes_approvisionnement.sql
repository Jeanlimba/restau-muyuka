-- Création de la table pour les lignes d'articles d'un approvisionnement
CREATE TABLE lignes_approvisionnement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    approvisionnement_id INT NOT NULL,
    article_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_achat DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (approvisionnement_id) REFERENCES approvisionnements(id) ON DELETE CASCADE,
    FOREIGN KEY (article_id) REFERENCES articles(id)
);
