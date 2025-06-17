<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'perusahaan') {
    header("Location: login.php");
    exit();
}
require 'koneksi.php';

$userId = $_SESSION['id'];

// Ambil ID perusahaan
$getPerusahaan = mysqli_query($conn, "SELECT * FROM perusahaan WHERE user_id = $userId");
$perusahaan = mysqli_fetch_assoc($getPerusahaan);
$perusahaanId = $perusahaan['id'];

// Proses jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $insert = mysqli_query($conn, "INSERT INTO lowongan 
        (perusahaan_id, judul, lokasi, jenis_pekerjaan, level_pekerjaan, pendidikan, gaji, deskripsi, keahlian, kualifikasi, tanggal_posting, tanggal_batas) 
        VALUES ('$perusahaanId', '$judul', '$lokasi', '$jenis', '$level', '$pendidikan', '$gaji', '$deskripsi', '$keahlian', '$kualifikasi', CURDATE(), '$tanggal_batas')");

    if ($insert) {
        echo "<script>alert('✅ Lowongan berhasil ditambahkan!'); window.location='dashboard_perusahaan.php';</script>";
        exit();
    } else {
        echo "Gagal menambahkan lowongan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Lowongan</title>
    <link rel="stylesheet" href="css/lowongan.css">
</head>
<body>

<?php include 'header.php'; ?>

<nav class="breadcrumb">
    <div class="breadcrumb-content">
        <a href="dashboard_perusahaan.php">Home</a> / <span>Tambah Lowongan</span>
    </div>
</nav>

<section id="apply">
    <h2>Tambah Lowongan Pekerjaan</h2>
    <form method="POST">
        <label>Judul Posisi</label>
        <input type="text" name="judul" required>

        <label>Lokasi</label>
        <input type="text" name="lokasi" required>

        <label>Jenis Pekerjaan</label>
        <select name="jenis_pekerjaan" required>
            <option value="-- Jenis Pekerjaan --">-- Jenis Pekerjaan --</option>
            <option value="Full Time">Full Time</option>
            <option value="Part Time">Part Time</option>
            <option value="Kontrak">Kontrak</option>
            <option value="Freelance">Freelance</option>
        </select>

        <label>Level Pekerjaan</label>
        <select name="level_pekerjaan" required>
            <option value="-- Level Pekerjaan --">-- Level Pekerjaan --</option>
            <option value="Junior / Entry Level">Junior / Entry Level</option>
            <option value="Mid Level">Mid Level</option>
            <option value="Senior Level">Senior Level</option>
            <option value="Executive Level">Executive Level</option>
        </select>

        <label>Pendidikan Minimal</label>
        <input type="text" name="pendidikan" placeholder="Contoh: SMA / D3 / S1" required>

        <label>Rentang Gaji</label>
        <input type="text" name="gaji" placeholder="Contoh: 3 - 5 juta / Negosiasi" required>

        <label>Deskripsi Pekerjaan</label>
        <textarea name="deskripsi" rows="5" required></textarea>

        <label>Keahlian Dibutuhkan</label>
        <textarea name="keahlian" rows="4" required></textarea>

        <label>Kualifikasi</label>
        <textarea name="kualifikasi" rows="4" required></textarea>

        <label>Tanggal Batas Pelamaran</label>
        <input type="date" name="tanggal_batas" required>

        <button type="submit" class="tombol-lamaran">Simpan Lowongan</button>
    </form>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
