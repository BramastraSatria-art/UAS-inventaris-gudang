<?php
class Database {
    protected $conn;

    public function __construct() {
        $this->connect();
    }

    protected function connect() {
        $host = "localhost";
        $user = "root";
        $pass = "";
        $db   = "db_inventaris_gudang_uas";

        $this->conn = new mysqli($host, $user, $pass, $db);

        if ($this->conn->connect_error) {
            die("Koneksi Error: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }
    
    public function getConn() {
    return $this->conn;
}
}
?>