<?php
include 'db.php';

// Mengambil nama hari dalam bahasa Indonesia
$hari_indo = [
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
];
$hari_sekarang = $hari_indo[date('l')]; // Hari saat ini

// Query untuk mendapatkan data mahasiswa
$mahasiswaResult = $connection->query("SELECT nim, nama FROM mahasiswa");

// Query untuk mendapatkan mata kuliah sesuai hari dan waktu
$queryMataKuliah = "
    SELECT m.id, m.nama_mata_kuliah 
    FROM jadwal_harian j
    JOIN mata_kuliah m ON j.mata_kuliah_id = m.id
    WHERE j.hari = ? 
      AND CURTIME() BETWEEN j.jam_mulai AND j.jam_selesai
";
$stmt = $connection->prepare($queryMataKuliah);
$stmt->bind_param('s', $hari_sekarang);
$stmt->execute();
$mataKuliahResult = $stmt->get_result();

// Logika untuk menyimpan absensi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $mataKuliahId = $_POST['mata_kuliah'];
    $keterangan = $_POST['keterangan'];

    $stmt = $connection->prepare("INSERT INTO absensi (nim, mata_kuliah_id, keterangan, tanggal) VALUES (?, ?, ?, CURDATE())");
    $stmt->bind_param('sis', $nim, $mataKuliahId, $keterangan);

    if ($stmt->execute()) {
        echo "<p>Absensi berhasil disimpan.</p>";
    } else {
        echo "<p>Gagal menyimpan absensi: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Absensi</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { max-width: 400px; margin: auto; }
        label, select, button { display: block; width: 100%; margin-bottom: 10px; }
        button { padding: 10px; }
    </style>
</head>
<body>
    <h1>Form Absensi</h1>
    <form method="POST">
        <label for="nim">NIM:</label>
        <select name="nim" id="nim" required>
            <option value="" disabled selected>Pilih NIM Anda</option>
            <?php while ($row = $mahasiswaResult->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['nim']) ?>"><?= htmlspecialchars($row['nim']) ?> - <?= htmlspecialchars($row['nama']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="mata_kuliah">Mata Kuliah:</label>
        <select name="mata_kuliah" id="mata_kuliah" required>
            <option value="" disabled selected>Pilih Mata Kuliah</option>
            <?php while ($row = $mataKuliahResult->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['nama_mata_kuliah']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="keterangan">Keterangan:</label>
        <select name="keterangan" id="keterangan" required>
            <option value="" disabled selected>Pilih Keterangan</option>
            <option value="OFFLINE">OFFLINE</option>
            <option value="ONLINE">ONLINE</option>
            <option value="SAKIT">SAKIT</option>
            <option value="IZIN">IZIN</option>
        </select>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
