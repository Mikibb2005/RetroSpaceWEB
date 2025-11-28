<?php
/**
 * Controlador de Contacto
 * Gestiona el formulario de contacto
 */

require_once __DIR__ . '/../models/MensajeContacto.php';

class ContactoController {
    private $db;
    private $user;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        if (isset($_SESSION['user_id'])) {
            $userModel = new User();
            $this->user = $userModel->getUserById($_SESSION['user_id']);
        } else {
            $this->user = null;
        }
    }
    
    /**
     * Mostrar formulario de contacto
     */
    public function index() {
        // Generar token CSRF si no existe
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Si el usuario está logueado, pre-rellenar datos
        $nombre = $this->user ? $this->user['nombre_real'] ?? $this->user['username'] : '';
        $email = $this->user ? $this->user['email'] : '';
        $errors = $_SESSION['contact_errors'] ?? [];
        
        $data = [
            'nombre' => $nombre,
            'email' => $email,
            'success' => $_SESSION['contact_success'] ?? false,
            'errors' => $errors
        ];
        
        // Limpiar mensajes flash
        unset($_SESSION['contact_success']);
        unset($_SESSION['contact_errors']);
        
        require __DIR__ . '/../views/contacto/index.php';
    }
    
    /**
     * Procesar envío del formulario
     */
    public function enviar() {
        // Solo POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/contacto');
            exit;
        }
        
        // Verificar token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['contact_errors'] = ['general' => __('error.csrf')];
            header('Location: ' . BASE_URL . '/contacto');
            exit;
        }
        
        // Sanitizar datos
        $datos = MensajeContacto::sanitizar($_POST);
        
        // Validar
        $errores = MensajeContacto::validar($datos);
        
        if (!empty($errores)) {
            $_SESSION['contact_errors'] = $errores;
            $_SESSION['contact_form_data'] = $datos; // Preservar datos
            header('Location: ' . BASE_URL . '/contacto');
            exit;
        }
        
        // Guardar en base de datos
        $guardado = MensajeContacto::crear($this->db, $datos);
        
        if ($guardado) {
            // Opcional: Enviar email de notificación al admin
            $this->enviarEmailNotificacion($datos);
            
            $_SESSION['contact_success'] = true;
            
            // Limpiar datos del formulario
            unset($_SESSION['contact_form_data']);
            
            header('Location: ' . BASE_URL . '/contacto?success=1');
        } else {
            $_SESSION['contact_errors'] = ['general' => __('contact.error_save')];
            header('Location: ' . BASE_URL . '/contacto');
        }
        
        exit;
    }
    
    /**
     * Enviar email de notificación (opcional)
     */
    private function enviarEmailNotificacion($datos) {
        // Solo si está configurado
        if (!defined('CONTACT_EMAIL') || !CONTACT_EMAIL) {
            return;
        }
        
        $to = CONTACT_EMAIL;
        $subject = '[RetroSpace] Nuevo mensaje de contacto: ' . $datos['asunto'];
        
        $message = "Has recibido un nuevo mensaje de contacto:\n\n";
        $message .= "Nombre: " . $datos['nombre'] . "\n";
        $message .= "Email: " . $datos['email'] . "\n";
        $message .= "Asunto: " . $datos['asunto'] . "\n\n";
        $message .= "Mensaje:\n" . $datos['mensaje'] . "\n\n";
        $message .= "---\n";
        $message .= "Enviado desde: " . $datos['ip_address'] . "\n";
        $message .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
        
        $headers = [
            'From: noreply@retrospace.com',
            'Reply-To: ' . $datos['email'],
            'X-Mailer: PHP/' . phpversion(),
            'Content-Type: text/plain; charset=UTF-8'
        ];
        
        @mail($to, $subject, $message, implode("\r\n", $headers));
    }
}
