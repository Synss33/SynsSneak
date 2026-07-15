<?php 
session_start();
include 'koneksi.php';

if (isset($_POST['submit_order'])) {
    $product_name  = mysqli_real_escape_string($koneksi, $_POST['product_name']);
    $customer_name = mysqli_real_escape_string($koneksi, $_POST['customer_name']);
    $customer_email = mysqli_real_escape_string($koneksi, $_POST['customer_email']);
    $customer_phone = mysqli_real_escape_string($koneksi, $_POST['customer_phone']);
    $address       = mysqli_real_escape_string($koneksi, $_POST['address']);
    $size          = mysqli_real_escape_string($koneksi, $_POST['size']);
    $quantity      = (int)$_POST['quantity'];
    $total_price   = (int)$_POST['total_price'];

    $q = "INSERT INTO orders (product_name, customer_name, customer_email, customer_phone, address, size, quantity, total_price)
          VALUES ('$product_name','$customer_name','$customer_email','$customer_phone','$address','$size',$quantity,$total_price)";
    if (mysqli_query($koneksi, $q)) {
        $_SESSION['flash_order'] = 'Pesanan berhasil dikirim! Kami akan menghubungi Anda segera.';
    } else {
        $_SESSION['flash_order'] = 'Gagal mengirim pesanan. Silakan coba lagi.';
    }
    header("Location: index.php#products");
    exit;
}

if (isset($_POST['submit_contact'])) {
    $name    = mysqli_real_escape_string($koneksi, $_POST['contact_name']);
    $email   = mysqli_real_escape_string($koneksi, $_POST['contact_email']);
    $message = mysqli_real_escape_string($koneksi, $_POST['contact_message']);

    $q = "INSERT INTO messages (name, email, message) VALUES ('$name','$email','$message')";
    if (mysqli_query($koneksi, $q)) {
        $_SESSION['flash_contact'] = 'Pesan Anda berhasil dikirim! Tim kami akan merespon dalam 1x24 jam.';
    } else {
        $_SESSION['flash_contact'] = 'Gagal mengirim pesan. Silakan coba lagi.';
    }
    header("Location: index.php#contact");
    exit;
}

if (isset($_POST['submit_tambah'])) {
    $nama_sepatu  = mysqli_real_escape_string($koneksi, $_POST['nama_sepatu']);
    $brand        = mysqli_real_escape_string($koneksi, $_POST['brand']);
    $kategori     = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga        = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $stok_status  = mysqli_real_escape_string($koneksi, $_POST['stok_status']);
    $deskripsi    = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $gambar       = mysqli_real_escape_string($koneksi, $_POST['gambar']);

    $q = "INSERT INTO produk (nama_sepatu, brand, kategori, harga, stok_status, deskripsi, gambar)
          VALUES ('$nama_sepatu', '$brand', '$kategori', '$harga', '$stok_status', '$deskripsi', '$gambar')";
    if (mysqli_query($koneksi, $q)) {
        $_SESSION['flash'] = 'Data berhasil ditambahkan!';
    } else {
        $_SESSION['flash'] = 'Gagal menambahkan data!';
    }
    header("Location: index.php#dashboard");
    exit;
}

if (isset($_POST['submit_edit'])) {
    $id           = (int)$_POST['id'];
    $nama_sepatu  = mysqli_real_escape_string($koneksi, $_POST['nama_sepatu']);
    $brand        = mysqli_real_escape_string($koneksi, $_POST['brand']);
    $kategori     = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga        = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $stok_status  = mysqli_real_escape_string($koneksi, $_POST['stok_status']);
    $deskripsi    = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $gambar       = mysqli_real_escape_string($koneksi, $_POST['gambar']);

    $q = "UPDATE produk SET nama_sepatu='$nama_sepatu', brand='$brand', kategori='$kategori',
          harga='$harga', stok_status='$stok_status', deskripsi='$deskripsi', gambar='$gambar'
          WHERE id=$id";
    if (mysqli_query($koneksi, $q)) {
        $_SESSION['flash'] = 'Data berhasil diperbarui!';
    } else {
        $_SESSION['flash'] = 'Gagal memperbarui data!';
    }
    header("Location: index.php#dashboard");
    exit;
}

$query = "SELECT * FROM produk ORDER BY id DESC";
$res = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));

$oq = "SELECT * FROM orders ORDER BY created_at DESC";
$ores = mysqli_query($koneksi, $oq);

