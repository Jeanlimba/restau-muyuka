<?php
class AuthController {
    public function login() {
        if ($_POST) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $user = (new User())->authenticate($email, $password);
            
            if ($user) {
                // Nouvel identifiant de session pour empêcher la fixation de session
                session_regenerate_id(true);
                $_SESSION['user'] = $user;
                $_SESSION['user_id'] = $user['id'];
                redirect('/');
            } else {
                $error = "Email ou mot de passe incorrect";
            }
        }
        
        view('auth/login', ['error' => $error ?? null]);
    }
    
    public function logout() {
        session_destroy();
        redirect('/login');
    }

    // Pas de réinitialisation en libre-service (aucun envoi d'email disponible) :
    // un administrateur redéfinit le mot de passe depuis la gestion des agents.
    public function forgotPassword() {
        view('auth/forgot_password');
    }
}
