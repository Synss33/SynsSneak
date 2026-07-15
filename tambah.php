<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_sepatu']);
    $brand  = mysqli_real_escape_string($koneksi, $_POST['brand']);
    $kat    = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga  = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $stok   = mysqli_real_escape_string($koneksi, $_POST['stok_status']);
    $desc   = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $gbr    = mysqli_real_escape_string($koneksi, $_POST['gambar']);

    if (empty($nama) || empty($brand) || empty($kat) || empty($harga) || empty($stok) || empty($desc) || empty($gbr)) {
        $_SESSION['flash'] = 'Gagal! Semua field harus diisi.';
        header("Location: tambah.php");
        exit;
    }

    $sql = "INSERT INTO produk (nama_sepatu, brand, kategori, harga, stok_status, deskripsi, gambar)
            VALUES ('$nama', '$brand', '$kat', '$harga', '$stok', '$desc', '$gbr')";

    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['flash'] = 'Data berhasil ditambahkan!';
        header("Location: index.php#dashboard");
    } else {
        $_SESSION['flash'] = 'Gagal menambahkan data!';
        header("Location: tambah.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Sepatu — Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 p-4 md:p-8">
    <?php if (isset($_SESSION['flash'])) : ?>
    <div class="max-w-2xl mx-auto mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg text-sm font-medium">
        <?php echo $_SESSION['flash']; unset($_SESSION['flash']); ?>
    </div>
    <?php endif; ?>
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-md border border-gray-200">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah Koleksi Sepatu Baru</h2>
        
        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Nama Tipe Sepatu</label>
                <input type="text" name="nama_sepatu" required class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Brand</label>
                    <input type="text" name="brand" placeholder="Contoh: Nike, Adidas" required class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Kategori Lini</label>
                    <select name="kategori" class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                        <option value="Running & Sport">Running & Sport</option>
                        <option value="Classic Canvas">Classic Canvas</option>
                        <option value="Casual & Skateboard">Casual & Skateboard</option>
                        <option value="Limited Edition / Hype">Limited Edition / Hype</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Harga (Rupiah Angka Saja)</label>
                    <input type="number" name="harga" required class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Status Ketersediaan</label>
                    <input type="text" name="stok_status" placeholder="Contoh: Stok Tersedia, Sisa 2 pasang" required class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">URL Link Gambar Sepatu</label>
                <input type="url" name="gambar" placeholder="https://example.com/sepatu.jpg" required class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Deskripsi & Keunggulan Produk</label>
                <textarea name="deskripsi" rows="4" required class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none"></textarea>
            </div>
            
            <div class="pt-4 flex justify-end space-x-2">
                <a href="index.php#dashboard" class="bg-gray-300 text-gray-700 py-2 px-4 rounded-lg font-semibold hover:bg-gray-400">Batal</a>
                <button type="submit" name="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg font-bold hover:bg-blue-700 shadow-md">Simpan Data</button>
            </div>
        </form>
    </div>
</body>
</html>