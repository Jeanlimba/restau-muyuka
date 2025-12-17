<?php
class Router {
    protected $routes = [];
    
    public function get($path, $controller, $middleware = null) {
        $this->routes['GET'][$path] = [
            'controller' => $controller,
            'middleware' => $middleware
        ];
    }
    
    public function post($path, $controller, $middleware = null) {
        $this->routes['POST'][$path] = [
            'controller' => $controller,
            'middleware' => $middleware
        ];
    }
    
    public function dispatch() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Route par défaut
        if ($path === '/') {
            $path = '/';
        }
        
        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];
            
            // Exécuter le middleware si présent
            if ($route['middleware']) {
                require_once "../app/middleware/{$route['middleware']}.php";
                call_user_func($route['middleware']);
            }
            
            [$controllerName, $action] = explode('@', $route['controller']);
            require_once "../app/controllers/{$controllerName}.php";
            
            $controller = new $controllerName();
            $controller->$action();
            
        } else {
            // Gestion des routes dynamiques (ex: /articles/edit/1)
            $this->handleDynamicRoutes($path, $method);
        }
    }
    
    private function handleDynamicRoutes($path, $method) {
        foreach ($this->routes[$method] as $routePath => $routeConfig) {
            // Vérifier les routes avec paramètres (ex: /articles/edit/{id})
            if (strpos($routePath, '{') !== false) {
                $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
                $pattern = str_replace('/', '\/', $pattern);
                
                if (preg_match('/^' . $pattern . '$/', $path, $matches)) {
                    // Exécuter le middleware si présent
                    if ($routeConfig['middleware']) {
                        require_once "../app/middleware/{$routeConfig['middleware']}.php";
                        call_user_func($routeConfig['middleware']);
                    }
                    
                    [$controllerName, $action] = explode('@', $routeConfig['controller']);
                    require_once "../app/controllers/{$controllerName}.php";
                    
                    $controller = new $controllerName();
                    // Passer le paramètre (ex: l'ID)
                    $controller->$action($matches[1]);
                    return;
                }
            }
        }
        
        // Si aucune route trouvée
        http_response_code(404);
        echo "Page non trouvée";
    }
}