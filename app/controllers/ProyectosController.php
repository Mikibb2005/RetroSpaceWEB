<?php
class ProyectosController {
    private $model;
    private $user;
    
    public function __construct() {
        $this->model = new Proyecto();
        $this->user = new User();
    }
    
    public function index() {
        $categoria = $_GET['categoria'] ?? null;
        $proyectos = $this->model->getAll($categoria);
        require __DIR__ . '/../views/proyectos/index.php';
    }
    
    public function crear() {
        if (!$this->user->isAdmin()) {
            header('Location: /');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'categoria' => $_POST['categoria'],
                'link1' => $_POST['link1'] ?? null,
                'link2' => $_POST['link2'] ?? null,
                'video_url' => $_POST['video_url'] ?? null,
                'imagen' => $_POST['imagen'] ?? null,
                'autor_id' => $this->user->getCurrentUserId()
            ];
            $this->model->crear($datos);
            header('Location: /proyectos');
            exit;
        }
    }
}
?>
