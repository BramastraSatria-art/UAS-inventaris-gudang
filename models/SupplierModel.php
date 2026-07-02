<?php
require_once __DIR__ . '/../config/koneksi.php';

class SupplierModel extends Database {

    public function getAll() {
        $qry = "SELECT * FROM supplier ORDER BY nama ASC";
        return $this->conn->query($qry);
    }

    public function getById($id) {
        $qry = "SELECT * FROM supplier WHERE id = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($nama, $telepon, $alamat) {
        $qry = "INSERT INTO supplier (nama, telepon, alamat) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("sss", $nama, $telepon, $alamat);
        return $stmt->execute();
    }

    public function update($id, $nama, $telepon, $alamat) {
        $qry = "UPDATE supplier SET nama = ?, telepon = ?, alamat = ? WHERE id = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("sssi", $nama, $telepon, $alamat, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        if ($this->isDipakai($id)) {
            return false;
        }
        $qry = "DELETE FROM supplier WHERE id = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function isDipakai($id) {
        $qry = "SELECT COUNT(*) as total FROM barang WHERE id_supplier = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] > 0;
    }
}
?>