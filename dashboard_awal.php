<?php
require 'koneksi.php';

// Ambil kata kunci pencarian dari URL
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mencari lowongan berdasarkan kata kunci dan tanggal batas lamaran
$sql = "SELECT l.*, p.nama_perusahaan 
        FROM lowongan l 
        JOIN perusahaan p ON l.perusahaan_id = p.id
        WHERE 
            (l.judul LIKE '%$search%' OR 
            p.nama_perusahaan LIKE '%$search%' OR 
            l.lokasi LIKE '%$search%' OR 
            l.jenis_pekerjaan LIKE '%$search%' OR 
            l.gaji LIKE '%$search%') AND 
            l.tanggal_batas >= CURDATE()
        ORDER BY l.tanggal_posting DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelamar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard_pelamar.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="main-content">
        <div class="dashboard-container">
            <section class="search-section">
                <h1>Temukan Pekerjaan Impianmu</h1>
                <p class="search-subtitle">Jelajahi ribuan lowongan kerja sesuai dengan keahlianmu</p>
                <form method="GET" action="" class="search-form">
                    <div class="search-input-group">
                        <i class="fas fa-search input-icon"></i>
                        <input type="text" name="search" 
                               placeholder="Cari berdasarkan posisi, perusahaan, atau lokasi" 
                               value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="search-button">
                            Cari Pekerjaan
                        </button>
                    </div>
                </form>
            </section>

            <section class="jobs-section">
                <h2>Lowongan Tersedia</h2>
                <div class="job-grid">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <a href="detail_lowongan.php?id=<?= $row['id'] ?>" class="job-card">
                            <div class="job-card-header">
                                <h3 class="company-name">
                                    <i class="fas fa-building"></i>
                                    <?= htmlspecialchars($row['nama_perusahaan']) ?>
                                </h3>
                                <h4 class="job-title"><?= htmlspecialchars($row['judul']) ?></h4>
                            </div>
                            <div class="job-card-body">
                                <p class="location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($row['lokasi']) ?>
                                </p>
                                <div class="job-tags">
                                    <span class="tag"><?= $row['jenis_pekerjaan'] ?></span>
                                    <span class="tag"><?= $row['pendidikan'] ?></span>
                                    <span class="tag"><?= $row['level_pekerjaan'] ?></span>
                                </div>
                            </div>
                            <div class="job-card-footer">
                                <p class="salary">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <?= htmlspecialchars($row['gaji']) ?>
                                </p>
                                <p class="posted-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?= $row['tanggal_posting'] ?>
                                </p>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            </section>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>