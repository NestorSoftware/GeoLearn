<?php
require_once 'models/Usuario.php';
require_once 'models/Curso.php';
require_once 'models/Partida.php';

class StudentController {

    private Curso $curso;
    private Partida $partida;

    public function __construct() {
        $this->curso   = new Curso();
        $this->partida = new Partida();
    }

    // Comprobar que hay sesion activa y que es alumno
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

    // Dashboard del alumno con cursos publicados y estadisticas
    public function index(): void {
        $this->verificarSesion();
        $cursos          = $this->curso->obtenerPublicados();
        $partidas_jugadas = $this->partida->contarPorUsuario($_SESSION['usuario_id']);
        $mejor_puntuacion = $this->partida->mejorPuntuacion($_SESSION['usuario_id']);
        require_once 'views/student/index.php';
    }
}