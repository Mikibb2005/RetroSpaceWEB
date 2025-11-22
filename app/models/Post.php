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
                                    (titulo, contenido, imagen, codigo_embed, autor_id) 
                                    VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $datos['titulo'], $datos['contenido'], 
            $datos['imagen'] ?? null, $datos['codigo_embed'] ?? null,
            $datos['autor_id']
        ]);
    }
}
?>
