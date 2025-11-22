<?php
class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->userModel->login($_POST['username'], $_POST['password']);
            if (isset($result['success'])) {
                header('Location: /');
                exit;
            } else {
                $error = $result['error'];
            }
        }
        require __DIR__ . '/../views/auth/login.php';
    }
    
    public function logout() {
        $this->userModel->logout();
        header('Location: /');
        exit;
    }
    
    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->userModel->register(
                $_POST['username'] ?? '',
                $_POST['email'] ?? '',
                $_POST['password'] ?? ''
            );
            
            if (isset($result['success'])) {
                // Registro exitoso - redirigir a login con mensaje
                $_SESSION['success_message'] = 'Cuenta creada exitosamente. Ahora puedes iniciar sesión.';
                header('Location: /login');
                exit;
            } else {
                // Error en el registro
                $error = $result['error'];
            }
        }
        require __DIR__ . '/../views/auth/registro.php';
    }
}
?>
