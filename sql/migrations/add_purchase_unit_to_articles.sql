-- Ajoute la gestion des unités d'achat et de la conversion à la table des articles
ALTER TABLE articles
ADD COLUMN purchase_unite_mesure_id INT NULL AFTER unite_mesure_id,
ADD COLUMN conversion_factor DECIMAL(10, 2) NOT NULL DEFAULT 1 AFTER purchase_unite_mesure_id,
ADD CONSTRAINT fk_articles_purchase_unit FOREIGN KEY (purchase_unite_mesure_id) REFERENCES unites_mesure(id);
