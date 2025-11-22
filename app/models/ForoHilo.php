<?php
class ForoHilo {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT h.*, u.username as autor,
                                  (SELECT COUNT(*) FROM foro_comentarios WHERE hilo_id = h.id) as comentarios
                                  FROM foro_hilos h
                                  JOIN usuarios u ON h.autor_id = u.id
                                  ORDER BY h.sticky DESC, h.fecha_creacion DESC");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT h.*, u.username as autor, u.id as autor_id 
                                   FROM foro_hilos h 
                                   JOIN usuarios u ON h.autor_id = u.id 
                                   WHERE h.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function crear($datos) {
        $stmt = $this->db->prepare("INSERT INTO foro_hilos 
                                    (titulo, descripcion, categoria, autor_id) 
                                    VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $datos['titulo'], $datos['descripcion'], 
            $datos['categoria'], $datos['autor_id']
        ]);
    }
}
?>
