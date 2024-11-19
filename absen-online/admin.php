<?php
include 'db.php';

// Query untuk menampilkan jadwal kuliah
$jadwalResult = $connection->query("
    SELECT j.id, m.nama_mata_kuliah, j.hari, j.jam_mulai, j.jam_selesai 
    FROM jadwal_harian j 
    JOIN mata_kuliah m ON j.mata_kuliah_id = m.id
");

// Proses update jadwal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_jadwal'])) {
    $id = $_POST['jadwal_id'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    if ($jamMulai >= $jamSelesai) {
        echo "<p style='color: red;'>Error: Jam selesai harus lebih besar dari jam mulai.</p>";
        exit;
    }

    $stmt = $connection->prepare("
        UPDATE jadwal_harian 
        SET hari = ?, jam_mulai = ?, jam_selesai = ? 
        WHERE id = ?
    ");
    $stmt->bind_param('sssi', $hari, $jam_mulai, $jam_selesai, $id);

    if ($stmt->execute()) {
        echo "<p>Jadwal berhasil diperbarui.</p>";
    } else {
        echo "<p>Gagal memperbarui jadwal: " . $stmt->error . "</p>";
    }

    $stmt->close();
    header("Location: admin.php"); // Refresh halaman
    exit;
}

// Query laporan absensi jika tanggal dipilih
if (isset($_GET['tanggal'])) {
    $tanggal = $_GET['tanggal'];

    $stmt = $connection->prepare("
        SELECT absensi.*, mahasiswa.nama, mata_kuliah.nama_mata_kuliah 
        FROM absensi 
        JOIN mahasiswa ON absensi.nim = mahasiswa.nim 
        JOIN mata_kuliah ON absensi.mata_kuliah_id = mata_kuliah.id 
        WHERE absensi.tanggal = ?
    ");
    $stmt->bind_param('s', $tanggal);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Laporan Absensi dan Pengelolaan Jadwal</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        form { max-width: 300px; margin: auto; }
        label, input, select, button { display: block; width: 100%; margin-bottom: 10px; }
        button { padding: 10px; }
    </style>
</head>
<body>
    <h1>Pengelolaan Jadwal dan Laporan Absensi</h1>

    <!-- Tabel Jadwal Kuliah -->
    <h2>Jadwal Kuliah</h2>
    <table>
        <tr>
            <th>Mata Kuliah</th>
            <th>Hari</th>
            <th>Jam Mulai</th>
            <th>Jam Selesai</th>
            <th>Edit</th>
        </tr>
        <?php while ($row = $jadwalResult->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nama_mata_kuliah']) ?></td>
                <td><?= htmlspecialchars($row['hari']) ?></td>
                <td><?= htmlspecialchars($row['jam_mulai']) ?></td>
                <td><?= htmlspecialchars($row['jam_selesai']) ?></td>
                <td>
                    <button onclick="openEditForm(<?= $row['id'] ?>, '<?= $row['hari'] ?>', '<?= $row['jam_mulai'] ?>', '<?= $row['jam_selesai'] ?>')">
                        Edit
                    </button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Form Edit Jadwal -->
    <div id="editForm" style="display: none; margin-top: 20px; border: 1px solid #ddd; padding: 15px; background-color: #f9f9f9;">
        <h3>Edit Jadwal</h3>
        <form method="POST" onsubmit="return validateEditForm()">
            <input type="hidden" name="jadwal_id" id="jadwal_id">
            
            <label for="hari">Hari:</label>
            <select name="hari" id="hari" required>
                <option value="Senin">Senin</option>
                <option value="Selasa">Selasa</option>
                <option value="Rabu">Rabu</option>
                <option value="Kamis">Kamis</option>
                <option value="Jumat">Jumat</option>
                <option value="Sabtu">Sabtu</option>
                <option value="Minggu">Minggu</option>
            </select>
            
            <label for="jam_mulai">Jam Mulai:</label>
            <input type="time" name="jam_mulai" id="jam_mulai" required>
            
            <label for="jam_selesai">Jam Selesai:</label>
            <input type="time" name="jam_selesai" id="jam_selesai" required>
            
            <button type="submit" name="update_jadwal">Simpan</button>
            <button type="button" onclick="closeEditForm()">Batal</button>
        </form>
    </div>



    <!-- Form Laporan Absensi -->
    <h2>Laporan Absensi</h2>
    <form method="GET">
        <label for="tanggal">Pilih Tanggal:</label>
        <input type="date" name="tanggal" id="tanggal" required>
        <button type="submit">Tampilkan</button>
    </form>

    <?php if (isset($result) && $result->num_rows > 0): ?>
        <table>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Mata Kuliah</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nim']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['nama_mata_kuliah']) ?></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php elseif (isset($tanggal)): ?>
        <p>Tidak ada data absensi untuk tanggal tersebut.</p>
    <?php endif; ?>

    <script>
        function openEditForm(id, hari, jamMulai, jamSelesai) {
            const editForm = document.getElementById('editForm');
            const jadwalIdInput = document.getElementById('jadwal_id');
            const hariInput = document.getElementById('hari');
            const jamMulaiInput = document.getElementById('jam_mulai');
            const jamSelesaiInput = document.getElementById('jam_selesai');

            // Masukkan data ke form
            jadwalIdInput.value = id;
            hariInput.value = hari;
            jamMulaiInput.value = jamMulai;
            jamSelesaiInput.value = jamSelesai;

            // Tampilkan form edit
            editForm.style.display = 'block';
            editForm.scrollIntoView({ behavior: 'smooth' });
        }

        function closeEditForm() {
            const editForm = document.getElementById('editForm');
            editForm.style.display = 'none';
        }

        function validateEditForm() {
            const jamMulai = document.getElementById('jam_mulai').value;
            const jamSelesai = document.getElementById('jam_selesai').value;

            if (jamMulai >= jamSelesai) {
                alert('Jam selesai harus lebih dulu dari jam mulai.');
                return false;
            }
            return true;
        }
    </script>


</body>
</html>
