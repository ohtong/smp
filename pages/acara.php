<?php
include '../config/db.php';

echo "<h2>Acara Sekolah</h2>";
$result = $conn->query("SELECT * FROM acara ORDER BY tanggal DESC");
while ($row = $result->fetch_assoc()) {
    echo "<p><b>{$row['judul']}</b><br>{$row['tanggal']}<br>{$row['deskripsi']}</p>";
}
