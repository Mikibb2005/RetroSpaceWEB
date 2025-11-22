<?php
// Iniciar sesión
session_start();

// Configuración de rutas
define('APP_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// Detectar base URL
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$baseUrl = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;
define('BASE_URL', $baseUrl);

// Cargar autoloader básico
spl_autoload_register(function ($class) {
    $file = APP_PATH . '/app/models/' . $class . '.php';
    if (file_exists($file)) require $file;
    
    $file = APP_PATH . '/app/core/' . $class . '.php';
    if (file_exists($file)) require $file;
    
    $file = APP_PATH . '/app/controllers/' . $class . '.php';
    if (file_exists($file)) require $file;
});

// Iniciar router
$router = new Router();

// Definir rutas
$router->addRoute('home', 'HomeController', 'index');
$router->addRoute('diario', 'DiarioController', 'index');
$router->addRoute('proyectos', 'ProyectosController', 'index');
$router->addRoute('foro', 'ForoController', 'index');
$router->addRoute('contacto', 'HomeController', 'contacto');
$router->addRoute('login', 'AuthController', 'login');
$router->addRoute('logout', 'AuthController', 'logout');

// Rutas con parámetros
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/mikisito-web', '', $path); // Ajustar según tu carpeta

// Enrutamiento simple
$page = 'home';
if (preg_match('/^\/diario\/(\d+)$/', $path, $matches)) {
    $controller = new DiarioController();
    $controller->ver($matches[1]);
    exit;
} elseif (preg_match('/^\/foro\/hilo\/(\d+)$/', $path, $matches)) {
    $controller = new ForoController();
    $controller->hilo($matches[1]);
    exit;
} elseif (preg_match('/^\/foro\/crear$/', $path)) {
    $controller = new ForoController();
    $controller->crearHilo();
    exit;
} elseif (preg_match('/^\/foro\/comentar$/', $path)) {
    $controller = new ForoController();
    $controller->comentar();
    exit;
} elseif (preg_match('/^\/perfil\/editar$/', $path)) {
    $controller = new ProfileController();
    $controller->edit();
    exit;
} elseif (preg_match('/^\/perfil\/(\d+)\/follow$/', $path, $matches)) {
    $controller = new ProfileController();
    $controller->follow($matches[1]);
    exit;
} elseif (preg_match('/^\/perfil\/(\d+)\/unfollow$/', $path, $matches)) {
    $controller = new ProfileController();
    $controller->unfollow($matches[1]);
    exit;
} elseif (preg_match('/^\/perfil\/(\d+)$/', $path, $matches)) {
    $controller = new ProfileController();
    $controller->index($matches[1]);
    exit;
} elseif (preg_match('/^\/perfil$/', $path)) {
    $controller = new ProfileController();
    $controller->index();
    exit;
} elseif (preg_match('/^\/registro$/', $path)) {
    $controller = new AuthController();
    $controller->registro();
    exit;
} elseif (preg_match('/\/login$/', $path)) {
    $page = 'login';
} elseif (preg_match('/\/logout$/', $path)) {
    $controller = new AuthController();
    $controller->logout();
    exit;
} elseif (preg_match('/\/diario$/', $path)) {
    $page = 'diario';
} elseif (preg_match('/\/proyectos$/', $path)) {
    $page = 'proyectos';
} elseif (preg_match('/\/foro$/', $path)) {
    $page = 'foro';
} elseif (preg_match('/\/contacto$/', $path)) {
    $page = 'contacto';
}

// Dispatch
$router->dispatch($page);
?>
