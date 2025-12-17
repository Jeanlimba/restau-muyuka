-- Mettre à jour la table des approvisionnements pour gérer les en-têtes de bons d'entrée
ALTER TABLE approvisionnements
ADD COLUMN numero_bon VARCHAR(50) NULL UNIQUE AFTER id,
ADD COLUMN date_approvisionnement DATE NULL AFTER numero_bon,
DROP FOREIGN KEY approvisionnements_ibfk_1,
DROP COLUMN article_id,
DROP COLUMN quantite,
DROP COLUMN prix_achat;
