# Peminjaman UKS

Sistem informasi berbasis web untuk manajemen peminjaman fasilitas dan alat UKS (Unit Kesehatan Sekolah).

## Fitur Utama
- **Autentikasi**: Pendaftaran dan login untuk pengguna dan admin.
- **Manajemen Fasilitas**: Browse dan cari fasilitas/alat yang tersedia.
- **Proses Peminjaman**: Alur peminjaman barang dengan validasi stok.
- **Pengembalian & Denda**: Perhitungan denda otomatis jika terlambat mengembalikan.
- **Laporan**: Rekap peminjaman (untuk admin).

## Teknologi
- PHP (Native dengan PDO)
- MySQL
- CSS (Vanilla)
- SweetAlert2 (Notifikasi)

## Instalasi

1.  **Clone Repository**:
    ```bash
    git clone https://github.com/username/peminjaman_uks.git
    ```

2.  **Konfigurasi Database**:
    - Buat database baru di MySQL.
    - Impor file SQL database Anda ke database tersebut.
    - Salin `config/database.php.example` menjadi `config/database.php`.
    - Sesuaikan `host`, `user`, `pass`, dan `db` di `config/database.php`.

3.  **Jalankan di Server Lokal**:
    - Jika menggunakan Laragon, pindahkan folder ke `C:/laragon/www/`.
    - Akses melalui `http://localhost/peminjaman_uks/`.

## Akun Demo
- **Admin**: admin / adminuks123 (Silakan cek di database jika diubah)
- **User**: (Daftar melalui halaman register)

## Lisensi
[MIT](LICENSE)
