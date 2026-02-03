<?php
session_start();
require '../../config/database.php';

$id = $_GET['id'];

// cek apakah fasilitas dipakai di peminjaman
$cek = $pdo->prepare("SELECT COUNT(*) FROM peminjaman WHERE fasilitas_id = ?");
$cek->execute([$id]);
$jumlah = $cek->fetchColumn();

if ($jumlah > 0) {
    echo "<script>
        alert('Fasilitas tidak bisa dihapus karena masih digunakan pada data peminjaman!');
        window.location='index.php';
    </script>";
    exit;
}

// jika tidak dipakai, baru hapus
$hapus = $pdo->prepare("DELETE FROM fasilitas WHERE id = ?");
$hapus->execute([$id]);

header('Location: index.php');
?>
