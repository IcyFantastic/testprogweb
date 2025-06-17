<?php
session_start();
// Periksa apakah pengguna sudah login dan memiliki peran sebagai perusahaan
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'perusahaan') {
    header("Location: login.php");
    exit();
}
require 'koneksi.php';

$userId = $_SESSION['id'];

// Ambil data perusahaan berdasarkan user_id
$getPerusahaan = mysqli_query($conn, "SELECT * FROM perusahaan WHERE user_id = $userId");
$perusahaan = mysqli_fetch_assoc($getPerusahaan);
$perusahaanId = $perusahaan['id'];

// Proses penghapusan lowongan
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    
    // Check if there are any applicants
    $checkApplicants = mysqli_query($conn, "SELECT COUNT(*) as total FROM lamaran WHERE lowongan_id = $deleteId");
    $applicantCount = mysqli_fetch_assoc($checkApplicants)['total'];
    
    if ($applicantCount > 0) {
        echo "<script>alert('❌ Lowongan tidak dapat dihapus karena sudah ada pelamar!'); window.location='dashboard_perusahaan.php';</script>";
        exit();
    }
    
    // If no applicants, proceed with deletion
    $deleteQuery = mysqli_query($conn, "DELETE FROM lowongan WHERE id = $deleteId AND perusahaan_id = $perusahaanId");

    if ($deleteQuery) {
        echo "<script>alert('✅ Lowongan berhasil dihapus!'); window.location='dashboard_perusahaan.php';</script>";
        exit();
    } else {
        echo "<script>alert('❌ Gagal menghapus lowongan!'); window.location='dashboard_perusahaan.php';</script>";
        exit();
    }
}

// Ambil daftar lowongan milik perusahaan
$lowongan = mysqli_query($conn, "
    SELECT l.*, 
           (SELECT COUNT(*) FROM lamaran WHERE lamaran.lowongan_id = l.id) AS jumlah_pelamar
    FROM lowongan l
    WHERE perusahaan_id = $perusahaanId
    ORDER BY l.tanggal_posting DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Perusahaan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard_perusahaan.css">
</head>
<body>

<div class="dashboard-container">
    <?php include 'header.php'; ?>

    <nav class="breadcrumb">
        <div class="breadcrumb-content">
            <a href="dashboard_perusahaan.php">Home</a> / <span>Dashboard Perusahaan</span>
        </div>
    </nav>

    <div class="main-content">
        <section id="search" class="welcome-section">
            <div class="welcome-header">
                <h2>Selamat datang, <?= htmlspecialchars($perusahaan['nama_perusahaan']) ?></h2>
                <p>📍 Lokasi: <?= $perusahaan['lokasi'] ?></p>
            </div>
            <button class="action-button" onclick="window.location.href='tambah_lowongan.php'">
                <span class="button-icon">➕</span> Tambah Lowongan
            </button>
        </section>

        <h1 class="section-title">Lowongan Kerja Yang Anda Buka</h1>

        <section id="job-listings">
            <div class="job-container">
                <?php while ($row = mysqli_fetch_assoc($lowongan)): ?>
                    <div class="job-card">
                        <div class="job-header">
                            <h3><?= htmlspecialchars($row['judul']) ?></h3>
                            <h2><?= htmlspecialchars($perusahaan['nama_perusahaan']) ?></h2>
                            <p class="location">📍 <?= htmlspecialchars($row['lokasi']) ?></p>
                        </div>
                        <div class="job-tags">
                            <span class="tag"><?= $row['jenis_pekerjaan'] ?></span>
                            <span class="tag"><?= $row['pendidikan'] ?></span>
                            <span class="tag"><?= $row['level_pekerjaan'] ?></span>
                        </div>
                        <div class="job-info">
                            <p class="salary">💰 <?= $row['gaji'] ?></p>
                            <p class="applicants">👥 Pelamar: <?= $row['jumlah_pelamar'] ?> orang</p>
                        </div>
                        <div class="action-buttons">
                            <a href="edit_lowongan.php?id=<?= $row['id'] ?>" class="btn edit">✏ Edit</a>
                            <a href="#" class="btn delete" onclick="handleDelete(<?= $row['id'] ?>, <?= $row['jumlah_pelamar'] ?>)">
                                🗑 Hapus
                            </a>
                            <a href="lihat_pelamar.php?id=<?= $row['id'] ?>" class="btn view">👀 Lihat Pelamar</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </div>

    <?php include 'footer.php'; ?>
</div>

<script>
function handleDelete(id, jumlahPelamar) {
    if (jumlahPelamar > 0) {
        alert('❌ Lowongan tidak dapat dihapus karena sudah ada ' + jumlahPelamar + ' pelamar!');
        return false;
    }
    
    if (confirm('Yakin ingin menghapus lowongan ini?')) {
        window.location.href = 'dashboard_perusahaan.php?delete_id=' + id;
    }
}
</script>
</body>
</html>