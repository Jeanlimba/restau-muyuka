-- Ajoute un champ pour la conclusion générale de l'inventaire
ALTER TABLE inventaires ADD COLUMN conclusion TEXT NULL AFTER notes;

-- Ajoute un champ pour justifier les écarts sur chaque ligne
ALTER TABLE lignes_inventaire ADD COLUMN justification VARCHAR(255) NULL AFTER ecart;
