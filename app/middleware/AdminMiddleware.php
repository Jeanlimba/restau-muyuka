<?php
    require_once __DIR__ . '/AuthMiddleware.php';

    // Réserve la route à la fonction « Administrateur ».
    function adminMiddleware() {
        authMiddleware();

        if (!is_admin()) {
            $message = "Action réservée à l'administrateur.";
            if (strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/api/') === 0) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $message]);
                exit;
            }
            $_SESSION['error'] = $message;
            redirect('/');
        }
    }
