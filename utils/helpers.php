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

function redirect($path) {
    header("Location: $path");
    exit;
}