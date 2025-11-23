<?php
class ProyectoActualizacion {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getByProyectoId($proyectoId) {
        $stmt = $this->db->prepare("SELECT * FROM proyecto_actualizaciones WHERE proyecto_id = ? ORDER BY fecha_creacion DESC");
        $stmt->execute([$proyectoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM proyecto_actualizaciones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function crear($proyectoId, $titulo, $contenido, $archivos = null) {
        $stmt = $this->db->prepare("INSERT INTO proyecto_actualizaciones (proyecto_id, titulo, contenido, archivos) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$proyectoId, $titulo, $contenido, $archivos]);
    }
    
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM proyecto_actualizaciones WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
