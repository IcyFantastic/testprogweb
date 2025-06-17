<?php
session_start();
// Periksa apakah pengguna sudah login dan memiliki peran sebagai pelamar
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'pelamar') {
    header("Location: login.php");
    exit();
}
require 'koneksi.php';

$userId = $_SESSION['id'];
$lowonganId = $_GET['id'] ?? null;

// Periksa apakah ID lowongan valid
if (!$lowonganId) {
    echo "<script>alert('ID lowongan tidak valid.'); window.location='dashboard_pelamar.php';</script>";
    exit();
}

// Ambil data pelamar yang sudah ada jika tersedia
$getPelamar = mysqli_query($conn, "SELECT * FROM pelamar WHERE user_id = '$userId'");
$pelamar = mysqli_fetch_assoc($getPelamar);

// Periksa apakah pelamar sudah melamar lowongan ini sebelumnya
if ($pelamar) {
    $pelamarId = $pelamar['id'];
    $cekQuery = "SELECT * FROM lamaran WHERE pelamar_id = ? AND lowongan_id = ?";
    $stmt = mysqli_prepare($conn, $cekQuery);
    mysqli_stmt_bind_param($stmt, "ii", $pelamarId, $lowonganId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Anda sudah pernah melamar lowongan ini'); window.location='detail_lowongan.php?id=$lowonganId';</script>";
        exit();
    }
}

// Update the uploadFile function with size validation
function uploadFile($file, $folder) {
    // Maximum file size 5MB
    $maxSize = 5 * 1024 * 1024;
    
    if ($file['size'] > 0) {
        // Validate file size
        if ($file['size'] > $maxSize) {
            throw new Exception("File " . basename($file['name']) . " melebihi batas maksimal 5MB!");
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $namaFile = uniqid() . "." . $ext;
        $target = "uploads/$folder/" . $namaFile;
        
        // Create directory if not exists
        if (!is_dir("uploads/$folder")) {
            mkdir("uploads/$folder", 0777, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $target)) {
            return $namaFile; // Return only filename for DB storage
        } else {
            throw new Exception("Gagal mengupload file " . basename($file['name']));
        }
    }
    return null;
}

// Update the form processing section
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $nama = $_POST['nama'];
        $tgl = $_POST['tanggal_lahir'];
        $email = $_POST['email'];
        $nohp = $_POST['no_hp'];

        // Process file uploads with validation
        $cvName = uploadFile($_FILES['cv'], 'cv');
        $portoName = uploadFile($_FILES['portofolio'], 'portofolio');
        $suratName = uploadFile($_FILES['surat'], 'surat');

        // Start transaction
        mysqli_begin_transaction($conn);

        // Insert or update pelamar data
        if (!$pelamar) {
            $stmt = mysqli_prepare($conn, "INSERT INTO pelamar (user_id, nama_lengkap, tanggal_lahir, no_hp, email) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "issss", $userId, $nama, $tgl, $nohp, $email);
            mysqli_stmt_execute($stmt);
            $pelamarId = mysqli_insert_id($conn);
        } else {
            $pelamarId = $pelamar['id'];
            $stmt = mysqli_prepare($conn, "UPDATE pelamar SET nama_lengkap = ?, tanggal_lahir = ?, no_hp = ?, email = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "ssssi", $nama, $tgl, $nohp, $email, $pelamarId);
            mysqli_stmt_execute($stmt);
        }

        // Insert lamaran with file paths
        $stmt = mysqli_prepare($conn, "INSERT INTO lamaran (pelamar_id, lowongan_id, cv, portofolio, surat_lamaran) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iisss", $pelamarId, $lowonganId, $cvName, $portoName, $suratName);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_commit($conn);
            echo "<script>alert('✅ Berhasil Mengirim Lamaran'); window.location='dashboard_pelamar.php';</script>";
            exit();
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Lamaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/lamaran.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <nav class="breadcrumb">
        <div class="breadcrumb-content">
            <a href="dashboard_pelamar.php">Home</a> / <span>Form Lamaran</span>
        </div>
    </nav>

    <div class="main-content">
        <section id="apply">
            <h2 class="section-title">Form Pengajuan Lamaran</h2>

            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" 
                               value="<?= htmlspecialchars($pelamar['nama_lengkap'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" 
                               value="<?= $pelamar['tanggal_lahir'] ?? '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" 
                               value="<?= htmlspecialchars($pelamar['email'] ?? '') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="no_hp">Nomor HP</label>
                        <input type="tel" id="no_hp" name="no_hp" 
                               value="<?= htmlspecialchars($pelamar['no_hp'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="cv">Unggah CV (PDF/DOCX)</label>
                        <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                        <small>Format yang didukung: PDF, DOC, DOCX. Maksimal 5MB</small>
                    </div>

                    <div class="form-group">
                        <label for="portofolio">Unggah Portofolio (Opsional)</label>
                        <input type="file" id="portofolio" name="portofolio" accept=".pdf">
                        <small>Format yang didukung: PDF. Maksimal 5MB</small>
                    </div>

                    <div class="form-group">
                        <label for="surat">Unggah Surat Lamaran (Opsional)</label>
                        <input type="file" id="surat" name="surat" accept=".pdf">
                        <small>Format yang didukung: PDF. Maksimal 5MB</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="tombol-lamaran">Kirim Lamaran</button>
                        <button type="button" class="tombol-lamaran tombol-batal" 
                                onclick="window.location.href='dashboard_pelamar.php'">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
