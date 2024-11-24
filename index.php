<?php
include 'koneksi.php';

// Tambah Data
if (isset($_POST['add'])) {
    $isi = $_POST['isi'];
    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];
    $sql = "INSERT INTO tasks (isi, tgl_awal, tgl_akhir) VALUES ('$isi', '$tgl_awal', '$tgl_akhir')";
    $conn->query($sql);
    header("Location: index.php");
}

// Hapus Data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM tasks WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
}

// Ubah Status
if (isset($_GET['status'])) {
    $id = $_GET['status'];
    $sql = "UPDATE tasks SET status = IF(status = 'Belum', 'Sudah', 'Belum') WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
}

// Edit Data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $isi = $_POST['isi'];
    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];
    $sql = "UPDATE tasks SET isi='$isi', tgl_awal='$tgl_awal', tgl_akhir='$tgl_akhir' WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
}

// Ambil Data
$tasks = $conn->query("SELECT * FROM tasks");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">To Do List</h2>

        <!-- Form Tambah -->
        <form method="POST" action="" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="isi" class="form-control" placeholder="Isi Tugas" required>
                </div>
                <div class="col-md-3">
                    <input type="date" name="tgl_awal" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <input type="date" name="tgl_akhir" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" type="submit" name="add">Tambah</button>
                </div>
            </div>
        </form>

        <!-- Tabel Data -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nomer</th>
                    <th>kegiatan</th>
                    <th>Tgl Awal</th>
                    <th>Tgl Akhir</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($tasks->num_rows > 0): ?>
                    <?php $no = 1; while ($row = $tasks->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['isi']; ?></td>
                            <td><?php echo $row['tgl_awal']; ?></td>
                            <td><?php echo $row['tgl_akhir']; ?></td>
                            <td>
                                <a href="index.php?status=<?php echo $row['id']; ?>" class="btn btn-<?php echo $row['status'] == 'Belum' ? 'warning' : 'success'; ?>">
                                    <?php echo $row['status']; ?>
                                </a>
                            </td>
                            <td>
                                <a href="index.php?edit=<?php echo $row['id']; ?>" class="btn btn-info">Edit</a>
                                <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Form Edit -->
        <?php if (isset($_GET['edit'])): ?>
            <?php
            $id = $_GET['edit'];
            $task = $conn->query("SELECT * FROM tasks WHERE id=$id")->fetch_assoc();
            ?>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                <div class="mb-3">
                    <label for="isi" class="form-label">kegiatan</label>
                    <input type="text" class="form-control" id="isi" name="isi" value="<?php echo $task['isi']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tgl_awal" class="form-label">Tanggal Awal</label>
                    <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="<?php echo $task['tgl_awal']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tgl_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?php echo $task['tgl_akhir']; ?>" required>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Simpan</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

