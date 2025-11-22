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
        $isAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
        require __DIR__ . '/../views/proyectos/index.php';
    }
    
    public function ver($id) {
        $proyecto = $this->model->getById($id);
        if (!$proyecto) {
            header('Location: ' . BASE_URL . '/proyectos');
            exit;
        }
        $isAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
        require __DIR__ . '/../views/proyectos/ver.php';
    }
    
    public function crear() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../helpers/upload_helper.php';
            $archivos = [];
            if (isset($_FILES['archivos']) && !empty($_FILES['archivos']['name'][0])) {
                $archivos = UploadHelper::uploadMultiple($_FILES['archivos'], 'proyectos');
            }
            
            $datos = [
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'categoria' => $_POST['categoria'],
                'link1' => $_POST['link1'] ?? null,
                'link2' => $_POST['link2'] ?? null,
                'video_url' => $_POST['video_url'] ?? null,
                'imagen' => $_POST['imagen'] ?? null,
                'autor_id' => $_SESSION['user_id'],
                'archivos' => $archivos
            ];
            $this->model->crear($datos);
            header('Location: ' . BASE_URL . '/proyectos');
            exit;
        }
        
        require __DIR__ . '/../views/proyectos/crear.php';
    }
    
    public function editar($id) {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
        
        $proyecto = $this->model->getById($id);
        if (!$proyecto) {
            header('Location: ' . BASE_URL . '/proyectos');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../helpers/upload_helper.php';
            $archivosExistentes = isset($proyecto['archivos']) ? json_decode($proyecto['archivos'], true) : [];
            
            if (isset($_FILES['archivos']) && !empty($_FILES['archivos']['name'][0])) {
                if (!empty($archivosExistentes)) {
                    UploadHelper::deleteFiles($archivosExistentes);
                }
                $archivos = UploadHelper::uploadMultiple($_FILES['archivos'], 'proyectos');
            } else {
                $archivos = $archivosExistentes;
            }
            
            $datos = [
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'categoria' => $_POST['categoria'],
                'link1' => $_POST['link1'] ?? null,
                'link2' => $_POST['link2'] ?? null,
                'video_url' => $_POST['video_url'] ?? null,
                'imagen' => $_POST['imagen'] ?? null,
                'archivos' => $archivos
            ];
            $this->model->actualizar($id, $datos);
            header('Location: ' . BASE_URL . '/proyectos');
            exit;
        }
        
        require __DIR__ . '/../views/proyectos/editar.php';
    }
    
    public function eliminar($id) {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->eliminar($id);
            header('Location: ' . BASE_URL . '/proyectos');
            exit;
        }
    }
}
?>
