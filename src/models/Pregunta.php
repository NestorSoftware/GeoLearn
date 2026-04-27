<?php
class Pregunta {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener todas las preguntas de un curso
    public function obtenerPorCurso(int $curso_id): array {
        $sql  = "SELECT * FROM preguntas WHERE curso_id = :curso_id ORDER BY id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':curso_id' => $curso_id]);
        return $stmt->fetchAll();
    }

    // Obtener una pregunta por ID
    public function obtenerPorId(int $id): array|false {
        $sql  = "SELECT * FROM preguntas WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Obtener preguntas aleatorias de un curso para el trivial
    public function obtenerAleatorias(int $curso_id, int $cantidad = 10): array {
        $sql  = "SELECT * FROM preguntas WHERE curso_id = :curso_id 
                 ORDER BY RANDOM() LIMIT :cantidad";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':curso_id', $curso_id, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Crear una nueva pregunta
    public function crear(int $curso_id, string $enunciado, string $opcion_a, string $opcion_b, 
                          string $opcion_c, string $opcion_d, string $respuesta_correcta, 
                          string $dificultad): bool {
        $sql  = "INSERT INTO preguntas 
                 (curso_id, enunciado, opcion_a, opcion_b, opcion_c, opcion_d, respuesta_correcta, dificultad)
                 VALUES (:curso_id, :enunciado, :opcion_a, :opcion_b, :opcion_c, :opcion_d, 
                         :respuesta_correcta, :dificultad)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':curso_id'           => $curso_id,
            ':enunciado'          => $enunciado,
            ':opcion_a'           => $opcion_a,
            ':opcion_b'           => $opcion_b,
            ':opcion_c'           => $opcion_c,
            ':opcion_d'           => $opcion_d,
            ':respuesta_correcta' => $respuesta_correcta,
            ':dificultad'         => $dificultad
        ]);
    }

    // Eliminar una pregunta
    public function eliminar(int $id): bool {
        $sql  = "DELETE FROM preguntas WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Contar preguntas de un curso
    public function contarPorCurso(int $curso_id): int {
        $sql  = "SELECT COUNT(*) FROM preguntas WHERE curso_id = :curso_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':curso_id' => $curso_id]);
        return (int) $stmt->fetchColumn();
    }
}