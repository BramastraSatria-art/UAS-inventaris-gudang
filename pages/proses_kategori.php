<?php
require_once '../config/koneksi.php';
require_once '../config/session.php';
require_once '../auth/cek_session.php';
require_once '../models/KategoriModel.php';

cekRole('Superadmin', 'Admin');

$kategoriModel = new KategoriModel();
$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

if ($aksi === 'tambah') {
    $nama = trim($_POST['nama']);
    $keterangan = trim($_POST['keterangan']);
    $kategoriModel->create($nama, $keterangan);

} elseif ($aksi === 'edit') {
    $id = $_POST['id'];
    $nama = trim($_POST['nama']);
    $keterangan = trim($_POST['keterangan']);
    $kategoriModel->update($id, $nama, $keterangan);

} elseif ($aksi === 'hapus') {
    $id = $_GET['id'];
    $sukses = $kategoriModel->delete($id);
    if (!$sukses) {
        header('Location: kategori.php?error=dipakai');
        exit;
    }
}

header('Location: kategori.php');
exit;
?>