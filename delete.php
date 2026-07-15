<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $q = "DELETE FROM produk WHERE id = $id";

    if (mysqli_query($koneksi, $q)) {
        $_SESSION['flash'] = 'Data berhasil dihapus!';
    } else {
        $_SESSION['flash'] = 'Gagal menghapus data!';
    }
} else {
    $_SESSION['flash'] = 'ID tidak ditemukan!';
}

header("Location: index.php#dashboard");
exit;
?>