<?php
class Comentario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getByHilo($hilo_id) {
        $stmt = $this->db->prepare("SELECT c.*, u.username as autor, u.id as autor_id 
                                   FROM foro_comentarios c 
                                   JOIN usuarios u ON c.autor_id = u.id 
                                   WHERE c.hilo_id = ? 
                                   ORDER BY c.fecha_publicacion ASC");
        $stmt->execute([$hilo_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function crear($datos) {
        $stmt = $this->db->prepare("INSERT INTO foro_comentarios 
                                    (contenido, autor_id, hilo_id, parent_id) 
                                    VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $datos['contenido'], $datos['autor_id'], 
            $datos['hilo_id'], $datos['parent_id'] ?? null
        ]);
    }
    
    public function buildTree($comments, $parent_id = null) {
        $tree = [];
        foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parent_id) {
                $children = $this->buildTree($comments, $comment['id']);
                if ($children) {
                    $comment['children'] = $children;
                }
                $tree[] = $comment;
            }
        }
        return $tree;
    }
}
?>
