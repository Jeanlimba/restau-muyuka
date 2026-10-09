-- Attribue la fonction « Administrateur » au compte Admin Principal.
-- Nécessaire depuis que la gestion des agents, des articles et les suppressions
-- sont réservées à l'administrateur.

INSERT INTO personnel (user_id, fonction_id)
SELECT u.id, f.id
FROM users u
JOIN fonctions f ON f.nom = 'Administrateur'
WHERE u.email = 'admin@restaurant.com'
  AND NOT EXISTS (SELECT 1 FROM personnel p WHERE p.user_id = u.id);

UPDATE personnel p
JOIN users u ON u.id = p.user_id
JOIN fonctions f ON f.nom = 'Administrateur'
SET p.fonction_id = f.id
WHERE u.email = 'admin@restaurant.com';
