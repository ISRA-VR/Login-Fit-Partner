<?php

class Database {
    private $host = "localhost";
    private $dbname = "fitness_app";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new RuntimeException('No fue posible conectar con la base de datos.', 0, $e);
        }

        return $this->conn;
    }
}
