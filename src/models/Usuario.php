<?php
class Usuario {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Registrar un nuevo usuario en la BD
    public function registrar(string $nombre, string $email, string $password): bool {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql  = "INSERT INTO usuarios (nombre, email, password_hash, rol)
                 VALUES (:nombre, :email, :hash, 'alumno')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':email'  => $email,
            ':hash'   => $hash
        ]);
    }

    // Buscar usuario por email para el login
    public function buscarPorEmail(string $email): array|false {
        $sql  = "SELECT * FROM usuarios WHERE email = :email AND activo = true";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // Buscar usuario por ID
    public function buscarPorId(int $id): array|false {
        $sql  = "SELECT id, nombre, email, rol, activo, creado_en
                 FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Obtener todos los usuarios (para el panel de administración)
    public function obtenerTodos(): array {
        $sql  = "SELECT id, nombre, email, rol, activo, creado_en
                 FROM usuarios ORDER BY creado_en DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Desactivar usuario sin borrar de la BD
    public function desactivar(int $id): bool {
        $sql  = "UPDATE usuarios SET activo = false WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Verificar contraseña en el login
    public function verificarPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}