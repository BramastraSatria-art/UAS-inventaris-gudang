<?php
require_once '../config/koneksi.php';
require_once '../config/session.php';
require_once '../auth/cek_session.php';
require_once '../models/BarangModel.php';

$barangModel = new BarangModel();
$barangs = $barangModel->getAll();

include '../components/header.php';
include '../components/navbar.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../components/sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <h4 class="mb-4">Penjualan</h4>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Pilih Barang</h5>

                            <div class="mb-3">
                                <label class="form-label">Barang</label>
                                <select id="pilihBarang" class="form-select">
                                    <option value="">Pilih Barang</option>
                                    <?php while ($row = $barangs->fetch_assoc()): ?>
                                    <option value="<?= $row['id'] ?>"
                                        data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                        data-harga="<?= $row['harga_jual'] ?>"
                                        data-stok="<?= $row['stok'] ?>">
                                        <?= htmlspecialchars($row['nama']) ?> (Stok: <?= $row['stok'] ?>) - Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" id="jumlahBarang" class="form-control" min="1" value="1">
                            </div>
                            <button type="button" class="btn btn-primary" onclick="tambahKeKeranjang()">Tambah ke Keranjang</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Keranjang</h5>

                            <table class="table table-bordered" id="tabelKeranjang">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="isiKeranjang"></tbody>
                            </table>

                            <div class="text-end mb-3">
                                <strong>Total: Rp <span id="totalHarga">0</span></strong>
                            </div>

                            <form method="POST" action="proses_penjualan.php" id="formPenjualan">
                                <input type="hidden" name="keranjang" id="inputKeranjang">
                                <div class="mb-3">
                                    <label class="form-label">Nama Pembeli</label>
                                    <input type="text" name="nama_pembeli" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-success w-100" onclick="return submitPenjualan()">Proses Penjualan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>