<?php
require_once __DIR__ . '/../config/koneksi.php';

class PenjualanModel extends Database {

    private function generateKode() {
        $tanggal = date('Ymd');
        $qry = "SELECT COUNT(*) as total FROM transaksi WHERE jenis = 'penjualan' AND DATE(tanggal) = CURDATE()";
        $result = $this->conn->query($qry)->fetch_assoc();
        $urutan = str_pad($result['total'] + 1, 3, '0', STR_PAD_LEFT);
        return 'PJ-' . $tanggal . '-' . $urutan;
    }

    public function simpan($nama_pembeli, $keranjang, $id_admin) {
        $kode = $this->generateKode();
        $tanggal = date('Y-m-d H:i:s');
        $total = 0;

        foreach ($keranjang as $item) {
            $total += $item['harga'] * $item['jumlah'];
        }

        $qry = "INSERT INTO transaksi (kode, jenis, tanggal, nama_pembeli, total, id_admin) 
                VALUES (?, 'penjualan', ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("sssdi", $kode, $tanggal, $nama_pembeli, $total, $id_admin);

        if (!$stmt->execute()) {
            return false;
        }

        $id_transaksi = $this->conn->insert_id;

        foreach ($keranjang as $item) {
            $subtotal = $item['harga'] * $item['jumlah'];

            $qry = "INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, harga_satuan, subtotal) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($qry);
            $stmt->bind_param("iiidd", $id_transaksi, $item['id'], $item['jumlah'], $item['harga'], $subtotal);

            if (!$stmt->execute()) {
                return false;
            }

            $qry = "UPDATE barang SET stok = stok - ? WHERE id = ?";
            $stmt = $this->conn->prepare($qry);
            $stmt->bind_param("ii", $item['jumlah'], $item['id']);

            if (!$stmt->execute()) {
                return false;
            }
        }

        return true;
    }

    public function getAll() {
        $qry = "SELECT t.*, a.nama as nama_admin 
                FROM transaksi t 
                JOIN admin a ON t.id_admin = a.id 
                WHERE t.jenis = 'penjualan' 
                ORDER BY t.tanggal DESC";
        return $this->conn->query($qry);
    }

    public function getById($id) {
        $qry = "SELECT t.*, a.nama as nama_admin 
                FROM transaksi t 
                JOIN admin a ON t.id_admin = a.id 
                WHERE t.id = ? AND t.jenis = 'penjualan'";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getDetail($id_transaksi) {
        $qry = "SELECT dt.*, b.nama as nama_barang, b.satuan 
                FROM detail_transaksi dt 
                JOIN barang b ON dt.id_barang = b.id 
                WHERE dt.id_transaksi = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id_transaksi);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getByPeriode($dari, $sampai) {
        $qry = "SELECT t.*, a.nama as nama_admin 
                FROM transaksi t 
                JOIN admin a ON t.id_admin = a.id 
                WHERE t.jenis = 'penjualan' AND DATE(t.tanggal) BETWEEN ? AND ?
                ORDER BY t.tanggal DESC";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("ss", $dari, $sampai);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getTotalPendapatan($dari, $sampai) {
        $qry = "SELECT SUM(total) as pendapatan 
                FROM transaksi 
                WHERE jenis = 'penjualan' AND DATE(tanggal) BETWEEN ? AND ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("ss", $dari, $sampai);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['pendapatan'] ?? 0;
    }
}
?>