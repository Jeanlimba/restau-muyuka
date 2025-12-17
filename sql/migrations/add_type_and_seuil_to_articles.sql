-- Ajoute un type pour différencier les produits finis des matières premières
ALTER TABLE articles ADD COLUMN type ENUM('Produit fini', 'Matière première') DEFAULT 'Produit fini';

-- Ajoute un seuil d'alerte pour les stocks bas
ALTER TABLE articles ADD COLUMN stock_seuil DECIMAL(10, 2) DEFAULT 0.00;
