<?php
require_once 'models/Curso.php';
require_once 'models/Pregunta.php';

class CourseController {

    private Curso $curso;
    private Pregunta $pregunta;

    public function __construct() {
        $this->curso   = new Curso();
        $this->pregunta = new Pregunta();
    }

    // Verificar que hay sesión activa y que es profesor o admin
    private function verificarSesion(): void {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /auth/login');
            exit;
        }
        if (!in_array($_SESSION['rol'], ['profesor', 'admin'])) {
            header('Location: /auth/login');
            exit;
        }
    }

    // Panel principal del profesor — lista sus cursos
    public function index(): void {
        $this->verificarSesion();
        $cursos = $this->curso->obtenerPorProfesor($_SESSION['usuario_id']);
        require_once 'views/course/index.php';
    }

    // Formulario para crear un nuevo curso
    public function crear(): void {
        $this->verificarSesion();
        require_once 'views/course/crear.php';
    }

    // Procesar la creación del curso
    public function procesarCrear(): void {
        $this->verificarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->crear();
            return;
        }

        $titulo      = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $categoria   = trim($_POST['categoria'] ?? '');

        if (empty($titulo) || empty($categoria)) {
            $error = "El título y la categoría son obligatorios.";
            require_once 'views/course/crear.php';
            return;
        }

        if (!in_array($categoria, ['Historia', 'Geografia', 'Mixto'])) {
            $error = "Categoría no válida.";
            require_once 'views/course/crear.php';
            return;
        }

        $id = $this->curso->crear($titulo, $descripcion, $categoria, $_SESSION['usuario_id']);
        header('Location: /course/verCurso?id=' . $id);
        exit;
    }

    // Ver detalle de un curso con sus preguntas
    public function verCurso(): void {
        $this->verificarSesion();

        $id    = (int) ($_GET['id'] ?? 0);
        $curso = $this->curso->obtenerPorId($id);

        if (!$curso) {
            header('Location: /course/index');
            exit;
        }

        $preguntas = $this->pregunta->obtenerPorCurso($id);
        require_once 'views/course/ver.php';
    }

    // Formulario para editar un curso
    public function editar(): void {
        $this->verificarSesion();

        $id    = (int) ($_GET['id'] ?? 0);
        $curso = $this->curso->obtenerPorId($id);

        if (!$curso) {
            header('Location: /course/index');
            exit;
        }

        require_once 'views/course/editar.php';
    }

    // Procesar la edición del curso
    public function procesarEditar(): void {
        $this->verificarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /course/index');
            exit;
        }

        $id          = (int) ($_POST['id'] ?? 0);
        $titulo      = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $categoria   = trim($_POST['categoria'] ?? '');

        if (empty($titulo) || empty($categoria)) {
            $error = "El título y la categoría son obligatorios.";
            $curso = $this->curso->obtenerPorId($id);
            require_once 'views/course/editar.php';
            return;
        }

        $this->curso->actualizar($id, $titulo, $descripcion, $categoria);
        header('Location: /course/verCurso?id=' . $id);
        exit;
    }

    // Publicar o despublicar un curso
    public function togglePublicado(): void {
        $this->verificarSesion();
        $id = (int) ($_GET['id'] ?? 0);
        $this->curso->togglePublicado($id);
        header('Location: /course/verCurso?id=' . $id);
        exit;
    }

    // Eliminar un curso
    public function eliminar(): void {
        $this->verificarSesion();
        $id = (int) ($_GET['id'] ?? 0);
        $this->curso->eliminar($id);
        header('Location: /course/index');
        exit;
    }

    // Formulario para añadir una pregunta a un curso
    public function addPregunta(): void {
        $this->verificarSesion();
        $curso_id = (int) ($_GET['curso_id'] ?? 0);
        $curso    = $this->curso->obtenerPorId($curso_id);

        if (!$curso) {
            header('Location: /course/index');
            exit;
        }

        require_once 'views/course/pregunta.php';
    }

    // Procesar la creación de una pregunta
    public function procesarPregunta(): void {
        $this->verificarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /course/index');
            exit;
        }

        $curso_id          = (int) ($_POST['curso_id'] ?? 0);
        $enunciado         = trim($_POST['enunciado'] ?? '');
        $opcion_a          = trim($_POST['opcion_a'] ?? '');
        $opcion_b          = trim($_POST['opcion_b'] ?? '');
        $opcion_c          = trim($_POST['opcion_c'] ?? '');
        $opcion_d          = trim($_POST['opcion_d'] ?? '');
        $respuesta_correcta = trim($_POST['respuesta_correcta'] ?? '');
        $dificultad        = trim($_POST['dificultad'] ?? 'media');

        if (empty($enunciado) || empty($opcion_a) || empty($opcion_b) || 
            empty($opcion_c) || empty($opcion_d) || empty($respuesta_correcta)) {
            $error = "Todos los campos son obligatorios.";
            $curso = $this->curso->obtenerPorId($curso_id);
            require_once 'views/course/pregunta.php';
            return;
        }

        $this->pregunta->crear($curso_id, $enunciado, $opcion_a, $opcion_b, 
                               $opcion_c, $opcion_d, $respuesta_correcta, $dificultad);
        header('Location: /course/verCurso?id=' . $curso_id);
        exit;
    }

    // Eliminar una pregunta
    public function eliminarPregunta(): void {
        $this->verificarSesion();
        $id       = (int) ($_GET['id'] ?? 0);
        $curso_id = (int) ($_GET['curso_id'] ?? 0);
        $this->pregunta->eliminar($id);
        header('Location: /course/verCurso?id=' . $curso_id);
        exit;
    }
}