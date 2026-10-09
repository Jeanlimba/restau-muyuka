<?php
    function authMiddleware() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // Fonction relue en base à chaque requête : un changement de rôle
        // ou une désactivation s'applique sans attendre une reconnexion.
        $_SESSION['user']['fonction'] = (new User())->getFonction($_SESSION['user']['id']);
    }
