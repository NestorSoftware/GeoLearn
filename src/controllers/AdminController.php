<?php
require_once 'models/Usuario.php';

class AdminController {

    private Usuario $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
    }

    // Comprobar que hay sesión activa y que es admin
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

    // Dashboard del admin con lista de usuarios
    public function index(): void {
        $this->verificarSesion();
        $usuarios = $this->usuario->obtenerTodos();
        require_once 'views/admin/index.php';
    }
}