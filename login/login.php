<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["nama"] = $user["nama"];

        if ($user["role"] == "admin") {
            header("Location: ../dashboard/admin.php");
        } else {
            header("Location: ../dashboard/siswa.php");
        }
        exit;
    } else {
        $error = "Login gagal. Username, password, atau role salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Login Sistem E-Government Sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #0052D4, #4364F7, #6FB1FC);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            padding: 2.5rem 2rem;
            max-width: 400px;
            width: 100%;
            transition: transform 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }
        .login-header {
            font-weight: 700;
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 0.3rem;
            text-align: center;
        }
        .school-subtitle {
            text-align: center;
            color: #5d6d7e;
            font-size: 1rem;
            margin-bottom: 1.8rem;
        }
        label {
            font-weight: 600;
            color: #34495e;
        }
        .btn-primary {
            background: #0052D4;
            border: none;
            font-weight: 600;
            padding: 0.55rem 0;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: #003c99;
        }
        .form-control:focus {
            border-color: #0052D4;
            box-shadow: 0 0 8px rgba(0, 82, 212, 0.5);
        }
        .register-link {
            text-align: center;
            margin-top: 1rem;
        }
        .register-link a {
            color: #0052D4;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .register-link a:hover {
            color: #003c99;
            text-decoration: underline;
        }
        .alert-danger {
            font-size: 0.9rem;
        }
        footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
            user-select: none;
        }
    </style>
</head>
<body>

<div class="login-card shadow-sm">
    <div class="login-header">Sistem E-Government Sekolah</div>
    <div class="school-subtitle">SMPS Ibnu Sina &amp; SMPS 02 Ibnu Sina Kabil</div>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
        <div class="form-group">
            <label for="username">Username</label>
            <input name="username" id="username" class="form-control" placeholder="Masukkan username" required autofocus />
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input name="password" type="password" id="password" class="form-control" placeholder="Masukkan password" required />
        </div>

        <div class="form-group">
            <label for="role">Pilih Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="" disabled selected>-- Pilih Role --</option>
                <option value="siswa">Siswa</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Masuk</button>
    </form>

    <div class="register-link">
        <small>Belum punya akun? <a href="../register/register.php">Daftar di sini</a></small>
    </div>
</div>

<footer>&copy; <?= date("Y") ?> Sistem E-Government Sekolah Ibnu Sina</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
