<form method="post">
    Nama: <input name="nama"><br>
    Email: <input name="email"><br>
    Pesan: <textarea name="pesan"></textarea><br>
    <button type="submit">Kirim</button>
</form>

<?php
include '../config/db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->query("INSERT INTO kontak (nama, email, pesan) VALUES 
    ('{$_POST['nama']}', '{$_POST['email']}', '{$_POST['pesan']}')");
    echo "Pesan terkirim!";
}
?>
