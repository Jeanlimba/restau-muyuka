CREATE TABLE fonctions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE
);

-- On insère les fonctions de base
INSERT INTO fonctions (nom) VALUES ('Serveur'), ('Caissier'), ('Administrateur');
