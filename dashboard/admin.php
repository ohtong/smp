<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login/login.php");
    exit;
}
include '../config/db.php';

// Filter dan pencarian
$filter_sekolah = isset($_GET['sekolah']) ? $_GET['sekolah'] : '';
$cari_nama = isset($_GET['nama']) ? $_GET['nama'] : '';

// Proses ubah status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    $allowed_status = ['Diterima', 'Ditolak'];
    if (in_array($status, $allowed_status)) {
        $stmt = $conn->prepare("UPDATE ppdb SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Statistik
$total_pendaftar = $conn->query("SELECT COUNT(*) AS total FROM ppdb")->fetch_assoc()['total'];
$pendaftar_terverifikasi = $conn->query("SELECT COUNT(*) AS total FROM ppdb WHERE status = 'Diterima'")->fetch_assoc()['total'];
$pendaftar_ditolak = $conn->query("SELECT COUNT(*) AS total FROM ppdb WHERE status = 'Ditolak'")->fetch_assoc()['total'];
$pendaftar_pending = $conn->query("SELECT COUNT(*) AS total FROM ppdb WHERE status = 'Pending'")->fetch_assoc()['total'];

// Ambil sekolah unik
$sekolah_options = $conn->query("SELECT DISTINCT sekolah_pilihan FROM ppdb");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - E-Gov</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; }
        .sidebar {
            height: 100vh; position: fixed; width: 240px; background-color: #003366;
            padding-top: 70px; color: white;
        }
        .sidebar a {
            color: white; padding: 14px 25px; display: block; font-weight: 600;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #002244; text-decoration: none;
        }
        .content { margin-left: 240px; padding: 40px 50px; }
        .card { border-radius: 10px; }
        .modal-body label { font-weight: bold; }
        @media print {
            .no-print, .btn, .modal { display: none !important; }
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: absolute; left: 0; top: 0; width: 100%; }
        }
        .d-none { display: none !important; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">E-Gov Sekolah</a>
        <div class="ml-auto">
            <a href="../login/logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
    <a href="#" class="active">Dashboard</a>
    <a href="../ppdb/form_ppdb.php">PPDB</a>
    <a href="../pages/acara.php">Acara</a>
    <a href="../pages/kontak.php">Kontak</a>
</div>

<!-- Content -->
<div class="content">
    <h2 class="mb-4">Dashboard Admin</h2>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white p-3">
                <h5>Total Pendaftar</h5>
                <h3><?= $total_pendaftar ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white p-3">
                <h5>Diterima</h5>
                <h3><?= $pendaftar_terverifikasi ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white p-3">
                <h5>Ditolak</h5>
                <h3><?= $pendaftar_ditolak ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark p-3">
                <h5>Pending</h5>
                <h3><?= $pendaftar_pending ?></h3>
            </div>
        </div>
    </div>

    <!-- Filter dan Cari -->
    <form method="GET" class="form-inline mb-3">
        <select name="sekolah" class="form-control mr-2">
            <option value="">-- Semua Sekolah --</option>
            <?php while ($opt = $sekolah_options->fetch_assoc()): ?>
                <option value="<?= $opt['sekolah_pilihan'] ?>" <?= ($filter_sekolah == $opt['sekolah_pilihan']) ? 'selected' : '' ?>>
                    <?= $opt['sekolah_pilihan'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="nama" placeholder="Cari Nama..." class="form-control mr-2" value="<?= htmlspecialchars($cari_nama) ?>">
        <button class="btn btn-primary" type="submit">Filter</button>
    </form>

    <!-- Tabel Data -->
    <div class="card p-4">
        <h4>Data Pendaftar</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Sekolah Pilihan</th>
                        <th>Status</th>
                        <th class="no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1;
                $query_sql = "SELECT ppdb.*, users.nama AS nama_lengkap FROM ppdb JOIN users ON ppdb.user_id = users.id WHERE 1=1";
                if ($filter_sekolah != '') {
                    $filter = $conn->real_escape_string($filter_sekolah);
                    $query_sql .= " AND ppdb.sekolah_pilihan = '$filter'";
                }
                if ($cari_nama != '') {
                    $nama = $conn->real_escape_string($cari_nama);
                    $query_sql .= " AND users.nama LIKE '%$nama%'";
                }
                $query_sql .= " ORDER BY ppdb.id DESC";
                $query = $conn->query($query_sql);

                while ($row = $query->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($row['sekolah_pilihan']) ?></td>
                        <td>
                            <?php
                            $badge = 'secondary';
                            if ($row['status'] == 'Diterima') $badge = 'success';
                            elseif ($row['status'] == 'Ditolak') $badge = 'danger';
                            elseif ($row['status'] == 'Pending') $badge = 'warning';
                            ?>
                            <span class="badge badge-<?= $badge ?>"><?= $row['status'] ?></span>
                        </td>
                        <td class="no-print">
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalDetail<?= $row['id'] ?>">Lihat</button>

                            <form method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="status" value="Diterima">
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Terima pendaftar ini?')">Diterima</button>
                            </form>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="status" value="Ditolak">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pendaftar ini?')">Ditolak</button>
                            </form>

                            <button class="btn btn-secondary btn-sm" onclick="printPerson('print-<?= $row['id'] ?>')">Print</button>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="modalDetail<?= $row['id'] ?>">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title">Detail Pendaftar</h5>
                                    <button class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6"><label>Nama:</label><p><?= $row['nama_lengkap'] ?></p></div>
                                        <div class="col-md-6"><label>Sekolah:</label><p><?= $row['sekolah_pilihan'] ?></p></div>
                                        <div class="col-md-6"><label>Alamat:</label><p><?= $row['alamat'] ?></p></div>
                                        <div class="col-md-6"><label>No. HP:</label><p><?= $row['no_hp'] ?></p></div>
                                        <div class="col-md-6"><label>Status:</label><p><?= $row['status'] ?></p></div>
                                    </div>
                                </div>
                                <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Tutup</button></div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Cetak -->
                    <div id="print-<?= $row['id'] ?>" class="d-none">
                        <div id="print-area">
                            <h2>Data Pendaftar</h2><hr>
                            <p><strong>Nama:</strong> <?= $row['nama_lengkap'] ?></p>
                            <p><strong>Sekolah:</strong> <?= $row['sekolah_pilihan'] ?></p>
                            <p><strong>Alamat:</strong> <?= $row['alamat'] ?></p>
                            <p><strong>No HP:</strong> <?= $row['no_hp'] ?></p>
                            <p><strong>Status:</strong> <?= $row['status'] ?></p>
                            <hr><p><em>Dicetak dari sistem e-Government Sekolah</em></p>
                        </div>
                    </div>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function printPerson(divId) {
    const printContents = document.getElementById(divId).innerHTML;
    const originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
</script>

</body>
</html>
