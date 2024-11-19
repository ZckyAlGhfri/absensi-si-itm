<?php
$servername = "localhost"; 
$username = "root";    
$password = "";    
$dbname = "absensi";            

// Membuat koneksi
$connection = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($connection->connect_error) {
    die("Koneksi gagal: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_jadwal'])) {
    $mataKuliahId = $_POST['mata_kuliah'];
    $hari = $_POST['hari'];
    $jamMulai = $_POST['jam_mulai'];
    $jamSelesai = $_POST['jam_selesai'];

    // Validasi waktu
    if (strtotime($jamMulai) >= strtotime($jamSelesai)) {
        echo "<p style='color: red;'>Jam mulai harus lebih awal dari jam selesai.</p>";
    } else {
        // Update jadwal di database
        $stmt = $connection->prepare("UPDATE jadwal_harian SET hari = ?, jam_mulai = ?, jam_selesai = ? WHERE mata_kuliah_id = ?");
        $stmt->bind_param('sssi', $hari, $jamMulai, $jamSelesai, $mataKuliahId);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Jadwal berhasil diperbarui.</p>";
        } else {
            echo "<p style='color: red;'>Gagal memperbarui jadwal: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}

?>
