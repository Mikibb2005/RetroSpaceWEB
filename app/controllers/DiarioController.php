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
        $isAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
        require __DIR__ . '/../views/diario/index.php';
    }
    
    public function ver($id) {
        $post = $this->model->getById($id);
        if (!$post) {
            header('Location:' . BASE_URL . '/diario');
            exit;
        }
        $isAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
        require __DIR__ . '/../views/diario/post.php';
    }
    
    public function crear() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar archivos subidos
            require_once __DIR__ . '/../helpers/upload_helper.php';
            $archivos = [];
            if (isset($_FILES['archivos']) && !empty($_FILES['archivos']['name'][0])) {
                $archivos = UploadHelper::uploadMultiple($_FILES['archivos'], 'diario');
            }
            
            $datos = [
                'titulo' => $_POST['titulo'],
                'contenido' => $_POST['contenido'],
                'imagen' => $_POST['imagen'] ?? null,
                'codigo_embed' => $_POST['codigo_embed'] ?? null,
                'autor_id' => $_SESSION['user_id'],
                'archivos' => $archivos
            ];
            $this->model->crear($datos);
            header('Location: ' . BASE_URL . '/diario');
            exit;
        }
        
        require __DIR__ . '/../views/diario/crear.php';
    }
    
    public function editar($id) {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
        
        $post = $this->model->getById($id);
        if (!$post) {
            header('Location: ' . BASE_URL . '/diario');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../helpers/upload_helper.php';
            
            // Obtener archivos existentes
            $archivosExistentes = isset($post['archivos']) ? json_decode($post['archivos'], true) : [];
            
            // Si se suben nuevos archivos, eliminar los antiguos
            if (isset($_FILES['archivos']) && !empty($_FILES['archivos']['name'][0])) {
                // Eliminar archivos antiguos del servidor
                if (!empty($archivosExistentes)) {
                    UploadHelper::deleteFiles($archivosExistentes);
                }
                // Subir nuevos archivos
                $archivos = UploadHelper::uploadMultiple($_FILES['archivos'], 'diario');
            } else {
                // Mantener los existentes
                $archivos = $archivosExistentes;
            }
            
            $datos = [
                'titulo' => $_POST['titulo'],
                'contenido' => $_POST['contenido'],
                'imagen' => $_POST['imagen'] ?? null,
                'codigo_embed' => $_POST['codigo_embed'] ?? null,
                'archivos' => $archivos
            ];
            $this->model->actualizar($id, $datos);
            header('Location: ' . BASE_URL . '/diario/' . $id);
            exit;
        }
        
        require __DIR__ . '/../views/diario/editar.php';
    }
    
    public function eliminar($id) {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->eliminar($id);
            header('Location: ' . BASE_URL . '/diario');
            exit;
        }
    }
}
?>
