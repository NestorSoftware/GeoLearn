<?php
class Database {
    private static $instance = null;
    
    private string $host;
    private string $port;
    private string $dbname;
    private string $user;
    private string $pass;

    private function __construct() {
        // Leer variables de entorno definidas en docker-compose.yml
        $this->host   = getenv('DB_HOST') ?: 'db';
        $this->port   = getenv('DB_PORT') ?: '5432';
        $this->dbname = getenv('DB_NAME') ?: 'geolearn';
        $this->user   = getenv('DB_USER') ?: 'geolearn_user';
        $this->pass   = getenv('DB_PASS') ?: 'geolearn_pass';
    }

    // Patrón Singleton: solo existe una conexión a la BD
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $db = new Database();
            $dsn = "pgsql:host={$db->host};port={$db->port};dbname={$db->dbname}";
            try {
                self::$instance = new PDO($dsn, $db->user, $db->pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                die(json_encode(['error' => 'Error de conexión a la base de datos.']));
            }
        }
        return self::$instance;
    }
}