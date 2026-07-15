<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$sql = "SELECT * FROM produk WHERE id = $id";
$res = mysqli_query($koneksi, $sql);
$row = mysqli_fetch_assoc($res);

if (!$row) {
    $_SESSION['flash'] = 'Data tidak ditemukan!';
    header("Location: index.php#dashboard");
    exit;
}

if (isset($_POST['update'])) {
    $nm   = mysqli_real_escape_string($koneksi, $_POST['nama_sepatu']);
    $brand = mysqli_real_escape_string($koneksi, $_POST['brand']);
    $ktgr = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $sts  = mysqli_real_escape_string($koneksi, $_POST['stok_status']);
    $ket  = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $gmbr = mysqli_real_escape_string($koneksi, $_POST['gambar']);

    if (empty($nm) || empty($brand) || empty($ktgr) || empty($harga) || empty($sts) || empty($ket) || empty($gmbr)) {
        $_SESSION['flash'] = 'Gagal! Semua field harus diisi.';
        header("Location: edit.php?id=$id");
        exit;
    }

    $q = "UPDATE produk SET nama_sepatu='$nm', brand='$brand', kategori='$ktgr',
          harga='$harga', stok_status='$sts', deskripsi='$ket', gambar='$gmbr'
          WHERE id=$id";

    if (mysqli_query($koneksi, $q)) {
        $_SESSION['flash'] = 'Data berhasil diperbarui!';
        header("Location: index.php#dashboard");
    } else {
        $_SESSION['flash'] = 'Gagal memperbarui data!';
        header("Location: edit.php?id=$id");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sepatu — Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 p-4 md:p-8">
    <?php if (isset($_SESSION['flash'])) : ?>
    <div class="max-w-2xl mx-auto mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg text-sm font-medium">
        <?php echo $_SESSION['flash']; unset($_SESSION['flash']); ?>
    </div>
    <?php endif; ?>
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-md border border-gray-200">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Perbarui Data Sepatu</h2>
        
        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Nama Tipe Sepatu</label>
                <input type="text" name="nama_sepatu" value="<?php echo $row['nama_sepatu']; ?>" required class="w-full p-2.5 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Brand</label>
                    <input type="text" name="brand" value="<?php echo $row['brand']; ?>" required class="w-full p-2.5 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Kategori Lini</label>
                    <select name="kategori" class="w-full p-2.5 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="Running & Sport" <?php if($row['kategori'] == 'Running & Sport') echo 'selected'; ?>>Running & Sport</option>
                        <option value="Classic Canvas" <?php if($row['kategori'] == 'Classic Canvas') echo 'selected'; ?>>Classic Canvas</option>
                        <option value="Casual & Skateboard" <?php if($row['kategori'] == 'Casual & Skateboard') echo 'selected'; ?>>Casual & Skateboard</option>
                        <option value="Limited Edition / Hype" <?php if($row['kategori'] == 'Limited Edition / Hype') echo 'selected'; ?>>Limited Edition / Hype</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Harga (Rupiah)</label>
                    <input type="number" name="harga" value="<?php echo $row['harga']; ?>" required class="w-full p-2.5 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Status Ketersediaan</label>
                    <input type="text" name="stok_status" value="<?php echo $row['stok_status']; ?>" required class="w-full p-2.5 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">URL Gambar</label>
                <input type="url" name="gambar" value="<?php echo $row['gambar']; ?>" required class="w-full p-2.5 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="4" required class="w-full p-2.5 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-amber-500"><?php echo $row['deskripsi']; ?></textarea>
            </div>
            
            <div class="pt-4 flex justify-end space-x-2">
                <a href="index.php#dashboard" class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg font-semibold hover:bg-gray-400">Batal</a>
                <button type="submit" name="update" class="bg-blue-500 text-white py-2 px-6 rounded-lg font-semibold hover:bg-blue-600 shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>
</html>