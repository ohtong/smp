<?php
$conn = new mysqli("localhost", "root", "", "db_egov");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>