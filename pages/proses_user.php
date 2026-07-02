<?php
require_once '../config/koneksi.php';
require_once '../config/session.php';
require_once '../auth/cek_session.php';
require_once '../models/AdminModel.php';

cekRole('Superadmin');

$adminModel = new AdminModel();
$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

if ($aksi === 'tambah') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nama = trim($_POST['nama']);
    $role = $_POST['role'];

    $adminModel->insert($username, $password, $nama, $role);
} elseif ($aksi === 'edit') {
    $id = $_POST['id'];
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nama = trim($_POST['nama']);
    $role = $_POST['role'];

    $adminModel->update($id, $username, $password, $nama, $role);
} elseif ($aksi === 'hapus') {
    $id = $_GET['id'];
    $adminModel->delete($id);
}

header('Location: kelola_user.php');
exit;
?>