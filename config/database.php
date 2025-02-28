<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'base_de_datos_mg';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>
