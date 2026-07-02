<?php
require_once __DIR__ . '/../config/koneksi.php';

class AdminModel extends Database {

    public function getAll() {
        $qry = "SELECT * FROM admin ORDER BY nama ASC";
        return $this->conn->query($qry);
    }

    public function getById($id) {
        $qry = "SELECT * FROM admin WHERE id = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insert($username, $password, $nama, $role) {
        $hashedPassword = hash('sha256', $password);
        $qry = "INSERT INTO admin (username, password, nama, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("ssss", $username, $hashedPassword, $nama, $role);
        return $stmt->execute();
    }

    public function update($id, $username, $password, $nama, $role) {
        if (!empty($password)) {
            $hashedPassword = hash('sha256', $password);
            $qry = "UPDATE admin SET username = ?, password = ?, nama = ?, role = ? WHERE id = ?";
            $stmt = $this->conn->prepare($qry);
            $stmt->bind_param("ssssi", $username, $hashedPassword, $nama, $role, $id);
        } else {
            $qry = "UPDATE admin SET username = ?, nama = ?, role = ? WHERE id = ?";
            $stmt = $this->conn->prepare($qry);
            $stmt->bind_param("sssi", $username, $nama, $role, $id);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $qry = "DELETE FROM admin WHERE id = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getByUsername($username) {
        $qry = "SELECT * FROM admin WHERE username = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>