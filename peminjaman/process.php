<?php
require_once __DIR__ . '/../config/database.php';

// ===============================
// VALIDASI INPUT
// ===============================
if (!isset($_POST['fasilitas_id'])) {
    die("Akses tidak valid!");
}

$fasilitas_id    = $_POST['fasilitas_id'];
$tgl_pinjam      = $_POST['tgl_pinjam'];
$tgl_kembali     = $_POST['tgl_kembali'];
$keperluan       = $_POST['keperluan'];
$jumlah_pinjam   = (int) ($_POST['jumlah_pinjam'] ?? 1);

// ===============================
// SECURITY: CSRF & IDOR FIX
// ===============================
if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
    set_flash('error', 'Akses Ditolak', 'Token keamanan tidak valid.');
    header("Location: " . BASE_URL . "fasilitas.php");
    exit;
}

// Gunakan ID dari SESSION, bukan POST data (Fix IDOR)
$user_id = $_SESSION['user']['id'];

// ===============================
// VALIDASI STOK (SERVER-SIDE)
// ===============================
$stmt_f = $pdo->prepare("SELECT * FROM fasilitas WHERE id = ?");
$stmt_f->execute([$fasilitas_id]);
$f = $stmt_f->fetch();

if (!$f) {
    die("Fasilitas tidak ditemukan!");
}

$stmt_stok = $pdo->prepare("SELECT SUM(jumlah_pinjam) FROM peminjaman WHERE fasilitas_id = ? AND status = 'dipinjam'");
$stmt_stok->execute([$fasilitas_id]);
$stok_terpakai = (int) $stmt_stok->fetchColumn() ?: 0;
$stok_tersisa = $f['jumlah'] - $stok_terpakai;

if ($jumlah_pinjam > $stok_tersisa) {
    set_flash('error', 'Stok Tidak Cukup', "Maaf, sisa stok tersedia hanya $stok_tersisa unit.");
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

if ($jumlah_pinjam < 1) {
    set_flash('error', 'Input Gagal', 'Jumlah peminjaman minimal 1 unit.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

// ===============================
// GENERATE KODE PEMINJAMAN UNIK
// Format: PMJ-2025-12345
// ===============================
$kode_pembayaran = "PMJ-" . date("Y") . "-" . rand(10000,99999);

// ===============================
// VALIDASI UPLOAD FILE IDENTITAS
// ===============================
if (!isset($_FILES['identitas']) || $_FILES['identitas']['error'] !== 0) {
    set_flash('error', 'Upload Gagal', 'Harap lampirkan foto identitas Anda.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

$allowed = ['jpg', 'jpeg', 'png'];
$namaFile = $_FILES['identitas']['name'];
$tmpFile  = $_FILES['identitas']['tmp_name'];

$ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    set_flash('error', 'Format Tidak Valid', 'Identitas harus berupa JPG atau PNG.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

// ===============================
// SECURITY: MIME TYPE CHECK
// ===============================
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $tmpFile);
finfo_close($finfo);

$allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];

if (!in_array($mime, $allowedMimes)) {
    set_flash('error', 'File Berbahaya', 'Tipe file identitas tidak diperbolehkan.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

// Buat folder jika belum ada (Relative to BASE_PATH/uploads)
$folder = "../uploads/identitas/";
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

// Nama file unik
$newName = "ID_" . time() . "_" . rand(1000,9999) . "." . $ext;

// Upload file identitas
move_uploaded_file($tmpFile, $folder . $newName);

// ===============================
// VALIDASI UPLOAD BUKTI PEMBAYARAN
// ===============================
if (!isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] !== 0) {
    die("Upload bukti pembayaran gagal!");
}

$allowedP = ['jpg', 'jpeg', 'png'];
$namaFileP = $_FILES['bukti_pembayaran']['name'];
$tmpFileP  = $_FILES['bukti_pembayaran']['tmp_name'];
$extP = strtolower(pathinfo($namaFileP, PATHINFO_EXTENSION));

if (!in_array($extP, $allowedP)) {
    die("Format bukti pembayaran wajib JPG/PNG!");
}

// Security MIME check for payment proof
$finfoP = finfo_open(FILEINFO_MIME_TYPE);
$mimeP = finfo_file($finfoP, $tmpFileP);
finfo_close($finfoP);

if (!in_array($mimeP, $allowedMimes)) {
    die("File bukti pembayaran tidak valid!");
}

$folderP = "../uploads/pembayaran/";
if (!is_dir($folderP)) {
    mkdir($folderP, 0777, true);
}

$newNameP = "PAY_" . time() . "_" . rand(1000,9999) . "." . $extP;
move_uploaded_file($tmpFileP, $folderP . $newNameP);

// ===============================
// SIMPAN KE DATABASE
// ===============================
$stmt = $pdo->prepare("
    INSERT INTO peminjaman 
    (user_id, fasilitas_id, jumlah_pinjam, tgl_pinjam, tgl_kembali, keperluan, kode_pembayaran, identitas_file, bukti_pembayaran, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
");

$success = $stmt->execute([
    $user_id,
    $fasilitas_id,
    $jumlah_pinjam,
    $tgl_pinjam,
    $tgl_kembali,
    $keperluan,
    $kode_pembayaran,
    $newName,
    $newNameP
]);

if ($success) {
    set_flash('success', 'Berhasil!', 'Peminjaman Anda telah terkirim dan sedang menunggu verifikasi.');
    header("Location: success.php?kode=" . $kode_pembayaran);
    exit;
} else {
    set_flash('error', 'Gagal', 'Terjadi kesalahan saat menyimpan data.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}
?>
