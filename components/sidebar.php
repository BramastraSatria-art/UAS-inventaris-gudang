<?php
$role = Session::getRole();
$current = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar col-md-2 p-0">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white <?= $current === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
        </li>

        <?php if (in_array($role, ['Superadmin', 'Admin'])): ?>
            <li class="nav-item">
                <a class="nav-link text-white <?= $current === 'barang.php' ? 'active' : '' ?>" href="barang.php">Barang</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= $current === 'kategori.php' ? 'active' : '' ?>" href="kategori.php">Kategori</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= $current === 'supplier.php' ? 'active' : '' ?>" href="supplier.php">Supplier</a>
            </li>
        <?php endif; ?>

        <li class="nav-item">
            <a class="nav-link text-white <?= $current === 'transaksi_masuk.php' ? 'active' : '' ?>" href="transaksi_masuk.php">Transaksi Masuk</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $current === 'transaksi_keluar.php' ? 'active' : '' ?>" href="transaksi_keluar.php">Transaksi Keluar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $current === 'penjualan.php' ? 'active' : '' ?>" href="penjualan.php">Penjualan</a>
        </li>

        <?php if (in_array($role, ['Superadmin', 'Admin'])): ?>
            <li class="nav-item">
                <a class="nav-link text-white <?= $current === 'laporan.php' ? 'active' : '' ?>" href="laporan.php">Laporan</a>
            </li>
        <?php endif; ?>

        <?php if ($role === 'Superadmin'): ?>
            <li class="nav-item">
                <a class="nav-link text-white <?= $current === 'kelola_user.php' ? 'active' : '' ?>" href="kelola_user.php">Kelola User</a>
            </li>
        <?php endif; ?>
    </ul>
</div>