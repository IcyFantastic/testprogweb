<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'perusahaan') {
    header("Location: login.php");
    exit();
}
require 'koneksi.php';

$userId = $_SESSION['id'];
$lowonganId = $_GET['id'] ?? null;

if (!$lowonganId) {
    echo "ID lowongan tidak ditemukan.";
    exit();
}

// Ambil ID perusahaan dari user
$getPerusahaan = mysqli_query($conn, "SELECT * FROM perusahaan WHERE user_id = $userId");
$perusahaan = mysqli_fetch_assoc($getPerusahaan);
$perusahaanId = $perusahaan['id'];

// Validasi: apakah lowongan milik perusahaan ini?
$cek = mysqli_query($conn, "SELECT * FROM lowongan WHERE id = $lowonganId AND perusahaan_id = $perusahaanId");
$lowongan = mysqli_fetch_assoc($cek);

if (!$lowongan) {
    echo "Lowongan tidak ditemukan atau bukan milik Anda.";
    exit();
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $lokasi = $_POST['lokasi'];
    $jenis = $_POST['jenis_pekerjaan'];
    $level = $_POST['level_pekerjaan'];
    $pendidikan = $_POST['pendidikan'];
    $gaji = $_POST['gaji'];
    $deskripsi = $_POST['deskripsi'];
    $keahlian = $_POST['keahlian'];
    $kualifikasi = $_POST['kualifikasi'];
    $tanggal_batas = $_POST['tanggal_batas']; // Ambil tanggal batas pelamaran dari form

    $update = mysqli_query($conn, "UPDATE lowongan SET 
        judul = '$judul',
        lokasi = '$lokasi',
        jenis_pekerjaan = '$jenis',
        level_pekerjaan = '$level',
        pendidikan = '$pendidikan',
        gaji = '$gaji',
        deskripsi = '$deskripsi',
        keahlian = '$keahlian',
        kualifikasi = '$kualifikasi',
        tanggal_batas = '$tanggal_batas'
        WHERE id = $lowonganId AND perusahaan_id = $perusahaanId
    ");

    if ($update) {
        echo "<script>alert('✅ Lowongan berhasil diperbarui!'); window.location='dashboard_perusahaan.php';</script>";
        exit();
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Lowongan</title>
    <link rel="stylesheet" href="css/lowongan.css">
</head>
<body>

<?php include 'header.php'; ?>

<nav class="breadcrumb">
    <div class="breadcrumb-content">
        <a href="dashboard_perusahaan.php">Home</a> / <span>Edit Lowongan</span>
    </div>
</nav>

<section id="apply">
    <h2>Edit Lowongan Pekerjaan</h2>
    <form method="POST">
        <label>Judul Posisi</label>
        <input type="text" name="judul" value="<?= htmlspecialchars($lowongan['judul']) ?>" required>

        <label>Lokasi</label>
        <input type="text" name="lokasi" value="<?= htmlspecialchars($lowongan['lokasi']) ?>" required>

        <label>Jenis Pekerjaan</label>
        <select name="jenis_pekerjaan" required>
            <option <?= $lowongan['jenis_pekerjaan'] === '-- Jenis Pekerjaan --' ? 'selected' : '' ?>>-- Jenis Pekerjaan --</option>
            <option <?= $lowongan['jenis_pekerjaan'] === 'Full Time' ? 'selected' : '' ?>>Full Time</option>
            <option <?= $lowongan['jenis_pekerjaan'] === 'Part Time' ? 'selected' : '' ?>>Part Time</option>
            <option <?= $lowongan['jenis_pekerjaan'] === 'Kontrak' ? 'selected' : '' ?>>Kontrak</option>
            <option <?= $lowongan['jenis_pekerjaan'] === 'Freelance' ? 'selected' : '' ?>>Freelance</option>
        </select>

        <label>Level Pekerjaan</label>
        <select name="level_pekerjaan" required>
            <option <?= $lowongan['level_pekerjaan'] === '-- Level Pekerjaan --' ? 'selected' : '' ?>>-- Level Pekerjaan --</option>
            <option <?= $lowongan['level_pekerjaan'] === 'Junior / Entry Level' ? 'selected' : '' ?>>Junior / Entry Level</option>
            <option <?= $lowongan['level_pekerjaan'] === 'Mid Level' ? 'selected' : '' ?>>Mid Level</option>
            <option <?= $lowongan['level_pekerjaan'] === 'Senior Level' ? 'selected' : '' ?>>Senior Level</option>
            <option <?= $lowongan['level_pekerjaan'] === 'Executive Level' ? 'selected' : '' ?>>Executive Level</option>
        </select>

        <label>Pendidikan Minimal</label>
        <input type="text" name="pendidikan" value="<?= htmlspecialchars($lowongan['pendidikan']) ?>" required>

        <label>Rentang Gaji</label>
        <input type="text" name="gaji" value="<?= htmlspecialchars($lowongan['gaji']) ?>" required>

        <label>Deskripsi Pekerjaan</label>
        <textarea name="deskripsi" rows="5" required><?= htmlspecialchars($lowongan['deskripsi']) ?></textarea>

        <label>Keahlian Dibutuhkan</label>
        <textarea name="keahlian" rows="4" required><?= htmlspecialchars($lowongan['keahlian']) ?></textarea>

        <label>Kualifikasi</label>
        <textarea name="kualifikasi" rows="4" required><?= htmlspecialchars($lowongan['kualifikasi']) ?></textarea>

        <label>Tanggal Batas Pelamaran</label>
        <input type="date" name="tanggal_batas" value="<?= htmlspecialchars($lowongan['tanggal_batas']) ?>" required>

        <button type="submit" class="tombol-lamaran">Update Lowongan</button>
    </form>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
