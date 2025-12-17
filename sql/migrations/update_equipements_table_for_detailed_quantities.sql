-- On commence par ajouter les nouvelles colonnes avec une valeur par défaut de 0
ALTER TABLE equipements ADD COLUMN quantite_en_service INT NOT NULL DEFAULT 0;
ALTER TABLE equipements ADD COLUMN quantite_en_reparation INT NOT NULL DEFAULT 0;
ALTER TABLE equipements ADD COLUMN quantite_hors_service INT NOT NULL DEFAULT 0;

-- (Optionnel) Cette requête migre les données existantes. 
-- Elle met la quantité de l'équipement dans la colonne correspondant à son ancien état.
UPDATE equipements SET 
    quantite_en_service = CASE WHEN etat = 'En service' THEN quantite ELSE 0 END,
    quantite_en_reparation = CASE WHEN etat = 'En réparation' THEN quantite ELSE 0 END,
    quantite_hors_service = CASE WHEN etat = 'Hors service' THEN quantite ELSE 0 END;
    -- Ajoutez ici d'autres cas si vous aviez d'autres états comme 'Neuf'

-- On supprime les anciennes colonnes qui ne sont plus utiles
ALTER TABLE equipements DROP COLUMN quantite;
ALTER TABLE equipements DROP COLUMN etat;

-- On ajoute une colonne pour la quantité totale qui se calcule automatiquement
ALTER TABLE equipements ADD COLUMN quantite_totale INT 
    GENERATED ALWAYS AS (quantite_en_service + quantite_en_reparation + quantite_hors_service) STORED;
