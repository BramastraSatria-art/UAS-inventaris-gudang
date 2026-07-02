<?php
require_once __DIR__ . '/../config/koneksi.php';

class BarangModel extends Database {

    public function getAll() {
        $qry = "SELECT b.*, k.nama as nama_kategori, s.nama as nama_supplier 
                FROM barang b 
                LEFT JOIN kategori k ON b.id_kategori = k.id 
                LEFT JOIN supplier s ON b.id_supplier = s.id 
                ORDER BY b.nama ASC";
        return $this->conn->query($qry);
    }

    public function getById($id) {
        $qry = "SELECT b.*, k.nama as nama_kategori, s.nama as nama_supplier 
                FROM barang b 
                LEFT JOIN kategori k ON b.id_kategori = k.id 
                LEFT JOIN supplier s ON b.id_supplier = s.id 
                WHERE b.id = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insert($id_kategori, $id_supplier, $nama, $stok, $satuan, $harga_beli, $harga_jual, $foto, $keterangan) {
        $qry = "INSERT INTO barang (id_kategori, id_supplier, nama, stok, satuan, harga_beli, harga_jual, foto, keterangan) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("iisisddss", $id_kategori, $id_supplier, $nama, $stok, $satuan, $harga_beli, $harga_jual, $foto, $keterangan);
        return $stmt->execute();
    }

    public function update($id, $id_kategori, $id_supplier, $nama, $stok, $satuan, $harga_beli, $harga_jual, $foto, $keterangan) {
        if (!empty($foto)) {
            $qry = "UPDATE barang SET id_kategori = ?, id_supplier = ?, nama = ?, stok = ?, satuan = ?, harga_beli = ?, harga_jual = ?, foto = ?, keterangan = ? WHERE id = ?";
            $stmt = $this->conn->prepare($qry);
            $stmt->bind_param("iisisddssi", $id_kategori, $id_supplier, $nama, $stok, $satuan, $harga_beli, $harga_jual, $foto, $keterangan, $id);
        } else {
            $qry = "UPDATE barang SET id_kategori = ?, id_supplier = ?, nama = ?, stok = ?, satuan = ?, harga_beli = ?, harga_jual = ?, keterangan = ? WHERE id = ?";
            $stmt = $this->conn->prepare($qry);
            $stmt->bind_param("iisisddsi", $id_kategori, $id_supplier, $nama, $stok, $satuan, $harga_beli, $harga_jual, $keterangan, $id);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $barang = $this->getById($id);
        if ($barang['foto']) {
            $path = __DIR__ . '/../assets/uploads/' . $barang['foto'];
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $qry = "DELETE FROM barang WHERE id = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getStokMenipis() {
        $qry = "SELECT * FROM barang WHERE stok <= 20 AND stok > 0 ORDER BY stok ASC";
        return $this->conn->query($qry);
    }

    public function getStokHabis() {
        $qry = "SELECT * FROM barang WHERE stok = 0";
        return $this->conn->query($qry);
    }
}
?>