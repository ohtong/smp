<?php
session_start();
include '../config/db.php';

$success = "";

// Cek apakah user sudah login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama_lengkap"];
    $nisn = $_POST["nisn"];
    $nama_orang_tua = $_POST["nama_orang_tua"];
    $asal = $_POST["asal_sekolah"];
    $alamat = $_POST["alamat"];
    $pilihan = $_POST["sekolah_pilihan"];
    $user_id = $_SESSION["user_id"];

    // Simpan ke database
    $sql = "INSERT INTO ppdb (user_id, nama_lengkap, nisn, nama_orang_tua, asal_sekolah, alamat, sekolah_pilihan) 
            VALUES ('$user_id', '$nama', '$nisn', '$nama_orang_tua', '$asal', '$alamat', '$pilihan')";

    if ($conn->query($sql) === TRUE) {
        $success = "Formulir berhasil dikirim!";
    } else {
        $success = "Terjadi kesalahan: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir PPDB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background: #f2f6fc;
        }
        .card {
            border-radius: 15px;
        }
        .card-header {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <?php if (!empty($success)) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $success ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Formulir Pendaftaran PPDB</h4>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>NISN</label>
                            <input type="text" name="nisn" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Nama Orang Tua</label>
                            <input type="text" name="nama_orang_tua" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Asal Sekolah</label>
                            <input type="text" name="asal_sekolah" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Sekolah Pilihan</label>
                            <select name="sekolah_pilihan" class="form-control" required>
                                <option value="">-- Pilih Sekolah --</option>
                                <option value="smps_ibnu_sina">SMPS Ibnu Sina</option>
                                <option value="smps_02_ibnu_sina">SMPS 02 Ibnu Sina Kabil</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">Kirim Pendaftaran</button>
                        <a href="../dashboard/siswa.php" class="btn btn-secondary btn-block">← Kembali ke Dashboard</a>
                    </form>
                </div>
                <div class="card-footer text-center text-muted">
                    E-Government Sistem Sekolah | PPDB Online
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS & jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