$mq = "SELECT * FROM messages ORDER BY created_at DESC";
$mres = mysqli_query($koneksi, $mq);

$products = [];
mysqli_data_seek($res, 0);
while ($row = mysqli_fetch_assoc($res)) $products[] = $row;
?>
<!DOCTYPE html>
<html lang="id">
  <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>SynsSneak - Pusat Sepatu Original & Bervariasi</title>
      <link rel="stylesheet" href="assets/css/style.css?v=3" />
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Montserrat:wght@800&display=swap" rel="stylesheet" />
  </head>
  <body>
    <nav class="navbar" id="navbar">
      <div class="nav-logo">
<span class="logo-text">Syns<span class="accent">Sneak</span></span>
      </div>
      <ul class="nav-links" id="navLinks">
        <li><a href="#home" class="active">Home</a></li>
        <li><a href="#products">Katalog Produk</a></li>
        <li><a href="#contact">Hubungi Kami</a></li>
        <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true) : ?>
        <li><a href="#dashboard" style="color: #2563eb; font-weight: bold;">Dashboard Admin</a></li>
        <li class="mobile-only"><a href="logout.php" style="color: #0f172a; font-weight: bold;">Logout</a></li>
        <?php else : ?>
        <li class="mobile-only"><a href="login.php" style="color: #2563eb; font-weight: bold;">Login</a></li>
        <?php endif; ?>
      </ul>
      <div class="nav-actions">
        <a href="#products" class="nav-cta">Belanja Sekarang</a>
        <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true) : ?>
        <a href="logout.php" class="nav-btn-auth" style="background:#0f172a;border-color:#0f172a;color:#fff;">Logout</a>
        <?php else : ?>
        <a href="login.php" class="nav-btn-auth">Login</a>
        <?php endif; ?>
      </div>
      <button class="hamburger" id="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
    </nav>

    <section class="page active" id="page-home">
      <section class="hero" id="hero">
        <div class="hero-bg">
          <div class="hero-overlay"></div>
        </div>

        <div class="hero-content">
          <h1 class="hero-title entry-fade-up entry-delay-1" style="font-size:clamp(3rem,8vw,5.5rem);letter-spacing:3px;">
            SynsSneak
          </h1>
          <p class="hero-desc entry-fade-up entry-delay-2">
            Sedia berbagai variasi sepatu olahraga, kasual, model kanvas klasik, hingga rilisan terbatas dari brand global terpercaya.
          </p>
          <div class="hero-actions entry-fade-up entry-delay-3">
            <a href="#products" class="btn-primary">
              <span>Lihat Koleksi</span>
            </a>
            <a onclick="document.getElementById('about-section').scrollIntoView({behavior:'smooth'}); return false;" href="#" class="btn-ghost">Tentang Kami</a>
          </div>

        </div>
      </section>

      <section class="about section-pad" id="about-section">
        <div class="container">
          <div class="about-grid">
            <div class="about-images reveal-left">
              <div class="img-card img-card-main">
                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=640"/>
                <div class="img-label">Performance Sneakers</div>
              </div>
              <div class="img-card img-card-small">
                <img src="https://images.unsplash.com/photo-1552346154-21d32810aba3?q=80&w=640"/>
                <div class="img-label">Streetwear Heritage</div>
              </div>
            </div>

            <div class="about-text reveal-right">
              <h2 class="section-title dark">Menyediakan Variasi Sepatu Terbaik untuk Tiap Langkah Anda</h2>
              <p>SynsSneak hadir sebagai solusi ekosistem belanja sepatu online modern terlengkap. Kami mengurasi setiap produk secara teliti demi memastikan pelanggan mendapatkan jaminan produk asli dengan variasi model melimpah untuk menunjang aktivitas olahraga maupun harian.</p>
              <p>Bukan sekadar penampilan luar, kami memahami bahwa kenyamanan tumpuan kaki merupakan poin krusial utama. Dapatkan koleksi rilisan terbaru, tipe kasual andalan, hingga lini model retro legendaris di satu tempat.</p>
            </div>
          </div>
        </div>
      </section>

      <section class="featured section-pad bg-dark">
        <div class="container">
          <div class="section-header reveal">
            <h2 class="section-title">Koleksi Terlaris Pekan Ini</h2>
            <p class="section-sub">Pilih lini model andalanmu dan tampil maksimal di setiap kesempatan</p>
          </div>

          <div class="sneaker-grid stagger">
            <div class="sneaker-card">
              <div class="sneaker-img">
                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=640"/>
                <div class="sneaker-overlay">
                  <span class="sneaker-category">Running</span>
                </div>
              </div>
              <div class="sneaker-info">
                <div class="sneaker-category">Kategori: Sport Shoes</div>
                <h3>Nike Air Max Tailwind</h3>
                <p>Maksimalkan performa lari harian Anda dengan dukungan bantalan udara responsif penuh kenyamanan tinggi.</p>
                <div class="sneaker-footer">
                  <div class="sneaker-price">
                    <span class="from">Harga Mulai</span>
                    <strong>Rp 1.850.000</strong>
                    <span class="per">/pasang</span>
                  </div>
                  <a href="#products" class="sneaker-btn">Detail →</a>
                </div>
              </div>
            </div>

            <div class="sneaker-card">
              <div class="sneaker-img">
                <img src="https://images.unsplash.com/photo-1552346154-21d32810aba3?q=80&w=640"/>
                <div class="sneaker-overlay">
                  <span class="sneaker-category">Streetwear</span>
                </div>
              </div>
              <div class="sneaker-info">
                <div class="sneaker-category">Kategori: High-Top Hype</div>
                <h3>Air Jordan 1 Retro</h3>
                <p>Siluet potongan tinggi kultur streetwear ikonik sepanjang masa dengan material kulit orisinal berkelas premium.</p>
                <div class="sneaker-footer">
                  <div class="sneaker-price">
                    <span class="from">Harga Mulai</span>
                    <strong>Rp 3.750.000</strong>
                    <span class="per">/pasang</span>
                  </div>
                  <a href="#products" class="sneaker-btn">Detail →</a>
                </div>
              </div>
            </div>

            <div class="sneaker-card">
              <div class="sneaker-img">
                <img src="https://images.unsplash.com/photo-1539185441755-769473a23570?q=80&w=640"/>
                <div class="sneaker-overlay">
                  <span class="sneaker-category">Casual</span>
                </div>
              </div>
              <div class="sneaker-info">
                <div class="sneaker-category">Kategori: Daily Use</div>
                <h3>New Balance 574</h3>
                <p>Kombinasi bahan premium suede berdaya tahan kuat serta desain estetika retro kasual serbaguna.</p>
                <div class="sneaker-footer">
                  <div class="sneaker-price">
                    <span class="from">Harga Mulai</span>
                    <strong>Rp 1.450.000</strong>
                    <span class="per">/pasang</span>
                  </div>
                  <a href="#products" class="sneaker-btn">Detail →</a>
                </div>
              </div>
            </div>
          </div>

          <div class="center-btn">
            <a href="#products" class="btn-outline-light">Lihat Katalog Lengkap</a>
          </div>
        </div>
      </section>
    </section>

    <section class="page" id="page-products">
      <section class="page-hero">
        <div class="page-hero-bg" style="background-image:url('https://images.unsplash.com/photo-1460353581641-37baddab0fa2?q=80&w=1200')"></div>
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content">
          <h1>Katalog Produk Sepatu</h1>
          <p>Temukan model sepatu dari berbagai lini brand global ternama dengan jaminan 100% original</p>
        </div>
      </section>

      <section class="all-products section-pad">
        <div class="container">
          <div class="sneaker-full-grid stagger" id="productGrid">
            <?php mysqli_data_seek($res, 0); while($row = mysqli_fetch_assoc($res)) : ?>
            <div class="sneaker-card-full">
              <div class="sneaker-img-full">
                <img src="<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_sepatu']; ?>"/>
                <span class="sneaker-tag"><?php echo $row['kategori']; ?></span>
                <span class="sneaker-stock"><?php echo $row['stok_status']; ?></span>
              </div>
              <div class="sneaker-body">
                <div class="sneaker-meta">
                  <span class="sneaker-brand">Brand: <?php echo $row['brand']; ?></span>
                </div>
                <h3><?php echo $row['nama_sepatu']; ?></h3>
                <p><?php echo $row['deskripsi']; ?></p>
                <div class="sneaker-price-row">
                  <div>
                    <span class="price-label">Harga</span>
                    <strong class="price-big">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></strong>
                  </div>
                  <button class="btn-primary btn-beli" data-product="<?php echo $row['nama_sepatu']; ?>" data-price="<?php echo $row['harga']; ?>">Beli Sekarang</button>
                </div>
              </div>
            </div>
            <?php endwhile; ?>
          </div>
        </div>
      </section>
    </section>

    <section class="page" id="page-contact">
      <section class="page-hero">
        <div class="page-hero-bg" style="background-image:url('https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?q=80&w=740')"></div>
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content">
          <h1>Layanan Pelanggan</h1>
          <p>Ada pertanyaan mengenai ukuran, ketersediaan stok, atau status pengiriman? Tim kami siap membantu Anda.</p>
        </div>
      </section>

      <section class="contact-section section-pad">
        <div class="container">
          <div class="contact-grid">

            <div class="contact-info reveal-left">
              <h2>Mari Bicara tentang Sepatu Impian Anda</h2>
              <p>Kami berkomitmen memberikan pelayanan terbaik demi kenyamanan berbelanja Anda. Hubungi tim cs retail atau grosir kami melalui saluran berikut:</p>

              <div class="contact-cards">
                <div class="contact-card">
                  <div class="cc-text">
                    <strong>WhatsApp Customer Service</strong>
                    <span>+62 812-3456-7890</span>
                  </div>
                </div>
                <div class="contact-card">
                  <div class="cc-text">
                    <strong>Email Layanan</strong>
                    <span>support@sneakervault.com</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="contact-form-wrap reveal-right">
              <div class="contact-form-card">
                <h3>Kirim Pesan / Request Pre-Order</h3>
                <?php if (isset($_SESSION['flash_contact'])) : ?>
                <div class="flash-msg" style="margin-bottom:16px;"><?php echo $_SESSION['flash_contact']; unset($_SESSION['flash_contact']); ?></div>
                <?php endif; ?>
                <form action="index.php#contact" method="POST">
                  <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="contact_name" placeholder="Nama lengkap Anda" required />
                  </div>
                  <div class="form-group">
                    <label>Alamat Email *</label>
                    <input type="email" name="contact_email" placeholder="nama@email.com" required />
                  </div>
                  <div class="form-group">
                    <label>Detail Pertanyaan *</label>
                    <textarea name="contact_message" rows="4" placeholder="Tulis tipe sepatu dan ukuran..." required></textarea>
                  </div>
                  <button type="submit" name="submit_contact" class="btn-primary full-width">Kirim Formulir</button>
                </form>
              </div>
            </div>

          </div>
        </div>
      </section>
    </section>

    <div class="modal-overlay admin-modal" id="orderModal">
      <div class="modal-card">
        <button class="modal-close" onclick="closeModal('orderModal')">&times;</button>
        <div class="modal-header">
          <h3>Pesan Sepatu</h3>
          <p>Isi data diri Anda untuk memesan produk ini</p>
        </div>
        <?php if (isset($_SESSION['flash_order'])) : ?>
        <div class="flash-msg" style="margin-bottom:16px;"><?php echo $_SESSION['flash_order']; unset($_SESSION['flash_order']); ?></div>
        <?php endif; ?>
        <form action="index.php#products" method="POST" class="modal-form">
          <input type="hidden" name="product_name" id="modalProductName">
          <input type="hidden" name="total_price" id="modalTotalPrice">

          <div class="modal-field">
            <label>Produk</label>
            <input type="text" id="modalProductDisplay" readonly>
          </div>
          <div class="modal-row">
            <div class="modal-field">
              <label>Nama Lengkap *</label>
              <input type="text" name="customer_name" required placeholder="Nama Anda">
            </div>
            <div class="modal-field">
              <label>No. HP *</label>
              <input type="text" name="customer_phone" required placeholder="08xxxx">
            </div>
          </div>
          <div class="modal-field">
            <label>Email *</label>
            <input type="email" name="customer_email" required placeholder="nama@email.com">
          </div>
          <div class="modal-field">
            <label>Alamat Pengiriman *</label>
            <textarea name="address" rows="2" required placeholder="Jl. ..."></textarea>
          </div>
          <div class="modal-row">
            <div class="modal-field">
              <label>Ukuran *</label>
              <select name="size" required>
                <option value="">Pilih ukuran</option>
                <option>39</option><option>40</option><option>41</option><option>42</option>
                <option>43</option><option>44</option><option>45</option>
              </select>
            </div>
            <div class="modal-field">
              <label>Jumlah *</label>
              <input type="number" name="quantity" id="modalQuantity" value="1" min="1" required>
            </div>
          </div>
          <div class="modal-total">
            <span>Total:</span>
            <strong id="modalTotalDisplay">Rp 0</strong>
          </div>
          <div class="modal-actions">
            <button type="button" class="btn-dash btn-dash-pdf" onclick="closeModal('orderModal')">Batal</button>
            <button type="submit" name="submit_order" class="btn-dash btn-dash-add">Konfirmasi Pesanan</button>
          </div>
        </form>
      </div>
    </div>

    <section class="page" id="page-dashboard">
      <section class="page-hero">
        <div class="page-hero-bg" style="background-image:url('https://images.unsplash.com/photo-1460353581641-37baddab0fa2?q=80&w=1200')"></div>
        <div class="page-hero-overlay"></div>
        <div class="page-hero-content">
          <h1>Dashboard Admin</h1>
          <p>Selamat datang, <?php echo $_SESSION['username'] ?? 'Admin'; ?></p>
        </div>
      </section>

      <section class="dashboard-section section-pad">
        <div class="container">
          <?php if (isset($_SESSION['flash'])) : ?>
          <div class="flash-msg"><?php echo $_SESSION['flash']; unset($_SESSION['flash']); ?></div>
          <?php endif; ?>

          <div class="dash-tabs">
            <button class="dash-tab active" data-tab="produk">Stok Produk</button>
            <button class="dash-tab" data-tab="orders">Pemesanan</button>
            <button class="dash-tab" data-tab="messages">Pesan Masuk</button>
          </div>

          <div class="dash-panel active" id="dash-produk">
            <div class="dashboard-header">
              <div>
                <h2 style="color:#0f172a;font-size:1.5rem;font-weight:700;">Manajemen Stok Sepatu</h2>
                <p style="color:#0f172a;font-size:0.88rem;">Tambah, perbarui, atau hapus koleksi produk katalog toko.</p>
              </div>
              <div class="dashboard-actions">
                <a href="cetak_laporan.php" target="_blank" class="btn-dash btn-dash-pdf">Cetak PDF</a>
                <button onclick="openModalTambah()" class="btn-dash btn-dash-add">+ Tambah Sepatu Baru</button>
              </div>
            </div>
            <div class="dash-table-wrap">
              <table class="dash-table">
                <thead>
                  <tr>
                    <th>Gambar</th>
                    <th>Nama Sepatu</th>
                    <th>Brand / Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php mysqli_data_seek($res, 0); while($row = mysqli_fetch_assoc($res)) : ?>
                  <tr>
                    <td><img src="<?php echo $row['gambar']; ?>" alt="" class="dash-thumb"></td>
                    <td class="dash-name"><?php echo $row['nama_sepatu']; ?></td>
                    <td>
                      <span class="dash-brand"><?php echo $row['brand']; ?></span>
                      <span class="dash-cat"><?php echo $row['kategori']; ?></span>
                    </td>
                    <td class="dash-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><span class="dash-status"><?php echo $row['stok_status']; ?></span></td>
                    <td class="dash-actions-td">
                      <button onclick="openEditModal(<?php echo $row['id']; ?>)" class="btn-aksi btn-edit">Edit</button>
                      <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                    </td>
                  </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="dash-panel" id="dash-orders">
            <div class="dashboard-header">
              <div>
                <h2 style="color:#0f172a;font-size:1.5rem;font-weight:700;">Pemesanan Pelanggan</h2>
                <p style="color:#0f172a;font-size:0.88rem;">Daftar pesanan masuk dari pelanggan.</p>
              </div>
            </div>
            <div class="dash-table-wrap">
              <table class="dash-table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Pemesan</th>
                    <th>Kontak</th>
                    <th>Ukuran</th>
                    <th>Jml</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (mysqli_num_rows($ores) > 0) : ?>
                  <?php $no=1; while($o = mysqli_fetch_assoc($ores)) : ?>
                  <tr>
                    <td><?php echo $no++; ?></td>
                    <td class="dash-name"><?php echo $o['product_name']; ?></td>
                    <td>
                      <strong><?php echo $o['customer_name']; ?></strong><br>
                      <span style="font-size:0.75rem;color:#64748b;"><?php echo $o['customer_email']; ?></span>
                    </td>
                    <td><?php echo $o['customer_phone']; ?></td>
                    <td><?php echo $o['size']; ?></td>
                    <td><?php echo $o['quantity']; ?></td>
                    <td class="dash-price">Rp <?php echo number_format($o['total_price'], 0, ',', '.'); ?></td>
                    <td>
                      <span class="order-status status-<?php echo $o['status']; ?>"><?php echo ucfirst($o['status']); ?></span>
                    </td>
                    <td style="font-size:0.78rem;color:#64748b;"><?php echo date('d/m/Y', strtotime($o['created_at'])); ?></td>
                  </tr>
                  <?php endwhile; ?>
                  <?php else : ?>
                  <tr><td colspan="9" style="text-align:center;padding:32px;color:#64748b;">Belum ada pemesanan.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="dash-panel" id="dash-messages">
            <div class="dashboard-header">
              <div>
                <h2 style="color:#0f172a;font-size:1.5rem;font-weight:700;">Pesan dari Pelanggan</h2>
                <p style="color:#0f172a;font-size:0.88rem;">Pertanyaan dan pesan yang dikirim melalui form kontak.</p>
              </div>
            </div>
            <div class="dash-table-wrap">
              <table class="dash-table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (mysqli_num_rows($mres) > 0) : ?>
                  <?php $no=1; while($m = mysqli_fetch_assoc($mres)) : ?>
                  <tr>
                    <td><?php echo $no++; ?></td>
                    <td class="dash-name"><?php echo $m['name']; ?></td>
                    <td><?php echo $m['email']; ?></td>
                    <td style="max-width:300px;"><?php echo $m['message']; ?></td>
                    <td style="font-size:0.78rem;color:#64748b;"><?php echo date('d/m/Y H:i', strtotime($m['created_at'])); ?></td>
                  </tr>
                  <?php endwhile; ?>
                  <?php else : ?>
                  <tr><td colspan="5" style="text-align:center;padding:32px;color:#64748b;">Belum ada pesan masuk.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </section>
    </section>

    <div class="modal-overlay admin-modal" id="modalTambah">
      <div class="modal-card">
        <button class="modal-close" onclick="closeModal('modalTambah')">&times;</button>
        <div class="modal-header">
          <h3>Tambah Koleksi Sepatu Baru</h3>
          <p>Lengkapi data produk untuk menambah stok katalog.</p>
        </div>
        <form action="index.php#dashboard" method="POST" class="modal-form">
          <div class="modal-field">
            <label>Nama Tipe Sepatu *</label>
            <input type="text" name="nama_sepatu" required>
          </div>
          <div class="modal-row">
            <div class="modal-field">
              <label>Brand *</label>
              <input type="text" name="brand" placeholder="Contoh: Nike, Adidas" required>
            </div>
            <div class="modal-field">
              <label>Kategori Lini *</label>
              <select name="kategori" required>
                <option value="">Pilih kategori</option>
                <option value="Running & Sport">Running & Sport</option>
                <option value="Classic Canvas">Classic Canvas</option>
                <option value="Casual & Skateboard">Casual & Skateboard</option>
                <option value="Limited Edition / Hype">Limited Edition / Hype</option>
              </select>
            </div>
          </div>
          <div class="modal-row">
            <div class="modal-field">
              <label>Harga (Rupiah) *</label>
              <input type="number" name="harga" required>
            </div>
            <div class="modal-field">
              <label>Status Ketersediaan *</label>
              <input type="text" name="stok_status" placeholder="Contoh: Stok Tersedia" required>
            </div>
          </div>
          <div class="modal-field">
            <label>URL Link Gambar Sepatu *</label>
            <input type="url" name="gambar" placeholder="https://example.com/sepatu.jpg" required>
          </div>
          <div class="modal-field">
            <label>Deskripsi & Keunggulan Produk *</label>
            <textarea name="deskripsi" rows="3" required></textarea>
          </div>
          <div class="modal-actions">
            <button type="button" class="btn-dash btn-dash-pdf" onclick="closeModal('modalTambah')">Batal</button>
            <button type="submit" name="submit_tambah" class="btn-dash btn-dash-add">Simpan Data</button>
          </div>
        </form>
      </div>
    </div>

    <div class="modal-overlay admin-modal" id="modalEdit">
      <div class="modal-card">
        <button class="modal-close" onclick="closeModal('modalEdit')">&times;</button>
        <div class="modal-header">
          <h3>Perbarui Data Sepatu</h3>
          <p>Ubah informasi produk yang sudah ada.</p>
        </div>
        <form action="index.php#dashboard" method="POST" class="modal-form">
          <input type="hidden" name="id" id="editId">
          <div class="modal-field">
            <label>Nama Tipe Sepatu *</label>
            <input type="text" name="nama_sepatu" id="editNama" required>
          </div>
          <div class="modal-row">
            <div class="modal-field">
              <label>Brand *</label>
              <input type="text" name="brand" id="editBrand" required>
            </div>
            <div class="modal-field">
              <label>Kategori Lini *</label>
              <select name="kategori" id="editKategori" required>
                <option value="Running & Sport">Running & Sport</option>
                <option value="Classic Canvas">Classic Canvas</option>
                <option value="Casual & Skateboard">Casual & Skateboard</option>
                <option value="Limited Edition / Hype">Limited Edition / Hype</option>
              </select>
            </div>
          </div>
          <div class="modal-row">
            <div class="modal-field">
              <label>Harga (Rupiah) *</label>
              <input type="number" name="harga" id="editHarga" required>
            </div>
            <div class="modal-field">
              <label>Status Ketersediaan *</label>
              <input type="text" name="stok_status" id="editStatus" required>
            </div>
          </div>
          <div class="modal-field">
            <label>URL Link Gambar Sepatu *</label>
            <input type="url" name="gambar" id="editGambar" required>
          </div>
          <div class="modal-field">
            <label>Deskripsi & Keunggulan Produk *</label>
            <textarea name="deskripsi" id="editDeskripsi" rows="3" required></textarea>
          </div>
          <div class="modal-actions">
            <button type="button" class="btn-dash btn-dash-pdf" onclick="closeModal('modalEdit')">Batal</button>
            <button type="submit" name="submit_edit" class="btn-dash btn-dash-add">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>

    <footer class="footer">
      <div class="container">
        <div class="footer-grid">
          <div class="footer-brand reveal">
            <div class="nav-logo">
