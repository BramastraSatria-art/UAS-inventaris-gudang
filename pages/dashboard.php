<?php
require_once '../config/koneksi.php';
require_once '../config/session.php';
require_once '../auth/cek_session.php';
require_once '../models/BarangModel.php';
require_once '../models/LaporanModel.php';
require_once '../vendor/autoload.php';

use Carbon\Carbon;

$barangModel = new BarangModel();
$laporanModel = new LaporanModel();

$total_barang = $barangModel->getAll()->num_rows;
$stok_menipis = $barangModel->getStokMenipis()->num_rows;
$stok_habis = $barangModel->getStokHabis()->num_rows;

$hari_ini = Carbon::today()->toDateString();
$transaksi_masuk_hari_ini = $laporanModel->getLaporanTransaksi('masuk', $hari_ini, $hari_ini)->num_rows;
$transaksi_keluar_hari_ini = $laporanModel->getLaporanTransaksi('keluar', $hari_ini, $hari_ini)->num_rows;
$penjualan_hari_ini = $laporanModel->getTotalPendapatan($hari_ini, $hari_ini);

$statistik = $laporanModel->getStatistikHarian();
$labels = [];
$data_penjualan = [];
while ($row = $statistik->fetch_assoc()) {
    $labels[] = Carbon::parse($row['tanggal'])->format('d M');
    $data_penjualan[] = $row['total_penjualan'];
}

$stok_kritis = $barangModel->getStokMenipis();

include '../components/header.php';
include '../components/navbar.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../components/sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <h4 class="mb-4">Dashboard</h4>

            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <h6 class="card-title">Total Jenis Barang</h6>
                            <h3><?= $total_barang ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <h6 class="card-title">Stok Menipis</h6>
                            <h3 class="text-warning"><?= $stok_menipis ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <h6 class="card-title">Stok Habis</h6>
                            <h3 class="text-danger"><?= $stok_habis ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <h6 class="card-title">Transaksi Masuk Hari Ini</h6>
                            <h3><?= $transaksi_masuk_hari_ini ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <h6 class="card-title">Transaksi Keluar Hari Ini</h6>
                            <h3><?= $transaksi_keluar_hari_ini ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-stat">
                        <div class="card-body">
                            <h6 class="card-title">Total Penjualan Hari Ini</h6>
                            <h3>Rp <?= number_format($penjualan_hari_ini, 0, ',', '.') ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Grafik Penjualan 7 Hari Terakhir</h5>
                            <canvas id="grafikPenjualan"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Barang Stok Kritis</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $stok_kritis->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['nama']) ?></td>
                                        <td><span class="badge bg-warning text-dark"><?= $row['stok'] ?></span></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('grafikPenjualan').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Total Penjualan (Rp)',
            data: <?= json_encode($data_penjualan) ?>,
            backgroundColor: '#1a237e'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<?php include '../components/footer.php'; ?>