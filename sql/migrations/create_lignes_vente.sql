-- Migration pour créer la table lignes_vente
-- Cette table stocke les détails de chaque article pour une vente donnée.

CREATE TABLE lignes_vente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vente_id INT NOT NULL,
    article_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire_ht DECIMAL(10, 2) NOT NULL,
    tva DECIMAL(5, 2) NOT NULL DEFAULT 20.00, -- Le taux de TVA au moment de la vente
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (vente_id) REFERENCES ventes(id) ON DELETE CASCADE,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Ajout d'index pour améliorer les performances des requêtes
ALTER TABLE lignes_vente ADD INDEX idx_vente_id (vente_id);
ALTER TABLE lignes_vente ADD INDEX idx_article_id (article_id);