<span class="logo-text">SynsSneak</span>
            </div>
            <p>Pusat ritel online terpercaya yang menyediakan berbagai pilihan sepatu original, dari gaya harian hingga koleksi terbatas.</p>
          </div>
          <div class="footer-links">
            <h4>Navigasi</h4>
            <ul>
              <li><a href="#home">Home</a></li>
              <li><a href="#products">Koleksi Produk</a></li>
              <li><a href="#contact">Hubungi Kami</a></li>
              <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true) : ?>
              <li><a href="#dashboard">Dashboard Admin</a></li>
              <?php endif; ?>
            </ul>
          </div>
          <div class="footer-links">
            <h4>Kategori Terpopuler</h4>
            <ul>
              <li><a href="#products">Running & Sport</a></li>
              <li><a href="#products">Casual Sneakers</a></li>
              <li><a href="#products">High-Top / Hype</a></li>
            </ul>
          </div>
          <div class="footer-contact">
            <h4>Kontak Resmi</h4>
            <p>Jl. Malioboro No. 88, Yogyakarta</p>
            <p>+62 812-3456-7890</p>
          </div>
        </div>
        <div class="footer-bottom">
          <p>© 2026 SynsSneak. Melangkah Dengan Gaya dan Autentisitas Terjamin.</p>
        </div>
      </div>
    </footer>

  <script>
    (function() {
      var hamburger = document.getElementById('hamburger');
      var navLinks = document.getElementById('navLinks');
      var pages = document.querySelectorAll('.page');
      var hashLinks = document.querySelectorAll('a[href^="#"]');

      function showPage(hash) {
        if (!hash || hash === '#') hash = '#home';

        pages.forEach(function(p) {
          p.classList.remove('active');
          p.style.display = 'none';
        });

        var targetId = hash === '#home' ? 'page-home' : 'page-' + hash.slice(1);
        var target = document.getElementById(targetId);
        if (target) {
          target.style.display = 'block';
          target.classList.add('active');
        }

        document.querySelectorAll('.nav-links a[href^="#"]').forEach(function(a) {
          a.classList.toggle('active', a.getAttribute('href') === hash);
        });
      }

      hashLinks.forEach(function(a) {
        a.addEventListener('click', function(e) {
          var href = a.getAttribute('href');
          if (href.startsWith('#') && href.length > 1 && !href.includes('#')) {
            e.preventDefault();
            window.location.hash = href;
          }
        });
      });

      window.addEventListener('hashchange', function() {
        showPage(window.location.hash || '#home');
      });

      showPage(window.location.hash || '#home');

      if (hamburger && navLinks) {
        hamburger.addEventListener('click', function() {
          navLinks.classList.toggle('active');
        });

        navLinks.querySelectorAll('a[href^="#"]').forEach(function(a) {
          a.addEventListener('click', function() {
            navLinks.classList.remove('active');
          });
        });
      }

      var modal = document.getElementById('orderModal');
      var modalProduct = document.getElementById('modalProductName');
      var modalProductDisplay = document.getElementById('modalProductDisplay');
      var modalTotal = document.getElementById('modalTotalPrice');
      var modalTotalDisplay = document.getElementById('modalTotalDisplay');
      var modalQty = document.getElementById('modalQuantity');

      document.querySelectorAll('.btn-beli').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var product = btn.getAttribute('data-product');
          var price = parseInt(btn.getAttribute('data-price'));
          modalProduct.value = product;
          modalProductDisplay.value = product;
          modalTotal.value = price;
          modalQty.value = 1;
          updateTotal();
          modal.classList.add('show');
        });
      });

      modal.addEventListener('click', function(e) {
        if (e.target === modal) modal.classList.remove('show');
      });

      function updateTotal() {
        var qty = parseInt(modalQty.value) || 1;
        var price = parseInt(modalTotal.value) || 0;
        var total = qty * price;
        modalTotal.value = total;
        modalTotalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
      }
      modalQty.addEventListener('input', updateTotal);

      document.querySelectorAll('.dash-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
          document.querySelectorAll('.dash-tab').forEach(function(t) { t.classList.remove('active'); });
          document.querySelectorAll('.dash-panel').forEach(function(p) { p.classList.remove('active'); });
          tab.classList.add('active');
          document.getElementById('dash-' + tab.getAttribute('data-tab')).classList.add('active');
        });
      });

      var navbar = document.getElementById('navbar');
      function handleScroll() {
        if (window.scrollY > 50) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }
      }
      window.addEventListener('scroll', handleScroll, { passive: true });
      handleScroll();

      if ('IntersectionObserver' in window) {
        var revealObserver = new IntersectionObserver(function(entries) {
          entries.forEach(function(entry) {
            if (entry.isIntersecting) {
              var el = entry.target;
              el.classList.add('active');
              revealObserver.unobserve(el);

            }
          });
        }, { threshold: 0.15 });

        document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .stagger, .section-header').forEach(function(el) {
          revealObserver.observe(el);
        });
      } else {
        document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .stagger').forEach(function(el) {
          el.classList.add('active');
        });
      }

      window.addEventListener('hashchange', function() {
        setTimeout(function() {
          if ('IntersectionObserver' in window) {
            document.querySelectorAll('.reveal:not(.active), .reveal-left:not(.active), .reveal-right:not(.active), .reveal-scale:not(.active), .stagger:not(.active)').forEach(function(el) {
              revealObserver.observe(el);
            });
          }
        }, 100);
      });

      var adminProducts = <?php echo json_encode($products); ?>;

      window.openModalTambah = function() {
        document.getElementById('modalTambah').classList.add('show');
      };

      window.openEditModal = function(id) {
        var p = adminProducts.find(function(prod) { return parseInt(prod.id) === id; });
        if (!p) return;
        document.getElementById('editId').value = p.id;
        document.getElementById('editNama').value = p.nama_sepatu;
        document.getElementById('editBrand').value = p.brand;
        document.getElementById('editKategori').value = p.kategori;
        document.getElementById('editHarga').value = p.harga;
        document.getElementById('editStatus').value = p.stok_status;
        document.getElementById('editGambar').value = p.gambar;
        document.getElementById('editDeskripsi').value = p.deskripsi;
        document.getElementById('modalEdit').classList.add('show');
      };

      window.closeModal = function(id) {
        document.getElementById(id).classList.remove('show');
      };

      document.querySelectorAll('#modalTambah, #modalEdit').forEach(function(m) {
        m.addEventListener('click', function(e) {
          if (e.target === m) m.classList.remove('show');
        });
      });

    })();
  </script>
  </body>
</html>
