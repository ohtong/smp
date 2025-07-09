<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "siswa") {
    header("Location: ../login/login.php");
    exit;
}
include '../config/db.php';

$status_ppdb = "Belum mendaftar";
$user_id = $_SESSION["user_id"] ?? 0;

if ($user_id) {
    $stmt = $conn->prepare("SELECT status FROM ppdb WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $status_ppdb = $row['status'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Siswa - Sistem E-Government Sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            transition: background-color 0.3s, color 0.3s;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body.dark-mode {
            background-color: #121212;
            color: #eee;
        }
        /* Navbar */
        .navbar {
            box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
            position: sticky;
            top: 0;
            z-index: 1030;
            transition: background-color 0.3s;
        }
        .navbar-dark-mode {
            background-color: #222 !important;
            box-shadow: 0 2px 12px rgba(255,255,255,0.1);
        }
        .navbar-brand {
            font-weight: 800;
            color: #007bff !important;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }
        .navbar-dark-mode .navbar-brand {
            color: #66b2ff !important;
        }
        /* Footer */
        footer.footer {
            background-color: #e9ecef;
            color: #555;
            padding: 1rem 0;
            text-align: center;
            box-shadow: 0 -2px 8px rgb(0 0 0 / 0.05);
            position: sticky;
            bottom: 0;
            width: 100%;
            transition: background-color 0.3s, color 0.3s;
            margin-top: 3rem;
        }
        footer.footer.dark-mode {
            background-color: #222;
            color: #aaa;
            box-shadow: 0 -2px 12px rgba(255,255,255,0.1);
        }
        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, #007bff 0%, #00c6ff 100%);
            color: white;
            border-radius: 1rem;
            padding: 3rem 2rem;
            margin-top: 3rem;
            box-shadow: 0 8px 25px rgba(0,123,255,0.3);
            position: relative;
            overflow: hidden;
        }
        .welcome-card .status-icon {
            font-size: 4rem;
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            opacity: 0.15;
            transition: color 0.3s;
        }
        .status-success {
            color: #28a745 !important;
        }
        .status-danger {
            color: #dc3545 !important;
        }
        .status-pending {
            color: #6c757d !important;
        }
        /* Action buttons */
        .action-btns a.btn {
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.75rem 1.5rem;
            box-shadow: 0 6px 12px rgb(0 123 255 / 0.3);
            transition: all 0.3s ease;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .action-btns a.btn i {
            margin-right: 0.5rem;
            font-size: 1.3rem;
        }
        .action-btns a.btn:hover {
            box-shadow: 0 12px 24px rgb(0 123 255 / 0.5);
            transform: translateY(-3px);
        }
        /* Cards Info Sekolah */
        .info-card {
            transition: box-shadow 0.3s ease;
            border-radius: 1rem;
        }
        .info-card:hover {
            box-shadow: 0 0 20px rgba(0,123,255,0.4);
        }
        /* Dark mode tweaks */
        body.dark-mode .welcome-card {
            background: linear-gradient(135deg, #0056b3 0%, #0086d1 100%);
            box-shadow: 0 8px 25px rgba(0,123,255,0.7);
        }
        body.dark-mode .action-btns a.btn {
            box-shadow: 0 6px 12px rgb(102 178 255 / 0.7);
        }
        body.dark-mode .action-btns a.btn:hover {
            box-shadow: 0 12px 24px rgb(102 178 255 / 1);
        }
        body.dark-mode .info-card:hover {
            box-shadow: 0 0 25px rgba(102,178,255,0.7);
        }
        /* Loading spinner */
        .loading-spinner {
            display: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 3px solid #fff;
            border-top: 3px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 0.75rem;
        }
        @keyframes spin {
            to { transform: rotate(360deg);}
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="#">E-Gov Sekolah</a>
        <button class="btn btn-outline-primary btn-sm ml-auto mr-2" id="toggleDarkMode" title="Toggle Mode Gelap / Terang">
            <i class="fas fa-moon"></i> Mode Gelap
        </button>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto font-weight-bold">
                <li class="nav-item"><a href="../ppdb/form_ppdb.php" class="nav-link">Form PPDB</a></li>
                <li class="nav-item"><a href="../ppdb/cetak.php" class="nav-link">Cetak Hasil</a></li>
                <li class="nav-item"><a href="../pages/acara.php" class="nav-link">Acara</a></li>
                <li class="nav-item"><a href="../pages/kontak.php" class="nav-link">Kontak</a></li>
                <li class="nav-item"><a href="../login/logout.php" class="nav-link text-danger font-weight-bold">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <!-- Alert Welcome -->
    <div class="alert alert-info alert-dismissible fade show mt-4" role="alert" id="welcomeAlert">
        <i class="fas fa-user-circle"></i> Selamat datang, <strong><?= htmlspecialchars($_SESSION["nama"]) ?></strong>! Gunakan dashboard ini untuk mengakses layanan sekolah.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" title="Tutup pesan">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <!-- Welcome Card -->
    <div class="welcome-card text-center position-relative">
        <h1 class="font-weight-bold mb-3">Halo, <strong><?= htmlspecialchars($_SESSION["nama"]) ?></strong>!</h1>
        <p class="lead mb-4">Status PPDB Anda saat ini:</p>

        <?php
        $statusClass = "status-pending";
        $statusIcon = "fas fa-hourglass-half";
        if ($status_ppdb == 'Diterima') {
            $statusClass = "status-success";
            $statusIcon = "fas fa-check-circle";
        } elseif ($status_ppdb == 'Ditolak') {
            $statusClass = "status-danger";
            $statusIcon = "fas fa-times-circle";
        }
        ?>
        <div class="d-flex justify-content-center align-items-center">
            <i class="<?= $statusIcon ?> fa-3x mr-3 <?= $statusClass ?> status-icon"></i>
            <span class="badge badge-pill badge-lg badge-<?= $statusClass === 'status-success' ? 'success' : ($statusClass === 'status-danger' ? 'danger' : 'secondary') ?> status-badge py-3 px-4" style="font-size:1.3rem;">
                <?= htmlspecialchars($status_ppdb) ?>
            </span>
        </div>

        <div class="action-btns d-flex flex-column flex-sm-row justify-content-center mt-5">
            <a href="../ppdb/form_ppdb.php" class="btn btn-primary mx-sm-3 mb-3 mb-sm-0" onclick="showLoading(this)" data-toggle="tooltip" data-placement="top" title="Isi Formulir PPDB">
                <i class="fas fa-file-alt"></i> Isi Form PPDB <span class="loading-spinner"></span>
            </a>
            <a href="../ppdb/cetak.php" class="btn btn-success mx-sm-3 mb-3 mb-sm-0" onclick="showLoading(this)" data-toggle="tooltip" data-placement="top" title="Cetak Bukti Hasil PPDB">
                <i class="fas fa-print"></i> Cetak Hasil PPDB <span class="loading-spinner"></span>
            </a>
            <a href="../pages/acara.php" class="btn btn-info mx-sm-3 mb-3 mb-sm-0" onclick="showLoading(this)" data-toggle="tooltip" data-placement="top" title="Lihat Acara Sekolah">
                <i class="fas fa-calendar-alt"></i> Acara Sekolah <span class="loading-spinner"></span>
            </a>
            <a href="../pages/kontak.php" class="btn btn-secondary mx-sm-3 mb-3 mb-sm-0" onclick="showLoading(this)" data-toggle="tooltip" data-placement="top" title="Kontak Kami">
                <i class="fas fa-address-book"></i> Kontak <span class="loading-spinner"></span>
            </a>
        </div>
    </div>

    <!-- Info Sekolah -->
    <div class="row mt-5">
        <div class="col-md-6 mb-4">
            <div class="card info-card shadow p-4 h-100">
                <h4 class="mb-3"><i class="fas fa-school text-primary"></i> Tentang Sekolah</h4>
                <p><strong>SMPS Ibnu Sina & SMPS 02 Ibnu Sina Kabil</strong> adalah sekolah yang menyediakan layanan e-government untuk memudahkan proses administrasi dan komunikasi antara siswa dan pihak sekolah.</p>
                <p>Dengan sistem ini, Anda dapat melakukan pendaftaran, cetak bukti, dan mengakses informasi penting lainnya dengan mudah.</p>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card info-card shadow p-4 h-100">
                <h4 class="mb-3"><i class="fas fa-envelope text-success"></i> Kontak Admin</h4>
                <p>Untuk bantuan atau pertanyaan, silakan hubungi admin kami:</p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-envelope"></i> Email: <a href="mailto:admin@ibnusina.sch.id">admin@ibnusina.sch.id</a></li>
                    <li><i class="fas fa-phone"></i> Telepon: <a href="tel:+62123456789">+62 123 4567 89</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<footer class="footer" id="footer">
    &copy; <?= date("Y") ?> Sistem E-Government Sekolah Ibnu Sina - All rights reserved.
</footer>

<!-- Bootstrap + jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const toggleBtn = document.getElementById('toggleDarkMode');
    const body = document.body;
    const navbar = document.getElementById('navbar');
    const footer = document.getElementById('footer');

    // Load mode from localStorage
    if(localStorage.getItem('darkMode') === 'enabled'){
        enableDarkMode();
    }

    toggleBtn.addEventListener('click', () => {
        if(body.classList.contains('dark-mode')){
            disableDarkMode();
        } else {
            enableDarkMode();
        }
    });

    function enableDarkMode(){
        body.classList.add('dark-mode');
        navbar.classList.add('navbar-dark-mode');
        footer.classList.add('dark-mode');
        toggleBtn.innerHTML = '<i class="fas fa-sun"></i> Mode Terang';
        localStorage.setItem('darkMode', 'enabled');
    }

    function disableDarkMode(){
        body.classList.remove('dark-mode');
        navbar.classList.remove('navbar-dark-mode');
        footer.classList.remove('dark-mode');
        toggleBtn.innerHTML = '<i class="fas fa-moon"></i> Mode Gelap';
        localStorage.setItem('darkMode', 'disabled');
    }

    // Loading spinner on buttons
    function showLoading(button) {
        const spinner = button.querySelector('.loading-spinner');
        spinner.style.display = 'inline-block';
        // Simulasi loading, ganti dengan ajax atau lainnya jika perlu
        setTimeout(() => {
            spinner.style.display = 'none';
        }, 1500);
    }

    // Enable tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

</body>
</html>
