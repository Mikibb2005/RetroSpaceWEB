<?php
class HomeController {
    private $postModel;
    private $proyectoModel;
    private $userModel;
    private $foroHiloModel;
    private $db;
    
    public function __construct() {
        $this->postModel = new Post();
        $this->proyectoModel = new Proyecto();
        $this->userModel = new User();
        $this->foroHiloModel = new ForoHilo();
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function index() {
        // Obtener estadísticas reales con manejo de errores
        try {
            $stmtUsuarios = $this->db->query("SELECT COUNT(*) FROM usuarios");
            $totalUsuarios = $stmtUsuarios->fetchColumn();
        } catch (PDOException $e) {
            $totalUsuarios = 0;
        }
        
        try {
            $stmtPosts = $this->db->query("SELECT COUNT(*) FROM posts_diario");
            $totalPosts = $stmtPosts->fetchColumn();
        } catch (PDOException $e) {
            $totalPosts = 0;
        }
        
        try {
            $stmtProyectos = $this->db->query("SELECT COUNT(*) FROM proyectos");
            $totalProyectos = $stmtProyectos->fetchColumn();
        } catch (PDOException $e) {
            $totalProyectos = 0;
        }
        
        try {
            $stmtHilos = $this->db->query("SELECT COUNT(*) FROM foro_hilos");
            $totalHilos = $stmtHilos->fetchColumn();
        } catch (PDOException $e) {
            $totalHilos = 0;
        }
        
        try {
            $stmtComentarios = $this->db->query("SELECT COUNT(*) FROM foro_comentarios");
            $totalComentarios = $stmtComentarios->fetchColumn();
        } catch (PDOException $e) {
            $totalComentarios = 0;
        }
        
        // Obtener últimos hilos del foro
        try {
            $stmtUltimosHilos = $this->db->query("
                SELECT h.*, u.username as autor,
                       (SELECT COUNT(*) FROM foro_comentarios WHERE hilo_id = h.id) as total_comentarios
                FROM foro_hilos h
                JOIN usuarios u ON h.autor_id = u.id
                ORDER BY h.fecha_creacion DESC
                LIMIT 6
            ");
            $ultimosHilos = $stmtUltimosHilos->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $ultimosHilos = [];
        }
        
        // Obtener últimos posts del blog
        try {
            $ultimosPosts = $this->postModel->getAll(3);
        } catch (Exception $e) {
            $ultimosPosts = [];
        }
        
        // Obtener proyectos destacados
        try {
            $proyectosDestacados = $this->proyectoModel->getAll(null, 3);
        } catch (Exception $e) {
            $proyectosDestacados = [];
        }
        
        $datos = [
            'ultimos_posts' => $ultimosPosts,
            'ultimos_hilos' => $ultimosHilos,
            'proyectos_destacados' => $proyectosDestacados,
            'estadisticas' => [
                'usuarios' => $totalUsuarios,
                'hilos' => $totalHilos,
                'comentarios' => $totalComentarios,
                'posts' => $totalPosts,
                'proyectos' => $totalProyectos
            ],
            'ultimo_video' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'videos_populares' => [
                ['id' => 'video1', 'titulo' => 'Mi proyecto más visto'],
                ['id' => 'video2', 'titulo' => 'Último tutorial']
            ]
        ];
        
        require __DIR__ . '/../views/home/index.php';
    }
}
?>
