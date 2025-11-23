<?php
class ProyectoComentario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getByActualizacionId($actualizacionId) {
        $stmt = $this->db->prepare("SELECT c.*, u.username, u.avatar, u.id as autor_id 
                                    FROM proyecto_comentarios c 
                                    JOIN usuarios u ON c.autor_id = u.id 
                                    WHERE c.actualizacion_id = ? 
                                    ORDER BY c.fecha_creacion ASC");
        $stmt->execute([$actualizacionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPreview($actualizacionId, $limit = 2) {
        $stmt = $this->db->prepare("SELECT c.*, u.username 
                                    FROM proyecto_comentarios c 
                                    JOIN usuarios u ON c.autor_id = u.id 
                                    WHERE c.actualizacion_id = ? 
                                    ORDER BY c.fecha_creacion DESC 
                                    LIMIT ?");
        $stmt->bindValue(1, $actualizacionId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    public function crear($actualizacionId, $autorId, $contenido) {
        $stmt = $this->db->prepare("INSERT INTO proyecto_comentarios (actualizacion_id, autor_id, contenido) VALUES (?, ?, ?)");
        return $stmt->execute([$actualizacionId, $autorId, $contenido]);
    }

    public function countByActualizacion($actualizacionId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM proyecto_comentarios WHERE actualizacion_id = ?");
        $stmt->execute([$actualizacionId]);
        return $stmt->fetchColumn();
    }
}
?>
