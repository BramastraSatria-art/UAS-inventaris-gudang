# UAS-inventaris-gudang

Sistem untuk mengelola data stok barang pada gudang yang mendistribusikan barang ke toko/pembeli, mencatat alur barang masuk dari supplier dan keluar ke toko, serta memantau ketersediaan stok secara real-time.

## Anggota Kelompok

1. I Made Ari Saputra (240040096)
2. Made Bramastra Satria Pinangguh (240040097)
3. I Wayan Bayu Caerma Akasidan (240040103)

## Teknologi

- PHP Native
- MySQL
- Bootstrap 5
- Chart.js
- TCPDF
- Carbon (via Composer)

## Cara Install

1. Clone repository ini
2. Import database dari `database/db_inventaris_gudang_uas.sql` ke phpMyAdmin
3. Jalankan `composer install` untuk install dependensi Carbon
4. Download TCPDF dari https://github.com/tecnickcom/TCPDF, ekstrak dan taruh di folder `TCPDF-main/`
5. Jalankan di XAMPP, buka `http://localhost/inventaris-gudang_UAS`

## Akun Default

- Superadmin: username `superadmin`, password `superadmin123`
- Admin: username `admin`, password `admin123`
- Staff: username `staff`, password `staff123`
