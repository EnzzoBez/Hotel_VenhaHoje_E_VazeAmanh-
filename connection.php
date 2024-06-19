<?php
class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = ""; 
    private $dbname = "hotel_reservation"; 

    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Falha na conexão: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        $this->conn->close();
    }
}
$database = new Database();
$conn = $database->getConnection();