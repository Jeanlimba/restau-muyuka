-- Renommer la colonne 'numero' en 'nom' et changer son type pour plus de flexibilité
ALTER TABLE tables CHANGE COLUMN numero nom VARCHAR(100) NOT NULL;
