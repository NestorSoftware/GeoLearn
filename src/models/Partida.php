<?php
class Partida {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Crear una nueva partida al empezar el juego
    public function crear(int $usuario_id, int $curso_id, int $total_preguntas): int {
        $sql  = "INSERT INTO partidas (usuario_id, curso_id, total_preguntas) 
                 VALUES (:usuario_id, :curso_id, :total_preguntas) 
                 RETURNING id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':usuario_id'      => $usuario_id,
            ':curso_id'        => $curso_id,
            ':total_preguntas' => $total_preguntas
        ]);
        return $stmt->fetchColumn();
    }

    // Guardar el resultado al terminar la partida
    public function finalizar(int $id, int $correctas, int $puntuacion): bool {
        $sql  = "UPDATE partidas 
                 SET correctas = :correctas, puntuacion = :puntuacion, completada = true 
                 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':correctas'   => $correctas,
            ':puntuacion'  => $puntuacion,
            ':id'          => $id
        ]);
    }

    // Obtener todas las partidas de un alumno
    public function obtenerPorUsuario(int $usuario_id): array {
        $sql  = "SELECT p.*, c.titulo AS titulo_curso 
                 FROM partidas p 
                 JOIN cursos c ON p.curso_id = c.id 
                 WHERE p.usuario_id = :usuario_id AND p.completada = true 
                 ORDER BY p.jugada_en DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);
        return $stmt->fetchAll();
    }

    // Obtener la mejor puntuacion de un alumno
    public function mejorPuntuacion(int $usuario_id): int {
        $sql  = "SELECT COALESCE(MAX(puntuacion), 0) 
                 FROM partidas 
                 WHERE usuario_id = :usuario_id AND completada = true";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);
        return (int) $stmt->fetchColumn();
    }

    // Contar partidas jugadas por un alumno
    public function contarPorUsuario(int $usuario_id): int {
        $sql  = "SELECT COUNT(*) 
                 FROM partidas 
                 WHERE usuario_id = :usuario_id AND completada = true";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);
        return (int) $stmt->fetchColumn();
    }

    // Obtener todas las partidas (para el admin)
    public function obtenerTodas(): array {
        $sql  = "SELECT p.*, u.nombre AS nombre_usuario, c.titulo AS titulo_curso 
                 FROM partidas p 
                 JOIN usuarios u ON p.usuario_id = u.id 
                 JOIN cursos c ON p.curso_id = c.id 
                 WHERE p.completada = true 
                 ORDER BY p.jugada_en DESC 
                 LIMIT 50";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}