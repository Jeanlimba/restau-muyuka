<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Personnel.php';
require_once __DIR__ . '/../models/Fonction.php';

class UserController {

    public function index() {
        $personnelModel = new Personnel();
        $users = $personnelModel->allWithUserDetails();
        view('users/index', ['users' => $users]);
    }

    public function create() {
        $fonctionModel = new Fonction();
        $fonctions = $fonctionModel->all();
        view('users/create', ['fonctions' => $fonctions]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/users/create');
            return;
        }

        $pdo = Database::getInstance()->getConnection();
        $userModel = new User();
        $personnelModel = new Personnel();

        if ($userModel->findByEmail($_POST['email'])) {
            $_SESSION['error'] = "Cette adresse email est déjà utilisée.";
            redirect('/users/create');
            return;
        }

        try {
            $pdo->beginTransaction();

            // 1. Créer l'utilisateur
            $userId = $userModel->create([
                'nom' => $_POST['nom'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ]);

            if (!$userId) {
                throw new Exception("La création de l'utilisateur a échoué.");
            }

            // 2. Créer l'entrée personnel
            $personnelData = [
                'user_id' => $userId,
                'fonction_id' => $_POST['fonction_id'],
                'salaire' => $_POST['salaire'],
                'date_embauche' => $_POST['date_embauche']
            ];
            
            if (!$personnelModel->create($personnelData)) {
                 throw new Exception("La création des détails du personnel a échoué.");
            }

            $pdo->commit();
            $_SESSION['message'] = "Agent créé avec succès.";
            redirect('/users');

        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Erreur lors de la création de l'agent: " . $e->getMessage();
            redirect('/users/create');
        }
    }

    public function edit($id) {
        $userModel = new User();
        $personnelModel = new Personnel();
        $fonctionModel = new Fonction();
        
        $user = $userModel->find($id);
        if (!$user) {
            http_response_code(404);
            $_SESSION['error'] = "Utilisateur non trouvé.";
            redirect('/users');
            return;
        }
        
        $personnel = $personnelModel->findByUserId($id);
        $fonctions = $fonctionModel->all();

        $user['fonction_id'] = $personnel['fonction_id'] ?? '';
        $user['salaire'] = $personnel['salaire'] ?? '';
        $user['date_embauche'] = $personnel['date_embauche'] ?? '';

        view('users/edit', ['user' => $user, 'fonctions' => $fonctions]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/users/edit/' . $id);
            return;
        }
        
        $pdo = Database::getInstance()->getConnection();
        $userModel = new User();
        $personnelModel = new Personnel();
        
        try {
            $pdo->beginTransaction();

            // 1. Mettre à jour l'utilisateur
            $userData = [
                'nom' => $_POST['nom'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ];
            if (!$userModel->update($id, $userData)) {
                 throw new Exception("La mise à jour de l'utilisateur a échoué.");
            }

            // 2. Mettre à jour les infos personnel
            $personnelData = [
                'fonction_id' => $_POST['fonction_id'],
                'salaire' => $_POST['salaire'],
                'date_embauche' => $_POST['date_embauche']
            ];
            if (!$personnelModel->update($id, $personnelData)) {
                throw new Exception("La mise à jour des détails du personnel a échoué.");
            }
            
            $pdo->commit();
            $_SESSION['message'] = "Agent mis à jour avec succès.";
            redirect('/users');

        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Erreur lors de la mise à jour: " . $e->getMessage();
            redirect('/users/edit/' . $id);
        }
    }

    public function delete($id) {
        $userModel = new User();
        if ($userModel->delete($id)) {
            $_SESSION['message'] = "Agent supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression.";
        }
        redirect('/users');
    }
}