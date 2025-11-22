<?php
class Router {
    private $routes = [];
    private $currentPage = 'home';

    public function addRoute($page, $controller, $action) {
        $this->routes[$page] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch($page) {
        $this->currentPage = $page;
        
        if (isset($this->routes[$page])) {
            $controllerName = $this->routes[$page]['controller'];
            $actionName = $this->routes[$page]['action'];
            
            $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controller = new $controllerName();
                if (method_exists($controller, $actionName)) {
                    $controller->$actionName();
                    return;
                }
            }
        }
        
        // 404
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Página no encontrada</h1>";
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function isActive($page) {
        return $this->currentPage === $page ? 'active' : '';
    }
}
?>
