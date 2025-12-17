<?php
    function authMiddleware() {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }

    function gerantMiddleware() {
        if ($_SESSION['user']['role'] !== 'gerant') {
            $_SESSION['error'] = "Accès réservé au gérant";
            redirect('/');
        }
    }