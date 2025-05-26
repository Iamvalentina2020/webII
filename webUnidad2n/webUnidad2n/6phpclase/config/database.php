<?php
class Database {
    private $host = "localhost";
    private $database_name = "doguitodb";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database_name);
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch(Exception $e) {
            echo "Database could not be connected: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>
