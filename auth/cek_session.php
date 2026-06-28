<?php
require_once '../config/session.php';

if (!Session::isLogin()) {
    header('Location: ../auth/login.php');
    exit;
}

if (Session::isTimeout()) {
    header('Location: ../auth/login.php?timeout=1');
    exit;
}

function cekRole(...$roles) {
    $userRole = Session::getRole();
    if (!in_array($userRole, $roles)) {
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>Akses Ditolak</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container mt-5">
                <div class="card p-4">
                    <h4 class="text-danger">403 - Akses Ditolak</h4>
                    <p class="text-muted">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                    <a href="../pages/dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}
?>