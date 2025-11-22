<?php
class HomeController {
    private $postModel;
    private $proyectoModel;
    
    public function __construct() {
        $this->postModel = new Post();
        $this->proyectoModel = new Proyecto();
    }
    
    public function index() {
        $datos = [
            'ultimos_posts' => $this->postModel->getAll(3),
            'proyectos_destacados' => $this->proyectoModel->getAll(null, 3),
            'estadisticas' => [
                'usuarios' => 42,
                'posts' => 128,
                'proyectos' => 15
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
