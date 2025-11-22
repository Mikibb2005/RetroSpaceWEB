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
    
    public function crear($datos) {
        $stmt = $this->db->prepare("INSERT INTO proyectos 
                                    (titulo, descripcion, categoria, link1, link2, video_url, imagen, autor_id) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $datos['titulo'], $datos['descripcion'], $datos['categoria'],
            $datos['link1'] ?? null, $datos['link2'] ?? null,
            $datos['video_url'] ?? null, $datos['imagen'] ?? null,
            $datos['autor_id']
        ]);
    }
}
?>
