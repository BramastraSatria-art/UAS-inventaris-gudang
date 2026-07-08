<?php
require_once '../config/koneksi.php';
require_once '../config/session.php';
require_once '../auth/cek_session.php';
require_once '../models/BarangModel.php';
require_once '../models/UploadModel.php';

cekRole('Superadmin', 'Admin');

$barangModel = new BarangModel();
$uploadModel = new UploadModel();

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

if ($aksi === 'tambah') {
    $id_kategori = $_POST['id_kategori'];
    $id_supplier = $_POST['id_supplier'];
    $nama = trim($_POST['nama']);
    $stok = $_POST['stok'];
    $satuan = trim($_POST['satuan']);
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $keterangan = trim($_POST['keterangan']);
    $foto = null;

    if (!empty($_FILES['foto']['name'])) {
        $foto = $uploadModel->upload($_FILES['foto']);
    }

    $barangModel->insert($id_kategori, $id_supplier, $nama, $stok, $satuan, $harga_beli, $harga_jual, $foto, $keterangan);

} elseif ($aksi === 'edit') {
    $id = $_POST['id'];
    $id_kategori = $_POST['id_kategori'];
    $id_supplier = $_POST['id_supplier'];
    $nama = trim($_POST['nama']);
    $stok = $_POST['stok'];
    $satuan = trim($_POST['satuan']);
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $keterangan = trim($_POST['keterangan']);
    $foto_lama = $_POST['foto_lama'];
    $foto = $foto_lama;

    if (!empty($_FILES['foto']['name'])) {
        $foto_baru = $uploadModel->upload($_FILES['foto']);
        if ($foto_baru) {
            if ($foto_lama) {
                $uploadModel->delete($foto_lama);
            }
            $foto = $foto_baru;
        }
    }

    $barangModel->update($id, $id_kategori, $id_supplier, $nama, $stok, $satuan, $harga_beli, $harga_jual, $foto, $keterangan);

} elseif ($aksi === 'hapus') {
    $id = $_GET['id'];
    $barangModel->delete($id);
}

header('Location: barang.php');
exit;
?>