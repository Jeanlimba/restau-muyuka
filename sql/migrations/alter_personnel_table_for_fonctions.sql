-- D'abord, on ajoute la nouvelle colonne
ALTER TABLE personnel ADD COLUMN fonction_id INT;

-- (Optionnel) Ici, vous pourriez écrire une requête UPDATE pour migrer
-- les anciennes chaînes de caractères vers les nouveaux IDs si nécessaire.
-- UPDATE personnel p SET fonction_id = (SELECT id FROM fonctions f WHERE f.nom = p.fonction);

-- Ensuite, on supprime l'ancienne colonne
ALTER TABLE personnel DROP COLUMN fonction;

-- Enfin, on ajoute la contrainte de clé étrangère
ALTER TABLE personnel ADD CONSTRAINT fk_personnel_fonction
FOREIGN KEY (fonction_id) REFERENCES fonctions(id);
