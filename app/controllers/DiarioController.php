<?php
class DiarioController {
    private $model;
    private $user;
    
    public function __construct() {
        $this->model = new Post();
        $this->user = new User();
    }
    
    public function index() {
        $posts = $this->model->getAll();
        require __DIR__ . '/../views/diario/index.php';
    }
    
    public function ver($id) {
        $post = $this->model->getById($id);
        if (!$post) {
            header('Location: /diario');
            exit;
        }
        require __DIR__ . '/../views/diario/post.php';
    }
    
    public function crear() {
        if (!$this->user->isAdmin()) {
            header('Location: /');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'titulo' => $_POST['titulo'],
                'contenido' => $_POST['contenido'],
                'imagen' => $_POST['imagen'] ?? null,
                'codigo_embed' => $_POST['codigo_embed'] ?? null,
                'autor_id' => $this->user->getCurrentUserId()
            ];
            $this->model->crear($datos);
            header('Location: /diario');
            exit;
        }
    }
}
?>
