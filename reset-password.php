<?php
session_start();
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Cek email exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // In real application, send reset password email
        $success = "Link reset password telah dikirim ke email Anda";
    } else {
        $error = "Email tidak ditemukan";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfoLoker - Reset Password</title>
    <link rel="stylesheet" href="css/reset-password.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="main-content">
        <div class="reset-container">
            <div class="reset-header">
                <h1 class="reset-title">Reset Password</h1>
                <p class="reset-subtitle">Masukkan email untuk reset password</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($success)): ?>
                <div class="success-message">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form class="reset-form" method="POST" action="">
                <div class="input-group">
                    <input type="email" class="input-field" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope input-icon'></i>
                </div>

                <button type="submit" class="reset-btn">
                    <span class="btn-text">Kirim Link Reset</span>
                </button>

                <div class="login-link">
                    <a href="login.php">Kembali ke Login</a>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>