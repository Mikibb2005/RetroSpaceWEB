<?php
class Proyecto {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll($categoria = null) {
        $sql = "SELECT p.*, u.username as autor 
                FROM proyectos p 
                JOIN usuarios u ON p.autor_id = u.id";
        if ($categoria) {
            $sql .= " WHERE p.categoria = " . $this->db->quote($categoria);
        }
        $sql .= " ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT p.*, u.username as autor 
                                   FROM proyectos p 
                                   JOIN usuarios u ON p.autor_id = u.id 
                                   WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function crear($datos) {
        $stmt = $this->db->prepare("INSERT INTO proyectos 
                                    (titulo, descripcion, categoria, link1, link2, video_url, imagen, autor_id, archivos) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $datos['titulo'], $datos['descripcion'], $datos['categoria'],
            $datos['link1'] ?? null, $datos['link2'] ?? null,
            $datos['video_url'] ?? null, $datos['imagen'] ?? null,
            $datos['autor_id'],
            isset($datos['archivos']) ? json_encode($datos['archivos']) : null
        ]);
    }
    
    public function actualizar($id, $datos) {
        $stmt = $this->db->prepare("UPDATE proyectos 
                                    SET titulo = ?, descripcion = ?, categoria = ?, 
                                        link1 = ?, link2 = ?, video_url = ?, imagen = ?, archivos = ?
                                    WHERE id = ?");
        return $stmt->execute([
            $datos['titulo'], $datos['descripcion'], $datos['categoria'],
            $datos['link1'] ?? null, $datos['link2'] ?? null,
            $datos['video_url'] ?? null, $datos['imagen'] ?? null,
            isset($datos['archivos']) ? json_encode($datos['archivos']) : null,
            $id
        ]);
    }
    
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM proyectos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
