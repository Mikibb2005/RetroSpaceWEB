<?php
/**
 * Modelo MensajeContacto
 * Gestiona los mensajes de contacto enviados por usuarios
 */

class MensajeContacto {
    
    /**
     * Crear nuevo mensaje de contacto
     */
    public static function crear($db, $datos) {
        $query = "INSERT INTO mensajes_contacto 
                  (nombre, email, asunto, mensaje, ip_address, user_agent) 
                  VALUES (:nombre, :email, :asunto, :mensaje, :ip, :user_agent)";
        
        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':email', $datos['email']);
            $stmt->bindParam(':asunto', $datos['asunto']);
            $stmt->bindParam(':mensaje', $datos['mensaje']);
            $stmt->bindParam(':ip', $datos['ip_address']);
            $stmt->bindParam(':user_agent', $datos['user_agent']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al crear mensaje de contacto: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener todos los mensajes (para panel admin)
     */
    public static function obtenerTodos($db, $limite = 50, $offset = 0) {
        $query = "SELECT * FROM mensajes_contacto 
                  ORDER BY fecha DESC 
                  LIMIT :limite OFFSET :offset";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener mensaje por ID
     */
    public static function obtenerPorId($db, $id) {
        $query = "SELECT * FROM mensajes_contacto WHERE id = :id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Marcar mensaje como leído
     */
    public static function marcarComoLeido($db, $id) {
        $query = "UPDATE mensajes_contacto SET leido = TRUE WHERE id = :id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Marcar mensaje como respondido
     */
    public static function marcarComoRespondido($db, $id) {
        $query = "UPDATE mensajes_contacto 
                  SET respondido = TRUE, leido = TRUE 
                  WHERE id = :id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Contar mensajes no leídos
     */
    public static function contarNoLeidos($db) {
        $query = "SELECT COUNT(*) as total FROM mensajes_contacto WHERE leido = FALSE";
        
        $stmt = $db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] ?? 0;
    }
    
    /**
     * Validar datos del formulario
     */
    public static function validar($datos) {
        $errores = [];
        
        // Nombre
        if (empty($datos['nombre'])) {
            $errores['nombre'] = 'El nombre es obligatorio';
        } elseif (strlen($datos['nombre']) < 2) {
            $errores['nombre'] = 'El nombre debe tener al menos 2 caracteres';
        } elseif (strlen($datos['nombre']) > 100) {
            $errores['nombre'] = 'El nombre no puede exceder 100 caracteres';
        }
        
        // Email
        if (empty($datos['email'])) {
            $errores['email'] = 'El email es obligatorio';
        } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'El email no es válido';
        } elseif (strlen($datos['email']) > 150) {
            $errores['email'] = 'El email no puede exceder 150 caracteres';
        }
        
        // Asunto
        if (empty($datos['asunto'])) {
            $errores['asunto'] = 'El asunto es obligatorio';
        } elseif (strlen($datos['asunto']) < 3) {
            $errores['asunto'] = 'El asunto debe tener al menos 3 caracteres';
        } elseif (strlen($datos['asunto']) > 200) {
            $errores['asunto'] = 'El asunto no puede exceder 200 caracteres';
        }
        
        // Mensaje
        if (empty($datos['mensaje'])) {
            $errores['mensaje'] = 'El mensaje es obligatorio';
        } elseif (strlen($datos['mensaje']) < 10) {
            $errores['mensaje'] = 'El mensaje debe tener al menos 10 caracteres';
        } elseif (strlen($datos['mensaje']) > 5000) {
            $errores['mensaje'] = 'El mensaje no puede exceder 5000 caracteres';
        }
        
        return $errores;
    }
    
    /**
     * Sanitizar datos
     */
    public static function sanitizar($datos) {
        return [
            'nombre' => trim(strip_tags($datos['nombre'] ?? '')),
            'email' => trim(strip_tags($datos['email'] ?? '')),
            'asunto' => trim(strip_tags($datos['asunto'] ?? '')),
            'mensaje' => trim($datos['mensaje'] ?? ''),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500)
        ];
    }
}
