<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/LaporanModel.php';

class ExportModel {

    private $laporanModel;

    public function __construct() {
        $this->laporanModel = new LaporanModel();
    }

    private function buatPDF($judul) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('Inventaris Gudang');
        $pdf->SetAuthor('Inventaris Gudang');
        $pdf->SetTitle($judul);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, $judul, 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Ln(5);
        return $pdf;
    }

    public function exportStok() {
        $pdf = $this->buatPDF('Laporan Stok Barang');
        $data = $this->laporanModel->getLaporanStok();

        $header = ['Nama Barang', 'Kategori', 'Stok', 'Satuan', 'Status'];
        $lebar  = [70, 40, 20, 25, 25];

        $pdf->SetFont('helvetica', 'B', 10);
        foreach ($header as $i => $h) {
            $pdf->Cell($lebar[$i], 8, $h, 1, 0, 'C');
        }
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 10);
        while ($row = $data->fetch_assoc()) {
            if ($row['stok'] == 0) {
                $status = 'Habis';
            } elseif ($row['stok'] <= 20) {
                $status = 'Menipis';
            } else {
                $status = 'Aman';
            }

            $pdf->Cell($lebar[0], 8, $row['nama'], 1);
            $pdf->Cell($lebar[1], 8, $row['nama_kategori'], 1);
            $pdf->Cell($lebar[2], 8, $row['stok'], 1, 0, 'C');
            $pdf->Cell($lebar[3], 8, $row['satuan'], 1, 0, 'C');
            $pdf->Cell($lebar[4], 8, $status, 1, 0, 'C');
            $pdf->Ln();
        }

        $pdf->Output('laporan_stok.pdf', 'D');
    }

    public function exportPenjualan($dari, $sampai) {
        $pdf = $this->buatPDF('Laporan Penjualan');
        $pdf->Cell(0, 6, 'Periode: ' . $dari . ' s/d ' . $sampai, 0, 1);
        $pdf->Ln(3);

        $data = $this->laporanModel->getLaporanPenjualan($dari, $sampai);
        $total_pendapatan = $this->laporanModel->getTotalPendapatan($dari, $sampai);

        $header = ['Kode', 'Tanggal', 'Nama Pembeli', 'Total', 'Admin'];
        $lebar  = [35, 35, 50, 35, 25];

        $pdf->SetFont('helvetica', 'B', 10);
        foreach ($header as $i => $h) {
            $pdf->Cell($lebar[$i], 8, $h, 1, 0, 'C');
        }
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 10);
        while ($row = $data->fetch_assoc()) {
            $pdf->Cell($lebar[0], 8, $row['kode'], 1);
            $pdf->Cell($lebar[1], 8, $row['tanggal'], 1);
            $pdf->Cell($lebar[2], 8, $row['nama_pembeli'], 1);
            $pdf->Cell($lebar[3], 8, 'Rp ' . number_format($row['total'], 0, ',', '.'), 1);
            $pdf->Cell($lebar[4], 8, $row['nama_admin'], 1);
            $pdf->Ln();
        }

        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 8, 'Total Pendapatan: Rp ' . number_format($total_pendapatan, 0, ',', '.'), 0, 1, 'R');

        $pdf->Output('laporan_penjualan.pdf', 'D');
    }

    public function exportTransaksi($jenis, $dari, $sampai) {
        $judul = $jenis === 'masuk' ? 'Laporan Transaksi Masuk' : 'Laporan Transaksi Keluar';
        $pdf = $this->buatPDF($judul);
        $pdf->Cell(0, 6, 'Periode: ' . $dari . ' s/d ' . $sampai, 0, 1);
        $pdf->Ln(3);

        $data = $this->laporanModel->getLaporanTransaksi($jenis, $dari, $sampai);

        $header = ['Kode', 'Tanggal', 'Admin', 'Keterangan'];
        $lebar  = [40, 40, 40, 60];

        $pdf->SetFont('helvetica', 'B', 10);
        foreach ($header as $i => $h) {
            $pdf->Cell($lebar[$i], 8, $h, 1, 0, 'C');
        }
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 10);
        while ($row = $data->fetch_assoc()) {
            $pdf->Cell($lebar[0], 8, $row['kode'], 1);
            $pdf->Cell($lebar[1], 8, $row['tanggal'], 1);
            $pdf->Cell($lebar[2], 8, $row['nama_admin'], 1);
            $pdf->Cell($lebar[3], 8, $row['keterangan'], 1);
            $pdf->Ln();
        }

        $pdf->Output('laporan_transaksi_' . $jenis . '.pdf', 'D');
    }
}
?>