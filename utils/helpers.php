<?php
// Autoloader pour les modèles
function autoloadModels($className) {
    $file = __DIR__ . "/../app/models/{$className}.php";
    if (file_exists($file)) {
        require_once $file;
    } else {
        echo "Model not found: {$className} in {$file}<br>";
    }
}

spl_autoload_register('autoloadModels');

function view($view, $data = []) {
    // Chemins absolus
    $viewFile = __DIR__ . "/../app/views/{$view}.php";
    $layoutFile = __DIR__ . "/../app/views/layout.php";
    
    // Vérifier que les fichiers existent
    if (!file_exists($viewFile)) {
        die("View file not found: {$viewFile}");
    }
    if (!file_exists($layoutFile)) {
        die("Layout file not found: {$layoutFile}");
    }
    
    // Extraire les données et capturer la vue
    extract($data);
    ob_start();
    require $viewFile;
    $content = ob_get_clean();
    
    // Inclure le layout qui utilisera $content
    require $layoutFile;
}
//Ici juste pour voir si ça va marcher

function redirect($path) {
    header("Location: $path");
    exit;
}

function is_admin() {
    return ($_SESSION['user']['fonction'] ?? null) === 'Administrateur';
}

// Protection CSRF : un jeton par session, vérifié sur toutes les requêtes POST
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf() {
    $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (empty($_SESSION['csrf_token']) || !is_string($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(419);
        echo "Session expirée ou requête invalide. Rechargez la page et réessayez.";
        exit;
    }
}