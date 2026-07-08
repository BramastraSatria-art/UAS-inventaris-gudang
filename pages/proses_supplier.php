<?php
require_once '../config/koneksi.php';
require_once '../config/session.php';
require_once '../auth/cek_session.php';
require_once '../models/SupplierModel.php';

cekRole('Superadmin', 'Admin');

$supplierModel = new SupplierModel();
$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

if ($aksi === 'tambah') {
    $nama = trim($_POST['nama']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $supplierModel->create($nama, $telepon, $alamat);

} elseif ($aksi === 'edit') {
    $id = $_POST['id'];
    $nama = trim($_POST['nama']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $supplierModel->update($id, $nama, $telepon, $alamat);

} elseif ($aksi === 'hapus') {
    $id = $_GET['id'];
    $sukses = $supplierModel->delete($id);
    if (!$sukses) {
        header('Location: supplier.php?error=dipakai');
        exit;
    }
}

header('Location: supplier.php');
exit;
?>