<?php
class Post {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll($limit = 10) {
        $stmt = $this->db->query("SELECT p.*, u.username as autor 
                                  FROM posts_diario p 
                                  JOIN usuarios u ON p.autor_id = u.id 
                                  ORDER BY p.fecha_publicacion DESC 
                                  LIMIT $limit");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT p.*, u.username as autor 
                                   FROM posts_diario p 
                                   JOIN usuarios u ON p.autor_id = u.id 
                                   WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function crear($datos) {
        $stmt = $this->db->prepare("INSERT INTO posts_diario 
                                    (titulo, contenido, imagen, codigo_embed, autor_id, archivos) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $datos['titulo'], $datos['contenido'], 
            $datos['imagen'] ?? null, $datos['codigo_embed'] ?? null,
            $datos['autor_id'],
            isset($datos['archivos']) ? json_encode($datos['archivos']) : null
        ]);
    }
    
    public function actualizar($id, $datos) {
        $stmt = $this->db->prepare("UPDATE posts_diario 
                                    SET titulo = ?, contenido = ?, imagen = ?, codigo_embed = ?, archivos = ?
                                    WHERE id = ?");
        return $stmt->execute([
            $datos['titulo'], $datos['contenido'],
            $datos['imagen'] ?? null, $datos['codigo_embed'] ?? null,
            isset($datos['archivos']) ? json_encode($datos['archivos']) : null,
            $id
        ]);
    }
    
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM posts_diario WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
