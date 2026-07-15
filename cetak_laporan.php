<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'assets/fpdf/fpdf.php';

$q = "SELECT * FROM produk ORDER BY id DESC";
$data = mysqli_query($koneksi, $q);

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->SetTitle('Laporan Stok Produk - SynsSneak');
$pdf->SetAuthor('SynsSneak Admin');
$pdf->AddPage();

$pdf->SetFont('Helvetica', 'B', 18);
$pdf->SetTextColor(44, 18, 18);
$pdf->Cell(0, 10, 'SynsSneak - Laporan Stok Produk', 0, 1, 'L');
$pdf->SetFont('Helvetica', '', 10);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 6, 'Pusat Sepatu Original & Bervariasi', 0, 1, 'L');
$pdf->Cell(0, 6, 'Tanggal Cetak: ' . date('d/m/Y H:i:s'), 0, 1, 'L');
$pdf->Ln(8);

$pdf->SetFont('Helvetica', 'B', 10);
$pdf->SetFillColor(44, 18, 18);
$pdf->SetTextColor(255, 255, 255);

$w = array(10, 50, 35, 40, 35, 35);
$h = array('No', 'Nama Sepatu', 'Brand', 'Kategori', 'Harga', 'Status Stok');

for ($i = 0; $i < count($h); $i++) {
    $pdf->Cell($w[$i], 8, $h[$i], 1, 0, 'C', true);
}
$pdf->Ln();

$pdf->SetFont('Helvetica', '', 9);
$pdf->SetTextColor(0, 0, 0);
$no = 1;

while ($row = mysqli_fetch_assoc($data)) {
    if ($pdf->GetY() > 180) {
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetFillColor(44, 18, 18);
        $pdf->SetTextColor(255, 255, 255);
        for ($i = 0; $i < count($h); $i++) {
            $pdf->Cell($w[$i], 8, $h[$i], 1, 0, 'C', true);
        }
        $pdf->Ln();
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(0, 0, 0);
    }

    $pdf->Cell($w[0], 7, $no++, 1, 0, 'C');
    $pdf->Cell($w[1], 7, $row['nama_sepatu'], 1);
    $pdf->Cell($w[2], 7, $row['brand'], 1);
    $pdf->Cell($w[3], 7, $row['kategori'], 1);
    $pdf->Cell($w[4], 7, 'Rp ' . number_format($row['harga'], 0, ',', '.'), 1);
    $pdf->Cell($w[5], 7, $row['stok_status'], 1);
    $pdf->Ln();
}

$pdf->Ln(10);
$pdf->SetFont('Helvetica', 'I', 9);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 6, 'Dicetak oleh sistem SynsSneak Admin', 0, 1, 'R');
$pdf->Cell(0, 6, 'Halaman ' . $pdf->PageNo() . '/{nb}', 0, 1, 'R');

$pdf->AliasNbPages();
$pdf->Output('D', 'laporan_stok_sneakervault.pdf');
