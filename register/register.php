<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];
    $sekolah = $_POST["sekolah"];

    // Prevent SQL Injection & double entry (minimal)
    $stmt = $conn->prepare("INSERT INTO users (nama, username, password, role, sekolah) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama, $username, $password, $role, $sekolah);
    $stmt->execute();

    header("Location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Registrasi Akun - Sistem E-Government Sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(135deg, #004aad, #0099ff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            width: 100%;
            max-width: 480px;
            padding: 2.5rem 2rem;
            transition: transform 0.3s ease;
        }
        .register-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: #0056b3;
            color: white;
            border-radius: 15px 15px 0 0;
            font-weight: 700;
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 1rem 0;
            user-select: none;
        }
        label {
            font-weight: 600;
            color: #2c3e50;
        }
        .form-control:focus {
            border-color: #0056b3;
            box-shadow: 0 0 8px rgba(0, 86, 179, 0.5);
        }
        .btn-primary, .btn-success {
            background-color: #0056b3;
            border-color: #004494;
            font-weight: 600;
            padding: 0.6rem 0;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover, .btn-success:hover {
            background-color: #003c7a;
            border-color: #002f5a;
        }
        .btn-link {
            color: #004494;
            font-weight: 600;
            text-align: center;
            display: block;
            margin-top: 1rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .btn-link:hover {
            color: #002f5a;
            text-decoration: underline;
        }
        small.note {
            color: #666;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
            text-align: center;
            user-select: none;
        }
    </style>
</head>
<body>

<div class="register-card shadow">
    <div class="card-header">Registrasi Akun Pengguna</div>
    <form method="post" autocomplete="off" novalidate>
        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input id="nama" name="nama" type="text" class="form-control" placeholder="Masukkan nama lengkap" required autofocus />
        </div>
        <div class="form-group">
            <label for="username">Username <small class="note">(unik, tanpa spasi)</small></label>
            <input id="username" name="username" type="text" class="form-control" placeholder="Masukkan username" pattern="^\S+$" title="Tidak boleh ada spasi" required />
        </div>
        <div class="form-group">
            <label for="password">Password <small class="note">(minimal 6 karakter)</small></label>
            <input id="password" name="password" type="password" class="form-control" placeholder="Masukkan password" minlength="6" required />
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" class="form-control" required>
                <option value="" disabled selected>-- Pilih Role --</option>
                <option value="siswa">Siswa</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="form-group">
            <label for="sekolah">Sekolah</label>
            <select id="sekolah" name="sekolah" class="form-control" required>
                <option value="" disabled selected>-- Pilih Sekolah --</option>
                <option value="smps_ibnu_sina">SMPS Ibnu Sina</option>
                <option value="smps_02_ibnu_sina">SMPS 02 Ibnu Sina Kabil</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success btn-block">Daftar</button>
    </form>
    <a href="../login/login.php" class="btn-link">Sudah punya akun? Login di sini</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
