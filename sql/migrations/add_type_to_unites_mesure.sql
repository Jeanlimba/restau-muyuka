-- Ajoute un type pour classifier les unités de mesure
ALTER TABLE unites_mesure
ADD COLUMN type ENUM('vente', 'achat') NOT NULL DEFAULT 'vente' AFTER symbole;
