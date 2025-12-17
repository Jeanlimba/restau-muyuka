<?php

require_once __DIR__ . '/../models/Fonction.php';

class FonctionController {

    public function store() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['nom'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Le nom de la fonction est requis.']);
            return;
        }

        $fonctionModel = new Fonction();
        $newId = $fonctionModel->create($data);

        if ($newId) {
            http_response_code(201); // Created
            echo json_encode([
                'success' => true, 
                'fonction' => [
                    'id' => $newId, 
                    'nom' => $data['nom']
                ]
            ]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de la fonction.']);
        }
    }
}
