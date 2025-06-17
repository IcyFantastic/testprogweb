<?php
session_start();
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' AND role='$role'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'pelamar') {
            header("Location: dashboard_pelamar.php");
            exit();
        } elseif ($user['role'] == 'perusahaan') {
            header("Location: dashboard_perusahaan.php");
            exit();
        } elseif ($user['role'] == '') {
            header("Location: dashboard_awal.php");
            exit();
        }
    } else {
        $error = "Username, password, atau role tidak sesuai";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfoLoker - Login</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="js/login.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="main-content">
        <div class="login-container">
            <div class="login-header">
                <h1 class="login-title">Selamat Datang</h1>
                <p class="login-subtitle">Masuk ke akun InfoLoker Anda</p>
            </div>

            <!-- Error Mesasage Display -->
            <?php if(isset($error)): ?>
                <div class="error-message" style="color: red; text-align: center; margin-bottom: 10px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="role-selection">
                <button type="button" class="role-btn active" data-role="pelamar">Pelamar</button>
                <button type="button" class="role-btn" data-role="perusahaan">Perusahaan</button>
            </div>

            <form class="login-form" method="POST" action="">
                <input type="hidden" name="role" id="selectedRole" value="pelamar">
                <div class="input-group">
                    <input type="text" class="input-field" name="username" placeholder="Masukkan username" required>
                    <i class="input-icon">👤</i>
                </div>
                <div class="input-group">
                    <input type="password" class="input-field" name="password" placeholder="Kata Sandi" required>
                    <i class="input-icon">🔒</i>
                </div>
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="rememberMe" name="rememberMe">
                        <label for="rememberMe">Ingat saya</label>
                    </div>
                    <div class="reset-password-link">
                        <a href="reset-password.php">Lupa kata sandi?</a>
                    </div>
                </div>
                <button type="submit" class="login-btn">
                    <span class="btn-text">Masuk</span>
                    <span class="loading"></span>
                </button>
                <div class="register-link">
                    Belum punya akun? <a href="register.php">Daftar Sekarang</a>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>