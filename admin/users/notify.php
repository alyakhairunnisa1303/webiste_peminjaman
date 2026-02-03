<?php
require '../../config/database.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT u.email, u.name, f.nama, p.tgl_kembali, p.user_id
    FROM peminjaman p
    JOIN users u ON p.user_id = u.id
    JOIN fasilitas f ON p.fasilitas_id = f.id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) die("Data tidak ditemukan");

$to = $data['email'];
$subject = "PENGINGAT PENGEMBALIAN FASILITAS UKS";
$message = "Halo {$data['name']}, Anda meminjam: {$data['nama']}. Batas pengembalian: {$data['tgl_kembali']}. Mohon segera dikembalikan.";
$headers = "From: uks@pnp.ac.id";

// 1. Kirim Email (Tetap ada sebagai percobaan)
@mail($to, $subject, $message, $headers);

// 2. Simpan Notifikasi ke Database (Sistem Baru)
$notif_pesan = "PENGINGAT: Fasilitas <b>{$data['nama']}</b> seharusnya dikembalikan pada <b>" . date('d M Y', strtotime($data['tgl_kembali'])) . "</b>. Mohon segera dikembalikan.";
$stmt_notif = $pdo->prepare("INSERT INTO notifikasi (user_id, pesan) VALUES (?, ?)");
$stmt_notif->execute([$data['user_id'] ?? 0, $notif_pesan]); // Kita butuh user_id

header("Location: index.php?notif=sent");
exit;
