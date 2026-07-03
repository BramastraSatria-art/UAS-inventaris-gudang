<?php
require_once '../config/koneksi.php';
require_once '../config/session.php';
require_once '../auth/cek_session.php';
require_once '../models/PenjualanModel.php';

$penjualanModel = new PenjualanModel();
$id_admin = Session::getId();

$nama_pembeli = trim($_POST['nama_pembeli']);
$keranjang = json_decode($_POST['keranjang'], true);

if (empty($keranjang)) {
    header('Location: penjualan.php?error=keranjang_kosong');
    exit;
}

$result = $penjualanModel->simpan($nama_pembeli, $keranjang, $id_admin);

if ($result) {
    header('Location: penjualan.php?success=1');
} else {
    header('Location: penjualan.php?error=gagal');
}
exit;
?>