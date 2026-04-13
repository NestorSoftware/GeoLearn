<?php
require_once 'models/Usuario.php';

class AuthController {

    private Usuario $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
    }

    // Página principal: redirige según si hay sesión o no
    public function index(): void {
        if (isset($_SESSION['usuario_id'])) {
            $this->redirigirPorRol($_SESSION['rol']);
        } else {
            $this->login();
        }
    }

    // Mostrar formulario de login
    public function login(): void {
        require_once 'views/auth/login.php';
    }

    // Procesar formulario de login
    public function procesarLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->login();
            return;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validación básica
        if (empty($email) || empty($password)) {
            $error = "Por favor, rellena todos los campos.";
            require_once 'views/auth/login.php';
            return;
        }

        // Buscar usuario en la BD
        $usuario = $this->usuario->buscarPorEmail($email);

        if (!$usuario || !$this->usuario->verificarPassword($password, $usuario['password_hash'])) {
            $error = "Email o contraseña incorrectos.";
            require_once 'views/auth/login.php';
            return;
        }

        // Crear sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre']     = $usuario['nombre'];
        $_SESSION['rol']        = $usuario['rol'];

        $this->redirigirPorRol($usuario['rol']);
    }

    // Mostrar formulario de registro
    public function registro(): void {
        require_once 'views/auth/registro.php';
    }

    // Procesar formulario de registro
    public function procesarRegistro(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->registro();
            return;
        }

        $nombre   = trim($_POST['nombre'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm  = trim($_POST['confirm_password'] ?? '');

        // Validaciones
        if (empty($nombre) || empty($email) || empty($password)) {
            $error = "Por favor, rellena todos los campos.";
            require_once 'views/auth/registro.php';
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "El formato del email no es válido.";
            require_once 'views/auth/registro.php';
            return;
        }

        if ($password !== $confirm) {
            $error = "Las contraseñas no coinciden.";
            require_once 'views/auth/registro.php';
            return;
        }

        if (strlen($password) < 6) {
            $error = "La contraseña debe tener al menos 6 caracteres.";
            require_once 'views/auth/registro.php';
            return;
        }

        // Comprobar si el email ya existe
        if ($this->usuario->buscarPorEmail($email)) {
            $error = "Ya existe una cuenta con ese email.";
            require_once 'views/auth/registro.php';
            return;
        }

        // Registrar usuario
        if ($this->usuario->registrar($nombre, $email, $password)) {
            $exito = "Cuenta creada correctamente. Ya puedes iniciar sesión.";
            require_once 'views/auth/login.php';
        } else {
            $error = "Error al crear la cuenta. Inténtalo de nuevo.";
            require_once 'views/auth/registro.php';
        }
    }

    // Cerrar sesión
    public function logout(): void {
        session_destroy();
        header('Location: /auth/login');
        exit;
    }

    // Redirigir al panel según el rol del usuario
    private function redirigirPorRol(string $rol): void {
        match($rol) {
            'admin'    => header('Location: /admin/index'),
            'profesor' => header('Location: /course/index'),
            default    => header('Location: /student/index'),
        };
        exit;
    }
}