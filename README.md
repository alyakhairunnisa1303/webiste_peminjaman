# 🏥 Peminjaman UKS - Sistem Manajemen Fasilitas Sekolah

[![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/mysql-%5E8.0-orange.svg)](https://www.mysql.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Sistem informasi berbasis web yang dirancang khusus untuk mendigitalisasi manajemen peminjaman fasilitas dan alat medik pada Unit Kesehatan Sekolah (UKS). Solusi ini memudahkan pemantauan stok, proses peminjaman, hingga perhitungan denda otomatis.

---

## ✨ Fitur Unggulan

- **🛡️ Multi-User Authentication**: Sistem login terpisah untuk Admin dan Pengguna (Siswa/Guru).
- **📦 Inventaris Real-time**: Manajemen fasilitas dengan status ketersediaan otomatis.
- **📑 Digital Workflow**: Alur peminjaman dengan upload bukti identitas dan verifikasi admin.
- **💰 Automatic Fine System**: Perhitungan denda keterlambatan pengembalian secara sistematis.
- **📊 Laporan Terintegrasi**: Rekapitulasi data transaksi dalam bentuk laporan untuk admin.
- **🔔 Notifikasi Sistem**: Pesan status peminjaman (disetujui/ditolak) langsung ke dashboard user.

---

## 🛠️ Stack Teknologi

- **Backend**: PHP 8.x (Native dengan PDO)
- **Database**: MySQL 8.x
- **Frontend**: Vanilla CSS, JavaScript
- **Plugin**: SweetAlert2 (Interactive Notifications)

---

## 🚀 Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan project di lingkungan lokal (Laragon/XAMPP):

1.  **Clone Repository**
    ```bash
    git clone https://github.com/alyakhairunnisa1303/webiste_peminjaman.git
    ```

2.  **Konfigurasi Database**
    - Buat database baru bernama `peminjaman_uks`.
    - Impor file `database.sql` ke dalam database tersebut.
    - Masuk ke folder `config/`, salin file `database.php.example` menjadi `database.php`.
    - Buka `database.php` dan masukkan kredensial database Anda (host, user, pass).

3.  **Jalankan Server**
    - Pindahkan folder project ke `www/` (Laragon) atau `htdocs/` (XAMPP).
    - Akses melalui browser di: `http://localhost/peminjaman_uks/`

---

## 🔐 Akun Demo (Default)

| Role | Username | Password |
| :--- | :--- | :--- |
| **Admin** | `admin` | `adminuks123` |
| **User** | (Silakan daftar di halaman Registrasi) | - |

---

## 📄 Lisensi

Didistribusikan di bawah Lisensi MIT. Lihat file `LICENSE` untuk informasi lebih lanjut.

---

