ALTER TABLE ventes
ADD COLUMN user_id INT NULL AFTER table_id,
ADD CONSTRAINT fk_ventes_users FOREIGN KEY (user_id) REFERENCES users(id);
