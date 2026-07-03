<?php
require_once '../config/koneksi.php';
require_once '../config/session.php';
require_once '../auth/cek_session.php';
require_once '../models/TransaksiModel.php';

$transaksiModel = new TransaksiModel();
$aksi = $_POST['aksi'] ?? '';
$id_admin = Session::getId();

if ($aksi === 'masuk') {
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $keterangan = trim($_POST['keterangan']);
    $transaksiModel->transaksiMasuk($id_barang, $jumlah, $keterangan, $id_admin);
    header('Location: transaksi_masuk.php');

} elseif ($aksi === 'keluar') {
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $keterangan = trim($_POST['keterangan']);
    $result = $transaksiModel->transaksiKeluar($id_barang, $jumlah, $keterangan, $id_admin);

    if (!$result) {
        header('Location: transaksi_keluar.php?error=stok_kurang');
    } else {
        header('Location: transaksi_keluar.php');
    }
}

exit;
?>