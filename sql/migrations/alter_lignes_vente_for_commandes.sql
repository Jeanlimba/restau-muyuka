-- Supprimer l'ancienne contrainte de clé étrangère vers la table ventes
ALTER TABLE lignes_vente DROP FOREIGN KEY lignes_vente_ibfk_1; -- Le nom peut varier, vérifiez-le dans votre DB si ça échoue

-- Supprimer la colonne vente_id
ALTER TABLE lignes_vente DROP COLUMN vente_id;

-- Ajouter la nouvelle colonne commande_id
ALTER TABLE lignes_vente ADD COLUMN commande_id INT NOT NULL AFTER id;

-- Ajouter la nouvelle contrainte de clé étrangère vers la table commandes
ALTER TABLE lignes_vente ADD CONSTRAINT fk_lignes_vente_commande
FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE;
