-- Ajoute le dernier coût d'achat connu à la table des articles
ALTER TABLE articles ADD COLUMN dernier_cout_achat DECIMAL(10, 2) DEFAULT 0.00;

-- Ajoute le coût d'achat au moment de la vente dans les lignes de vente
ALTER TABLE lignes_vente ADD COLUMN cout_achat_unitaire DECIMAL(10, 2) DEFAULT 0.00;
