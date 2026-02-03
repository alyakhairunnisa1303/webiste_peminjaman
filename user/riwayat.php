<?php
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    // If no user_id, check user session
    if (isset($_SESSION['user']['id'])) {
        $user_id = $_SESSION['user']['id'];
    } else {
         header("Location: " . BASE_URL . "auth/login.php");
         exit;
    }
} else {
    $user_id = $_SESSION['user_id'];
}

$stmt = $pdo->prepare("
    SELECT p.*, f.nama AS nama_fasilitas
    FROM peminjaman p
    JOIN fasilitas f ON p.fasilitas_id = f.id
    WHERE p.user_id = ?
    ORDER BY p.id DESC
");
$stmt->execute([$user_id]);
$data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">
    <h3>Riwayat Peminjaman Saya</h3>

    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Fasilitas</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $d): ?>
            <tr>
                <td><?= $d['kode_pembayaran'] ?? '-' ?></td> <!-- Fallback if no kode_pembayaran -->
                <td><?= htmlspecialchars($d['nama_fasilitas']) ?></td>
                <td><?= $d['tgl_pinjam'] ?></td>
                <td><?= $d['tgl_kembali'] ?></td>
                <td>
                    <span class="badge bg-primary"><?= $d['status'] ?></span>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

</body>
</html>
