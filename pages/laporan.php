<?php
require_once '../config/koneksi.php';
require_once '../config/session.php';
require_once '../auth/cek_session.php';
require_once '../models/LaporanModel.php';

cekRole('Superadmin', 'Admin');

$laporanModel = new LaporanModel();

$dari = $_GET['dari'] ?? date('Y-m-01');
$sampai = $_GET['sampai'] ?? date('Y-m-d');
$jenis = $_GET['jenis'] ?? 'penjualan';

include '../components/header.php';
include '../components/navbar.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../components/sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <h4 class="mb-4">Laporan</h4>

            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Jenis Laporan</label>
                                <select name="jenis" class="form-select">
                                    <option value="penjualan" <?= $jenis === 'penjualan' ? 'selected' : '' ?>>Penjualan</option>
                                    <option value="masuk" <?= $jenis === 'masuk' ? 'selected' : '' ?>>Transaksi Masuk</option>
                                    <option value="keluar" <?= $jenis === 'keluar' ? 'selected' : '' ?>>Transaksi Keluar</option>
                                    <option value="stok" <?= $jenis === 'stok' ? 'selected' : '' ?>>Stok Barang</option>
                                </select>
                            </div>
                            <?php if ($jenis !== 'stok'): ?>
                            <div class="col-md-3">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" name="dari" class="form-control" value="<?= $dari ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" name="sampai" class="form-control" value="<?= $sampai ?>">
                            </div>
                            <?php endif; ?>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">Tampilkan</button>
                                <a href="export_pdf.php?jenis=<?= $jenis ?>&dari=<?= $dari ?>&sampai=<?= $sampai ?>" class="btn btn-danger">Export PDF</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($jenis === 'stok'): ?>
                <?php $data = $laporanModel->getLaporanStok(); ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $data->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                            <td><?= $row['stok'] ?></td>
                            <td><?= htmlspecialchars($row['satuan']) ?></td>
                            <td>
                                <?php if ($row['stok'] == 0): ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php elseif ($row['stok'] <= 20): ?>
                                    <span class="badge bg-warning text-dark">Menipis</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Aman</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <?php elseif ($jenis === 'penjualan'): ?>
                <?php
                $data = $laporanModel->getLaporanPenjualan($dari, $sampai);
                $total_pendapatan = $laporanModel->getTotalPendapatan($dari, $sampai);
                ?>
                <div class="alert alert-info">Total Pendapatan: <strong>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></strong></div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Nama Pembeli</th>
                            <th>Total</th>
                            <th>Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $data->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['kode']) ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= htmlspecialchars($row['nama_pembeli']) ?></td>
                            <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['nama_admin']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <?php else: ?>
                <?php $data = $laporanModel->getLaporanTransaksi($jenis, $dari, $sampai); ?>
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
                        <?php while ($row = $data->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['kode']) ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= htmlspecialchars($row['nama_admin']) ?></td>
                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>