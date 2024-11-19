<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tanggal'])) {
    $tanggal = $_POST['tanggal'];

    $stmt = $connection->prepare("SELECT absensi.*, mahasiswa.nama, mata_kuliah.nama_mata_kuliah 
                                  FROM absensi 
                                  JOIN mahasiswa ON absensi.nim = mahasiswa.nim 
                                  JOIN mata_kuliah ON absensi.mata_kuliah_id = mata_kuliah.id 
                                  WHERE absensi.tanggal = ?");
    $stmt->bind_param('s', $tanggal);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=laporan_absensi_' . $tanggal . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['NIM', 'Nama', 'Mata Kuliah', 'Keterangan', 'Tanggal']);

        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [$row['nim'], $row['nama'], $row['nama_mata_kuliah'], $row['keterangan'], $row['tanggal']]);
        }

        fclose($output);
        exit();
    } else {
        echo "Tidak ada data absensi untuk tanggal tersebut.";
    }
} else {
    echo "Akses tidak valid.";
}
?>
