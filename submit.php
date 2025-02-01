<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "lab_booking";

$conn = new mysqli($host, $user, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses penghapusan data jika ada permintaan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM bookings WHERE id = $id");
    header("Location: submit.php");
    exit();
}

// Simpan data dari formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $keperluan = $_POST['keperluan'];
    $kelas = $_POST['kelas'];

    $sql = "INSERT INTO bookings (nama, email, tanggal, waktu, keperluan, kelas) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nama, $email, $tanggal, $waktu, $keperluan, $kelas);

    if ($stmt->execute()) {
        echo "<h2>Pendaftaran berhasil!</h2>";
    } else {
        echo "Terjadi kesalahan: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pendaftaran Ruangan Lab</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1, h2 {
            text-align: center;
            color: #4caf50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #4caf50;
            color: white;
        }

        .notes {
            margin-top: 20px;
            color: #ff5722;
            font-size: 16px;
            text-align: center;
        }

        .btn {
            text-decoration: none;
            font-size: 14px;
            color: white;
            background-color: #f44336;
            padding: 8px 12px;
            border-radius: 5px;
            display: inline-block;
        }

        .btn:hover {
            background-color: #d32f2f;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: white;
            background-color: #2196f3;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-link:hover {
            background-color: #1976d2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Pendaftaran Ruangan Lab</h1>

        <!-- Tabel Data -->
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Keperluan</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM bookings");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nama']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['tanggal']}</td>
                            <td>{$row['waktu']}</td>
                            <td>{$row['keperluan']}</td>
                            <td>{$row['kelas']}</td>
                            <td>
                                <a href='submit.php?hapus={$row['id']}' onclick=\"return confirm('Yakin ingin menghapus data ini?');\" class='btn'>Hapus</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Belum ada data pendaftar.</td></tr>";
            }
            ?>
        </table>

        <!-- Notes -->
        <div class="notes">
            <p>Jika waktu dan tanggal sudah tersedia, silahkan atur kembali waktu dan tanggalnya.</p>
        </div>

        <!-- Tombol Kembali -->
        <a href="index.html" class="back-link">Kembali ke Form Pendaftaran</a>
    </div>
</body>
</html>
