<?php
require_once '../config/koneksi.php';
require_once '../config/session.php';
require_once '../auth/cek_session.php';
require_once '../models/BarangModel.php';
require_once '../models/KategoriModel.php';
require_once '../models/SupplierModel.php';

cekRole('Superadmin', 'Admin');

$barangModel = new BarangModel();
$kategoriModel = new KategoriModel();
$supplierModel = new SupplierModel();

$barangs = $barangModel->getAll();
$kategoris = $kategoriModel->getAll();
$suppliers = $supplierModel->getAll();

include '../components/header.php';
include '../components/navbar.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../components/sidebar.php'; ?>

        <div class="col-md-10 p-4">
            <h4 class="mb-4">Data Barang</h4>

            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Barang</button>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $barangs->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td>
                            <?php if ($row['foto']): ?>
                                <img src="../assets/uploads/<?= htmlspecialchars($row['foto']) ?>" class="foto-barang">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td><?= htmlspecialchars($row['nama_supplier']) ?></td>
                        <td><?= $row['stok'] ?></td>
                        <td><?= htmlspecialchars($row['satuan']) ?></td>
                        <td>Rp <?= number_format($row['harga_beli'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ($row['stok'] == 0): ?>
                                <span class="badge bg-danger">Habis</span>
                            <?php elseif ($row['stok'] <= 20): ?>
                                <span class="badge bg-warning text-dark">Menipis</span>
                            <?php else: ?>
                                <span class="badge bg-success">Aman</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">Edit</button>
                            <a href="proses_barang.php?aksi=hapus&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus barang ini?')">Hapus</a>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEdit<?= $row['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="proses_barang.php" enctype="multipart/form-data">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Barang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="aksi" value="edit">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($row['foto']) ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Nama Barang</label>
                                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kategori</label>
                                            <select name="id_kategori" class="form-select">
                                                <?php
                                                $kategoris->data_seek(0);
                                                while ($kat = $kategoris->fetch_assoc()):
                                                ?>
                                                <option value="<?= $kat['id'] ?>" <?= $kat['id'] == $row['id_kategori'] ? 'selected' : '' ?>><?= htmlspecialchars($kat['nama']) ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Supplier</label>
                                            <select name="id_supplier" class="form-select">
                                                <?php
                                                $suppliers->data_seek(0);
                                                while ($sup = $suppliers->fetch_assoc()):
                                                ?>
                                                <option value="<?= $sup['id'] ?>" <?= $sup['id'] == $row['id_supplier'] ? 'selected' : '' ?>><?= htmlspecialchars($sup['nama']) ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Stok</label>
                                            <input type="number" name="stok" class="form-control" value="<?= $row['stok'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Satuan</label>
                                            <input type="text" name="satuan" class="form-control" value="<?= htmlspecialchars($row['satuan']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Harga Beli</label>
                                            <input type="number" name="harga_beli" class="form-control" value="<?= $row['harga_beli'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Harga Jual</label>
                                            <input type="number" name="harga_jual" class="form-control" value="<?= $row['harga_jual'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Foto Baru (kosongkan jika tidak diubah)</label>
                                            <input type="file" name="foto" class="form-control" accept="image/*">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <textarea name="keterangan" class="form-control"><?= htmlspecialchars($row['keterangan']) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="proses_barang.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="aksi" value="tambah">

                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="id_kategori" class="form-select">
                            <?php
                            $kategoris->data_seek(0);
                            while ($kat = $kategoris->fetch_assoc()):
                            ?>
                            <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Supplier</label>
                        <select name="id_supplier" class="form-select">
                            <?php
                            $suppliers->data_seek(0);
                            while ($sup = $suppliers->fetch_assoc()):
                            ?>
                            <option value="<?= $sup['id'] ?>"><?= htmlspecialchars($sup['nama']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stok" class="form-control" value="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Beli</label>
                        <input type="number" name="harga_beli" class="form-control" value="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Jual</label>
                        <input type="number" name="harga_jual" class="form-control" value="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>