<?php
require_once '../config/koneksi.php';
require_once '../config/session.php';
require_once '../auth/cek_session.php';
require_once '../models/ExportModel.php';

cekRole('Superadmin', 'Admin');

$exportModel = new ExportModel();

$jenis = $_GET['jenis'] ?? 'penjualan';
$dari = $_GET['dari'] ?? date('Y-m-01');
$sampai = $_GET['sampai'] ?? date('Y-m-d');

if ($jenis === 'stok') {
    $exportModel->exportStok();
} elseif ($jenis === 'penjualan') {
    $exportModel->exportPenjualan($dari, $sampai);
} else {
    $exportModel->exportTransaksi($jenis, $dari, $sampai);
}
?>