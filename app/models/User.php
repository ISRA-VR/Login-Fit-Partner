<?php
require_once __DIR__ . "/../config/database.php";

class User
{
    private $conn;
    private $table = "users";

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function register($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, edad, estatura, peso, email, password)
                  VALUES (:nombre, :edad, :estatura, :peso, :email, :password)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':edad', $data['edad']);
        $stmt->bindParam(':estatura', $data['estatura']);
        $stmt->bindParam(':peso', $data['peso']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);

        return $stmt->execute();
    }

    public function login($email)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function emailExists($email)
    {
        $query = $this->conn->prepare("SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1");
        $query->execute(['email' => $email]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
