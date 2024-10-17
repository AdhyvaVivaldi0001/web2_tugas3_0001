<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'db_pelanggan';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Fungsi untuk menambah data
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $sql = "INSERT INTO pelanggan (nama, alamat, no_hp) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nama, $alamat, $no_hp]);
    header("Location: crud.php");
}

// Fungsi untuk menghapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $sql = "DELETE FROM pelanggan WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header("Location: crud.php");
}

// Fungsi untuk mengupdate data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $sql = "UPDATE pelanggan SET nama = ?, alamat = ?, no_hp = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nama, $alamat, $no_hp, $id]);
    header("Location: crud.php");
}

// Mendapatkan data pelanggan
$sql = "SELECT * FROM pelanggan";
$stmt = $pdo->query($sql);
$pelanggan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ADHYVA VIVALDI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px 0;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .button {
            padding: 10px 20px;
            background-color: #008CBA;
            color: white;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .button:hover {
            background-color: #005f75;
        }

        .button.red {
            background-color: #f44336;
        }

        .button.red:hover {
            background-color: #da190b;
        }
    </style>
</head>
<body>
    <h1>PROGRAM CRUD ADHYVA VIVALDI AL LUQMAN - 23.230.0001</h1>
    <h1>PEMOGRAMAN WEB II</h1>

    <!-- Form Tambah Data -->
    <form method="POST" action="crud.php">
        <input type="text" name="nama" placeholder="Nama" required>
        <input type="text" name="alamat" placeholder="Alamat" required>
        <input type="text" name="no_hp" placeholder="No HP" required>
        <input type="submit" name="tambah" value="Tambah">
    </form>

    <!-- Tabel Data Pelanggan -->
    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($pelanggan as $index => $row): ?>
        <tr>
            <td><?php echo $index + 1; ?></td>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
            <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
            <td>
                <a class="button" href="crud.php?edit=<?php echo $row['id']; ?>">Edit</a>
                <a class="button red" href="crud.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php
    // Form Edit Data
    if (isset($_GET['edit'])):
        $id = $_GET['edit'];
        $sql = "SELECT * FROM pelanggan WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row):
    ?>
    <h2>Edit Data Pelanggan</h2>
    <form method="POST" action="crud.php">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="text" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
        <input type="text" name="alamat" value="<?php echo htmlspecialchars($row['alamat']); ?>" required>
        <input type="text" name="no_hp" value="<?php echo htmlspecialchars($row['no_hp']); ?>" required>
        <input type="submit" name="update" value="Update">
    </form>
    <?php endif; endif; ?>

</body>
</html>
