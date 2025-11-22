<?php
class ForoController {
    private $hiloModel;
    private $comentarioModel;
    private $user;
    
    public function __construct() {
        $this->hiloModel = new ForoHilo();
        $this->comentarioModel = new Comentario();
        $this->user = new User();
    }
    
    public function index() {
        $hilos = $this->hiloModel->getAll();
        require __DIR__ . '/../views/foro/index.php';
    }
    
    public function hilo($id) {
        $hilo = $this->hiloModel->getById($id);
        if (!$hilo) {
            header('Location: /foro');
            exit;
        }
        
        $comentarios = $this->comentarioModel->getByHilo($id);
        $comentarios_tree = $this->comentarioModel->buildTree($comentarios);
        
        require __DIR__ . '/../views/foro/hilo.php';
    }
    
    public function crearHilo() {
        if (!$this->user->isLogged()) {
            header('Location: /login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'categoria' => $_POST['categoria'],
                'autor_id' => $this->user->getCurrentUserId()
            ];
            $this->hiloModel->crear($datos);
            header('Location: /foro');
            exit;
        }
        
        require __DIR__ . '/../views/foro/crear.php';
    }
    
    public function comentar() {
        if (!$this->user->isLogged()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenido = trim($_POST['contenido'] ?? '');
            $hiloId = intval($_POST['hilo_id'] ?? 0);
            $parentId = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
            
            if (empty($contenido) || $hiloId <= 0) {
                header('Location: ' . BASE_URL . '/foro');
                exit;
            }
            
            $datos = [
                'contenido' => $contenido,
                'autor_id' => $this->user->getCurrentUserId(),
                'hilo_id' => $hiloId,
                'parent_id' => $parentId
            ];
            
            $this->comentarioModel->crear($datos);
            header('Location: ' . BASE_URL . '/foro/hilo/' . $hiloId);
            exit;
        }
        
        header('Location: ' . BASE_URL . '/foro');
        exit;
    }
}
?>
