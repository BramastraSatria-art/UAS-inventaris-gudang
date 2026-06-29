<?php
require_once __DIR__ . '/../config/koneksi.php';

class KategoriModel extends Database {

    public function getAll() {
        $qry = "SELECT * FROM kategori ORDER BY nama ASC";
        return $this->conn->query($qry);
    }

    public function getById($id) {
        $qry = "SELECT * FROM kategori WHERE id = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($nama, $keterangan) {
        $qry = "INSERT INTO kategori (nama, keterangan) VALUES (?, ?)";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("ss", $nama, $keterangan);
        return $stmt->execute();
    }

    public function update($id, $nama, $keterangan) {
        $qry = "UPDATE kategori SET nama = ?, keterangan = ? WHERE id = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("ssi", $nama, $keterangan, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        // Cek apakah kategori masih dipakai barang
        $cek = "SELECT COUNT(*) as total FROM barang WHERE id_kategori = ?";
        $stmt = $this->conn->prepare($cek);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result['total'] > 0) {
            return false;
        }

        $qry = "DELETE FROM kategori WHERE id = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function isDipakai($id) {
        $qry = "SELECT COUNT(*) as total FROM barang WHERE id_kategori = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] > 0;
    }
}
?>