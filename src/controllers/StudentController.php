<?php
require_once 'models/Usuario.php';

class StudentController {

    // Comprobar que hay sesión activa y que es alumno
    private function verificarSesion(): void {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /auth/login');
            exit;
        }
        if ($_SESSION['rol'] !== 'alumno') {
            header('Location: /auth/login');
            exit;
        }
    }

    // Dashboard del alumno
    public function index(): void {
        $this->verificarSesion();
        require_once 'views/student/index.php';
    }
}