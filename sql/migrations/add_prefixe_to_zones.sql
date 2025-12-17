-- Ajouter un préfixe aux zones pour la dénomination des tables (ex: 'T' pour Terrasse)
ALTER TABLE zones ADD prefixe VARCHAR(10) NULL AFTER nom;
