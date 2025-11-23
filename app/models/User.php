<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['esta_bloqueado']) {
                return ['error' => 'Usuario bloqueado'];
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['rol'] = $user['rol'];
            return ['success' => true];
        }
        return ['error' => 'Credenciales inválidas'];
    }
    
    public function logout() {
        session_destroy();
    }
    
    public function isAdmin() {
        return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
    }
    
    public function isLogged() {
        return isset($_SESSION['user_id']);
    }
    
    public function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    public function register($username, $email, $password) {
        // Validación básica
        if (empty($username) || empty($email) || empty($password)) {
            return ['error' => 'Todos los campos son obligatorios'];
        }
        
        // Validar longitud de username
        if (strlen($username) < 3 || strlen($username) > 50) {
            return ['error' => 'El nombre de usuario debe tener entre 3 y 50 caracteres'];
        }
        
        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'El email no es válido'];
        }
        
        // Validar longitud de contraseña
        if (strlen($password) < 6) {
            return ['error' => 'La contraseña debe tener al menos 6 caracteres'];
        }
        
        // Verificar si el username ya existe
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            return ['error' => 'El nombre de usuario ya está en uso'];
        }
        
        // Verificar si el email ya existe
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['error' => 'El email ya está registrado'];
        }
        
        // Hash de la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insertar nuevo usuario
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO usuarios (username, email, password_hash, rol) VALUES (?, ?, ?, 'user')"
            );
            $stmt->execute([$username, $email, $passwordHash]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['error' => 'Error al crear la cuenta. Inténtalo de nuevo.'];
        }
    }
    
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT id, username, email, nombre_real, rol, fecha_registro, biografia, avatar, etiquetas_so FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($userId, $nombreReal, $biografia, $avatar, $etiquetasSo = null) {
        $sql = "UPDATE usuarios SET nombre_real = ?, biografia = ?, avatar = ?";
        $params = [$nombreReal, $biografia, $avatar];
        
        if ($etiquetasSo !== null) {
            $sql .= ", etiquetas_so = ?";
            $params[] = $etiquetasSo;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $userId;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function follow($followerId, $followedId) {
        if ($followerId == $followedId) return false;
        try {
            $stmt = $this->db->prepare("INSERT INTO seguidores (seguidor_id, seguido_id) VALUES (?, ?)");
            return $stmt->execute([$followerId, $followedId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function unfollow($followerId, $followedId) {
        $stmt = $this->db->prepare("DELETE FROM seguidores WHERE seguidor_id = ? AND seguido_id = ?");
        return $stmt->execute([$followerId, $followedId]);
    }

    public function isFollowing($followerId, $followedId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM seguidores WHERE seguidor_id = ? AND seguido_id = ?");
        $stmt->execute([$followerId, $followedId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getFollowers($userId) {
        $stmt = $this->db->prepare("
            SELECT u.id, u.username, u.avatar 
            FROM usuarios u 
            JOIN seguidores s ON u.id = s.seguidor_id 
            WHERE s.seguido_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getFollowing($userId) {
        $stmt = $this->db->prepare("
            SELECT u.id, u.username, u.avatar 
            FROM usuarios u 
            JOIN seguidores s ON u.id = s.seguido_id 
            WHERE s.seguidor_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getUserActivity($userId) {
        $stmt = $this->db->prepare("
            (SELECT 'thread' as type, id, id as hilo_id, titulo as content, fecha_creacion as date 
             FROM foro_hilos WHERE autor_id = ?)
            UNION
            (SELECT 'comment' as type, fc.id, fc.hilo_id, fc.contenido as content, fc.fecha_publicacion as date 
             FROM foro_comentarios fc WHERE fc.autor_id = ?)
            ORDER BY date DESC LIMIT 20
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
