<?php
session_start();
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'pelamar';

    // Validasi username
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Username sudah digunakan";
    } else {
        // Insert user baru
        $query = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sss", $username, $password, $role);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['register_success'] = true;
            header("Location: login.php");
            exit();
        } else {
            $error = "Gagal mendaftar: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfoLoker - Daftar Akun</title>
    <link rel="stylesheet" href="css/register.css">
    <script src="js/register.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="main-content">
        <div class="register-container">
            <div class="register-header">
                <h1 class="register-title">Daftar Akun</h1>
                <p class="register-subtitle">Buat akun InfoLoker baru</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="role-selection">
                <button type="button" class="role-btn active" data-role="pelamar">
                    <i class='bx bxs-user'></i> Pelamar
                </button>
                <button type="button" class="role-btn" data-role="perusahaan">
                    <i class='bx bxs-briefcase'></i> Perusahaan
                </button>
            </div>

            <form class="register-form" method="POST" action="">
                <input type="hidden" name="role" id="selectedRole" value="pelamar">
                
                <div class="input-group">
                    <input type="text" class="input-field" name="username" placeholder="Username" required>
                    <i class='bx bxs-user input-icon'></i>
                </div>

                <div class="input-group">
                    <input type="email" class="input-field" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope input-icon'></i>
                </div>

                <div class="input-group">
                    <input type="password" class="input-field" name="password" placeholder="Kata Sandi" required>
                    <i class='bx bxs-lock-alt input-icon'></i>
                </div>

                <button type="submit" class="register-btn">
                    <span class="btn-text">Daftar</span>
                </button>

                <div class="login-link">
                    Sudah punya akun? <a href="login.php">Masuk</a>
                </div>
            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>