<?php
require_once __DIR__ . '/../models/ProyectoActualizacion.php';
require_once __DIR__ . '/../models/ProyectoComentario.php';

class ProyectosController {
    private $model;
    private $updateModel;
    private $commentModel;
    private $user;
    
    public function __construct() {
        $this->model = new Proyecto();
        $this->updateModel = new ProyectoActualizacion();
        $this->commentModel = new ProyectoComentario();
        $this->user = new User();
    }
    
    public function index() {
        $categoria = $_GET['categoria'] ?? null;
        $proyectos = $this->model->getAll($categoria);
        $canCreate = $this->user->isLogged();
        $isAdmin = $this->user->isAdmin(); // Para compatibilidad con vista existente si se usa
        require __DIR__ . '/../views/proyectos/index.php';
    }
    
    public function ver($id) {
        $proyecto = $this->model->getById($id);
        if (!$proyecto) {
            header('Location: ' . BASE_URL . '/proyectos');
            exit;
        }
        
        $currentUserId = $this->user->getCurrentUserId();
        $isOwner = ($currentUserId == $proyecto['autor_id']);
        $isAdmin = $this->user->isAdmin();
        $canEdit = $isOwner || $isAdmin;
        
        // Cargar actualizaciones
        $actualizaciones = $this->updateModel->getByProyectoId($id);
        
        // Cargar preview de comentarios
        foreach ($actualizaciones as &$act) {
            $act['comentarios_preview'] = $this->commentModel->getPreview($act['id'], 2);
            $act['total_comentarios'] = $this->commentModel->countByActualizacion($act['id']);
        }
        
        require __DIR__ . '/../views/proyectos/ver.php';
    }
    
    public function crear() {
        if (!$this->user->isLogged()) {
            header('Location: ' . BASE_URL . '/login');
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
                'autor_id' => $this->user->getCurrentUserId(),
                'archivos' => $archivos
            ];
            $this->model->crear($datos);
            header('Location: ' . BASE_URL . '/proyectos');
            exit;
        }
        
        require __DIR__ . '/../views/proyectos/crear.php';
    }
    
    public function editar($id) {
        $proyecto = $this->model->getById($id);
        if (!$proyecto) {
            header('Location: ' . BASE_URL . '/proyectos');
            exit;
        }
        
        $currentUserId = $this->user->getCurrentUserId();
        if ($currentUserId != $proyecto['autor_id'] && !$this->user->isAdmin()) {
            header('Location: ' . BASE_URL . '/proyectos');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../helpers/upload_helper.php';
            $archivosExistentes = isset($proyecto['archivos']) ? json_decode($proyecto['archivos'], true) : [];
            
            if (isset($_FILES['archivos']) && !empty($_FILES['archivos']['name'][0])) {
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
            header('Location: ' . BASE_URL . '/proyectos/ver/' . $id);
            exit;
        }
        
        require __DIR__ . '/../views/proyectos/editar.php';
    }
    
    public function eliminar($id) {
        $proyecto = $this->model->getById($id);
        if (!$proyecto) return;
        
        $currentUserId = $this->user->getCurrentUserId();
        if ($currentUserId != $proyecto['autor_id'] && !$this->user->isAdmin()) {
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->eliminar($id);
            header('Location: ' . BASE_URL . '/proyectos');
            exit;
        }
    }

    public function crearActualizacion($proyectoId) {
        $proyecto = $this->model->getById($proyectoId);
        if (!$proyecto) {
            header('Location: /proyectos');
            exit;
        }

        $currentUserId = $this->user->getCurrentUserId();
        if ($currentUserId != $proyecto['autor_id']) {
            header('Location: /proyectos/ver/' . $proyectoId);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../helpers/upload_helper.php';
            $archivos = [];
            if (isset($_FILES['archivos']) && !empty($_FILES['archivos']['name'][0])) {
                $archivos = UploadHelper::uploadMultiple($_FILES['archivos'], 'proyectos');
            }
            $archivosJson = !empty($archivos) ? json_encode($archivos) : null;

            $this->updateModel->crear($proyectoId, $_POST['titulo'], $_POST['contenido'], $archivosJson);
            $this->model->touch($proyectoId);
            header('Location: ' . BASE_URL . '/proyectos/ver/' . $proyectoId);
            exit;
        }
        
        require __DIR__ . '/../views/proyectos/crear_actualizacion.php';
    }

    public function verActualizacion($id) {
        $actualizacion = $this->updateModel->getById($id);
        if (!$actualizacion) {
            header('Location: ' . BASE_URL . '/proyectos');
            exit;
        }
        $proyecto = $this->model->getById($actualizacion['proyecto_id']);
        $comentarios = $this->commentModel->getByActualizacionId($id);
        
        $isOwner = ($this->user->getCurrentUserId() == $proyecto['autor_id']);
        
        require __DIR__ . '/../views/proyectos/actualizacion.php';
    }

    public function comentar($actualizacionId) {
        if (!$this->user->isLogged()) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenido = $_POST['contenido'];
            $this->commentModel->crear($actualizacionId, $this->user->getCurrentUserId(), $contenido);
            header('Location: ' . BASE_URL . '/proyectos/actualizacion/' . $actualizacionId);
            exit;
        }
    }
}
?>
