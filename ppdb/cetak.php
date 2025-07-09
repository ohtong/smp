<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login/login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$result = $conn->query("SELECT * FROM ppdb WHERE user_id = '$user_id'");
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pendaftaran - e-Government Sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            height: 80px;
            margin-bottom: 10px;
        }
        .print-area {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .title {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .label {
            font-weight: bold;
        }
        .btn-area {
            margin-top: 20px;
            text-align: center;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #printable, #printable * {
                visibility: visible;
            }
            #printable {
                position: absolute;
                left: 0;
                top: 0;
            }
            .btn-area {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div id="printable" class="print-area">

        <div class="header">
            <img src="https://via.placeholder.com/100x100.png?text=Logo" alt="Logo Sekolah">
            <h4>Pemerintah Kabupaten Contoh</h4>
            <h5>Sistem PPDB - e-Government Sekolah</h5>
        </div>

        <div class="title text-center">
            <h4><strong>Bukti Pendaftaran Siswa Baru</strong></h4>
        </div>

        <table class="table table-borderless">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td>: <?= htmlspecialchars($data['nama_lengkap']) ?></td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td>: <?= htmlspecialchars($data['nisn']) ?></td>
            </tr>
            <tr>
                <td class="label">Asal Sekolah</td>
                <td>: <?= htmlspecialchars($data['asal_sekolah']) ?></td>
            </tr>
            <tr>
                <td class="label">Sekolah Pilihan</td>
                <td>: <?= htmlspecialchars($data['sekolah_pilihan']) ?></td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td>: <?= htmlspecialchars($data['alamat']) ?></td>
            </tr>
            <tr>
                <td class="label">No. HP</td>
                <td>: <?= htmlspecialchars($data['no_hp']) ?></td>
            </tr>
            <tr>
                <td class="label">Status Pendaftaran</td>
                <td>: 
                    <?php
                        $status = $data['status'];
                        $badge = 'secondary';
                        if ($status === 'Diterima') $badge = 'success';
                        elseif ($status === 'Ditolak') $badge = 'danger';
                        elseif ($status === 'Pending') $badge = 'warning';
                    ?>
                    <span class="badge badge-<?= $badge ?>"><?= $status ?></span>
                </td>
            </tr>
        </table>

        <p class="mt-4"><em>Silakan simpan atau cetak bukti ini sebagai tanda bahwa Anda telah mendaftar di sekolah tujuan.</em></p>
        <p><strong>Tanggal Pendaftaran:</strong> <?= date("d F Y", strtotime($data['created_at'] ?? date("Y-m-d"))) ?></p>
    </div>

    <div class="btn-area">
        <button class="btn btn-primary mr-2" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Bukti Pendaftaran
        </button>
        <a href="../dashboard/siswa.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<!-- Font Awesome & Bootstrap JS -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
