<?php
require_once 'models/Curso.php';
require_once 'models/Pregunta.php';
require_once 'models/Partida.php';

class GameController {

    private Curso $curso;
    private Pregunta $pregunta;
    private Partida $partida;

    public function __construct() {
        $this->curso    = new Curso();
        $this->pregunta = new Pregunta();
        $this->partida  = new Partida();
    }

    // Verificar que hay sesion activa y que es alumno
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

    // Pantalla de juego — carga el curso y las preguntas
    public function jugar(): void {
        $this->verificarSesion();

        $curso_id = (int) ($_GET['curso_id'] ?? 0);
        $curso    = $this->curso->obtenerPorId($curso_id);

        if (!$curso || !$curso['publicado']) {
            header('Location: /student/index');
            exit;
        }

        $preguntas = $this->pregunta->obtenerAleatorias($curso_id, 10);

        if (empty($preguntas)) {
            header('Location: /student/index');
            exit;
        }

        // Crear la partida en la BD al empezar
        $partida_id = $this->partida->crear(
            $_SESSION['usuario_id'],
            $curso_id,
            count($preguntas)
        );

        // Guardar preguntas y partida en sesion para el juego
        $_SESSION['partida_id'] = $partida_id;
        $_SESSION['preguntas']  = $preguntas;
        $_SESSION['curso_id']   = $curso_id;

        require_once 'views/student/jugar.php';
    }

    // API REST — devuelve preguntas en JSON para el fetch() de JS
    public function getPreguntasApi(): void {
        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        $preguntas = $_SESSION['preguntas'] ?? [];

        // Eliminar la respuesta correcta antes de enviar al cliente
        $preguntasSanitizadas = array_map(function($p) {
            return [
                'id'        => $p['id'],
                'enunciado' => $p['enunciado'],
                'opcion_a'  => $p['opcion_a'],
                'opcion_b'  => $p['opcion_b'],
                'opcion_c'  => $p['opcion_c'],
                'opcion_d'  => $p['opcion_d'],
                'dificultad' => $p['dificultad']
            ];
        }, $preguntas);

        echo json_encode($preguntasSanitizadas);
    }

    // API REST — recibe respuestas y devuelve resultado en JSON
    public function enviarRespuestas(): void {
        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        $input     = json_decode(file_get_contents('php://input'), true);
        $respuestas = $input['respuestas'] ?? [];
        $preguntas  = $_SESSION['preguntas'] ?? [];
        $partida_id = $_SESSION['partida_id'] ?? 0;

        if (empty($preguntas) || !$partida_id) {
            echo json_encode(['error' => 'Sesion de juego no encontrada']);
            exit;
        }

        // Corregir respuestas
        $correctas  = 0;
        $resultados = [];

        foreach ($preguntas as $p) {
            $id_pregunta      = $p['id'];
            $respuesta_dada   = $respuestas[$id_pregunta] ?? null;
            $respuesta_correcta = $p['respuesta_correcta'];
            $es_correcta      = ($respuesta_dada === $respuesta_correcta);

            if ($es_correcta) $correctas++;

            $resultados[] = [
                'id'                 => $id_pregunta,
                'enunciado'          => $p['enunciado'],
                'respuesta_dada'     => $respuesta_dada,
                'respuesta_correcta' => $respuesta_correcta,
                'es_correcta'        => $es_correcta
            ];
        }

        // Calcular puntuacion sobre 100
        $total      = count($preguntas);
        $puntuacion = $total > 0 ? (int) round(($correctas / $total) * 100) : 0;

        // Guardar resultado en la BD
        $this->partida->finalizar($partida_id, $correctas, $puntuacion);

        // Limpiar sesion de juego
        unset($_SESSION['partida_id'], $_SESSION['preguntas'], $_SESSION['curso_id']);

        echo json_encode([
            'correctas'   => $correctas,
            'total'       => $total,
            'puntuacion'  => $puntuacion,
            'resultados'  => $resultados
        ]);
    }

    // Pantalla de resultado tras terminar la partida
    public function resultado(): void {
        $this->verificarSesion();
        require_once 'views/student/resultado.php';
    }
}