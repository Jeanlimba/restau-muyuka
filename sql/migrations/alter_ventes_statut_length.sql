-- Augmenter la taille de la colonne statut pour permettre la valeur 'annulee'
ALTER TABLE ventes MODIFY COLUMN statut VARCHAR(20) NOT NULL;
