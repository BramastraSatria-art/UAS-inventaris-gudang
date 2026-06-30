<?php
require_once __DIR__ . '/../config/koneksi.php';

class TransaksiModel extends Database {

    private function generateKode($jenis) {
        $prefix = $jenis === 'masuk' ? 'TM' : 'TK';
        $tanggal = date('Ymd');
        $qry = "SELECT COUNT(*) as total FROM transaksi WHERE jenis = ? AND DATE(tanggal) = CURDATE()";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("s", $jenis);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $urutan = str_pad($result['total'] + 1, 3, '0', STR_PAD_LEFT);
        return $prefix . '-' . $tanggal . '-' . $urutan;
    }

    public function getAll($jenis = null) {
        if ($jenis) {
            $qry = "SELECT t.*, a.nama as nama_admin, b.nama as nama_barang 
                    FROM transaksi t 
                    JOIN admin a ON t.id_admin = a.id 
                    JOIN detail_transaksi dt ON t.id = dt.id_transaksi
                    JOIN barang b ON dt.id_barang = b.id
                    WHERE t.jenis = ?
                    ORDER BY t.tanggal DESC";
            $stmt = $this->conn->prepare($qry);
            $stmt->bind_param("s", $jenis);
            $stmt->execute();
            return $stmt->get_result();
        }
        $qry = "SELECT t.*, a.nama as nama_admin 
                FROM transaksi t 
                JOIN admin a ON t.id_admin = a.id 
                ORDER BY t.tanggal DESC";
        return $this->conn->query($qry);
    }

    public function getById($id) {
        $qry = "SELECT t.*, a.nama as nama_admin 
                FROM transaksi t 
                JOIN admin a ON t.id_admin = a.id 
                WHERE t.id = ?";
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

    public function transaksiMasuk($id_barang, $jumlah, $keterangan, $id_admin) {
        $kode = $this->generateKode('masuk');
        $tanggal = date('Y-m-d H:i:s');

        $qry = "INSERT INTO transaksi (kode, jenis, tanggal, keterangan, id_admin) 
                VALUES (?, 'masuk', ?, ?, ?)";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("sssi", $kode, $tanggal, $keterangan, $id_admin);

        if (!$stmt->execute()) {
            return false;
        }

        $id_transaksi = $this->conn->insert_id;

        $qry = "INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, harga_satuan, subtotal) 
                VALUES (?, ?, ?, 0, 0)";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("iii", $id_transaksi, $id_barang, $jumlah);

        if (!$stmt->execute()) {
            return false;
        }

        $qry = "UPDATE barang SET stok = stok + ? WHERE id = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("ii", $jumlah, $id_barang);
        return $stmt->execute();
    }

    public function transaksiKeluar($id_barang, $jumlah, $keterangan, $id_admin) {
        $cek = "SELECT stok FROM barang WHERE id = ?";
        $stmt = $this->conn->prepare($cek);
        $stmt->bind_param("i", $id_barang);
        $stmt->execute();
        $barang = $stmt->get_result()->fetch_assoc();

        if (!$barang || $barang['stok'] < $jumlah) {
            return false;
        }

        $kode = $this->generateKode('keluar');
        $tanggal = date('Y-m-d H:i:s');

        $qry = "INSERT INTO transaksi (kode, jenis, tanggal, keterangan, id_admin) 
                VALUES (?, 'keluar', ?, ?, ?)";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("sssi", $kode, $tanggal, $keterangan, $id_admin);

        if (!$stmt->execute()) {
            return false;
        }

        $id_transaksi = $this->conn->insert_id;

        $qry = "INSERT INTO detail_transaksi (id_transaksi, id_barang, jumlah, harga_satuan, subtotal) 
                VALUES (?, ?, ?, 0, 0)";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("iii", $id_transaksi, $id_barang, $jumlah);

        if (!$stmt->execute()) {
            return false;
        }

        $qry = "UPDATE barang SET stok = stok - ? WHERE id = ?";
        $stmt = $this->conn->prepare($qry);
        $stmt->bind_param("ii", $jumlah, $id_barang);
        return $stmt->execute();
    }

    public function getByPeriode($jenis, $dari, $sampai) {
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
}
?>