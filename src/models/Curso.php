<?php
class Curso {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener todos los cursos (para el admin)
    public function obtenerTodos(): array {
        $sql  = "SELECT c.*, u.nombre AS nombre_profesor 
                 FROM cursos c 
                 LEFT JOIN usuarios u ON c.profesor_id = u.id 
                 ORDER BY c.creado_en DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener cursos de un profesor concreto
    public function obtenerPorProfesor(int $profesor_id): array {
        $sql  = "SELECT * FROM cursos WHERE profesor_id = :profesor_id 
                 ORDER BY creado_en DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':profesor_id' => $profesor_id]);
        return $stmt->fetchAll();
    }

    // Obtener cursos publicados (para los alumnos)
    public function obtenerPublicados(): array {
        $sql  = "SELECT c.*, u.nombre AS nombre_profesor 
                 FROM cursos c 
                 LEFT JOIN usuarios u ON c.profesor_id = u.id 
                 WHERE c.publicado = true 
                 ORDER BY c.creado_en DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener un curso por ID
    public function obtenerPorId(int $id): array|false {
        $sql  = "SELECT * FROM cursos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Crear un nuevo curso
    public function crear(string $titulo, string $descripcion, string $categoria, int $profesor_id): int {
        $sql  = "INSERT INTO cursos (titulo, descripcion, categoria, profesor_id) 
                 VALUES (:titulo, :descripcion, :categoria, :profesor_id) 
                 RETURNING id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':titulo'       => $titulo,
            ':descripcion'  => $descripcion,
            ':categoria'    => $categoria,
            ':profesor_id'  => $profesor_id
        ]);
        return $stmt->fetchColumn();
    }

    // Actualizar un curso existente
    public function actualizar(int $id, string $titulo, string $descripcion, string $categoria): bool {
        $sql  = "UPDATE cursos SET titulo = :titulo, descripcion = :descripcion, 
                 categoria = :categoria WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titulo'      => $titulo,
            ':descripcion' => $descripcion,
            ':categoria'   => $categoria,
            ':id'          => $id
        ]);
    }

    // Publicar o despublicar un curso
    public function togglePublicado(int $id): bool {
        $sql  = "UPDATE cursos SET publicado = NOT publicado WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Eliminar un curso
    public function eliminar(int $id): bool {
        $sql  = "DELETE FROM cursos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
