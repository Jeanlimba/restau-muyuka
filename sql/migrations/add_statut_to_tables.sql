ALTER TABLE tables
ADD COLUMN statut ENUM('libre', 'occupée') NOT NULL DEFAULT 'libre';
