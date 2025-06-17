<?php
session_start();
require 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID lowongan tidak valid.";
    exit();
}

$idLowongan = $_GET['id'];
$userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;

// Ambil detail lowongan
$query = "SELECT l.*, p.nama_perusahaan, p.lokasi AS lokasi_perusahaan, p.logo, p.company_desc 
          FROM lowongan l 
          JOIN perusahaan p ON l.perusahaan_id = p.id 
          WHERE l.id = $idLowongan";
$result = mysqli_query($conn, $query);
$lowongan = mysqli_fetch_assoc($result);

if (!$lowongan) {
    echo "Lowongan tidak ditemukan.";
    exit();
}

// Cek apakah user sudah melamar
$sudahMelamar = false;
if ($userId) {
    $cekLamaran = mysqli_query($conn, "
        SELECT * FROM lamaran 
        WHERE pelamar_id = (SELECT id FROM pelamar WHERE user_id = $userId) 
        AND lowongan_id = $idLowongan
    ");
    $sudahMelamar = mysqli_num_rows($cekLamaran) > 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($lowongan['judul']) ?> - <?= htmlspecialchars($lowongan['nama_perusahaan']) ?></title>
    <link rel="stylesheet" href="css/detail.css">
    <script>
        function redirectToLogin() {
            alert("Silahkan Login sebelum melamar");
            window.location.href = "login.php";
        }
    </script>
</head>
<body>



<div class="container">
    <?php include 'header.php'; ?>
    
    <div class="job-header">
        <h1><?= htmlspecialchars($lowongan['judul']) ?></h1>
        <p class="company-name">🏢 <a href="#"><?= htmlspecialchars($lowongan['nama_perusahaan']) ?></a></p>

        <?php if (!isset($_SESSION['username'])): ?>
            <button class="apply-btn" onclick="redirectToLogin()">📝 Lamar Pekerjaan</button>
        <?php elseif ($sudahMelamar): ?>
            <div class="warning-message">⚠️ Anda sudah pernah melamar LOWONGAN ini.</div>
        <?php else: ?>
            <button class="apply-btn" onclick="window.location.href='form_lamaran.php?id=<?= $lowongan['id'] ?>'">📝 Lamar Pekerjaan</button>
        <?php endif; ?>
    </div>

    <div class="job-container">
        <div class="job-details">
            <p>📍 <strong>Lokasi:</strong> <?= htmlspecialchars($lowongan['lokasi']) ?></p>
            <p>⏳ <strong>Tipe:</strong> <?= htmlspecialchars($lowongan['jenis_pekerjaan']) ?></p>
            <p>📌 <strong>Level:</strong> <?= htmlspecialchars($lowongan['level_pekerjaan']) ?></p>
            <p>🎓 <strong>Pendidikan:</strong> <?= htmlspecialchars($lowongan['pendidikan']) ?></p>
            <p>💰 <strong>Gaji:</strong> <?= htmlspecialchars($lowongan['gaji']) ?></p>

            <section class="job-description">
                <h2>Deskripsi:</h2>
                <p><?= nl2br(htmlspecialchars($lowongan['deskripsi'])) ?></p>
            </section>

            <section class="job-requirements">
                <h2>Keahlian:</h2>
                <p><?= nl2br(htmlspecialchars($lowongan['keahlian'])) ?></p>
                <h2>Kualifikasi:</h2>
                <p><?= nl2br(htmlspecialchars($lowongan['kualifikasi'])) ?></p>
            </section>
        </div>

        <div class="company-card">
            <div class="company-header">
                <img src="Gambar/<?= htmlspecialchars($lowongan['logo']) ?>" alt="Logo" class="company-logo">
                <h2><?= htmlspecialchars($lowongan['nama_perusahaan']) ?></h2>
            </div>
            <div class="company-info">
                <p>📍 <?= htmlspecialchars($lowongan['lokasi_perusahaan']) ?></p>
                <p>🏢 Industri: Tidak ditentukan</p>
                <p>👥 Skala: Tidak ditentukan</p>
            </div>
            <p class="company-desc"><?= nl2br(htmlspecialchars($lowongan['company_desc'])) ?></p>
        </div>
    </div>

    <button class="back-btn" onclick="window.location.href='dashboard_pelamar.php'">⬅ Kembali ke Halaman Utama</button>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
