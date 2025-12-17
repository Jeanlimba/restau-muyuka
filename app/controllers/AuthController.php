<?php
class AuthController {
    public function login() {
        if ($_POST) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $user = (new User())->authenticate($email, $password);
            
            if ($user) {
                $_SESSION['user'] = $user;
                $_SESSION['user_id'] = $user['id'];
                echo "=== DEBUG: User session set ===";
                echo "<pre>";
                print_r($_SESSION['user']);
                echo "</pre>";
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

    // Nouvelle méthode pour gérer le mot de passe oublié
    public function forgotPassword() {
        if ($_POST) {
            $email = $_POST['email'];

            $result = (new User())->resetPassword($email);

            if ($result['success']) {
                view('auth/forgot_password', [
                    'success' => true,
                    'user' => $result['user'],
                    'nouveau_mot_de_passe' => $result['nouveau_mot_de_passe']
                ]);
                return;
            } else {
                $error = "Aucun utilisateur trouvé avec cet email.";
            }
        }

        view('auth/forgot_password', ['error' => $error ?? null]);
    }
}
