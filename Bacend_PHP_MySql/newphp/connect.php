<?php
class Database {
    private $host = "127.0.0.1";  // or "localhost"
    private $port = "3306";       // your custom MySQL port
    private $db_name = "noteapp";  // database name
    private $username = "root";   // MySQL user
    private $password = "";       // leave empty if no password
    private $conn;

    // Get database connection
    public function connect(): PDO {
        $this->conn = null;
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            // Error handling mode
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("❌ Connection failed: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>