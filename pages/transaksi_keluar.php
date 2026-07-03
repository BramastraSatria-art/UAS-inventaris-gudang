<?php
require_once '../config/koneksi.php';
require_once '../config/session.php';
require_once '../auth/cek_session.php';
require_once '../models/TransaksiModel.php';
require_once '../models/BarangModel.php';

$barangModel = new BarangModel();
$transaksiModel = new TransaksiModel();

$barangs = $barangModel->getAll();
$riwayat = $transaksiModel->getAll('keluar');

include '../components/header.php';
include '../components/navbar.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../components/sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <h4 class="mb-4">Transaksi Keluar</h4>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'stok_kurang'): ?>
                <div class="alert alert-danger">Stok barang tidak mencukupi.</div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Form Transaksi Keluar</h5>
                    <form method="POST" action="proses_transaksi.php">
                        <input type="hidden" name="aksi" value="keluar">

                        <div class="mb-3">
                            <label class="form-label">Barang</label>
                            <select name="id_barang" class="form-select" required>
                                <option value="">Pilih Barang</option>
                                <?php while ($row = $barangs->fetch_assoc()): ?>
                                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?> (Stok: <?= $row['stok'] ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>

            <h5 class="mb-3">Riwayat Transaksi Keluar</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Admin</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $riwayat->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['kode']) ?></td>
                        <td><?= $row['tanggal'] ?></td>
                        <td><?= htmlspecialchars($row['nama_admin']) ?></td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>