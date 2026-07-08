<?php
require_once __DIR__ . '/../config/koneksi.php';

class LaporanModel extends Database {

    public function getLaporanStok() {
        $qry = "SELECT b.*, k.nama as nama_kategori 
                FROM barang b 
                LEFT JOIN kategori k ON b.id_kategori = k.id 
                ORDER BY b.stok ASC";
        return $this->conn->query($qry);
    }

    public function getLaporanTransaksi($jenis, $dari, $sampai) {
        $qry = "SELECT t.*, a.nama as nama_admin 
                FROM transaksi t 
                JOIN admin a ON t.id_admin = a.id 
                WHERE t.jenis = ? AND DATE(t.tanggal) BETWEEN ? AND ?
                ORDER BY t.tanggal DESC";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("sss", $jenis, $dari, $sampai);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getLaporanPenjualan($dari, $sampai) {
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

    public function getDetailPenjualan($id_transaksi) {
        $qry = "SELECT dt.*, b.nama as nama_barang, b.satuan 
                FROM detail_transaksi dt 
                JOIN barang b ON dt.id_barang = b.id 
                WHERE dt.id_transaksi = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("i", $id_transaksi);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getStatistikHarian() {
        $qry = "SELECT DATE(tanggal) as tanggal, 
                SUM(CASE WHEN jenis = 'penjualan' THEN total ELSE 0 END) as total_penjualan,
                COUNT(CASE WHEN jenis = 'masuk' THEN 1 END) as total_masuk,
                COUNT(CASE WHEN jenis = 'keluar' THEN 1 END) as total_keluar
                FROM transaksi 
                WHERE DATE(tanggal) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(tanggal)
                ORDER BY tanggal ASC";
        return $this->conn->query($qry);
    }
}
?>