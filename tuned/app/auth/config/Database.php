<?php
class Database {
    private $host = "localhost";
    private $db = "auth_db";
    private $user = "root";
    private $pass = "";
    private $conn;

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db;charset=utf8",
                $this->user,
                $this->pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            return $this->conn;
        } catch (PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }
    }
}
