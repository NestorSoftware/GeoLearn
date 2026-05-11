<?php
require_once 'models/Usuario.php';
require_once 'models/Curso.php';
require_once 'models/Partida.php';

class AdminController {

    private Usuario $usuario;
    private Curso $curso;
    private Partida $partida;

    public function __construct() {
        $this->usuario = new Usuario();
        $this->curso   = new Curso();
        $this->partida = new Partida();
    }

    // Comprobar que hay sesion activa y que es admin
    private function verificarSesion(): void {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /auth/login');
            exit;
        }
        if ($_SESSION['rol'] !== 'admin') {
            header('Location: /auth/login');
            exit;
        }
    }

    // Dashboard del admin con estadisticas globales
    public function index(): void {
        $this->verificarSesion();
        $usuarios  = $this->usuario->obtenerTodos();
        $cursos    = $this->curso->obtenerTodos();
        $partidas  = $this->partida->obtenerTodas();
        require_once 'views/admin/index.php';
    }
}