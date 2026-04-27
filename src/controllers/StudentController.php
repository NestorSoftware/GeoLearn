<?php
require_once 'models/Usuario.php';
require_once 'models/Curso.php';

class StudentController {

    private Curso $curso;

    public function __construct() {
        $this->curso = new Curso();
    }

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

    // Dashboard del alumno con cursos publicados
    public function index(): void {
        $this->verificarSesion();
        $cursos = $this->curso->obtenerPublicados();
        require_once 'views/student/index.php';
    }
}