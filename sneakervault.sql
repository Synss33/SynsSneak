-- --------------------------------------------------------
-- Database: sneakervault
------------------------------------------------------

CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Password default: admin123 (bcrypt hash)
INSERT IGNORE INTO users (username, password, role) VALUES ('admin', '$2y$12$0uQHrF5KrJxvpzJ7d/Jdo.R1GVH07ig78ZBL.2JxHb2yQhkZUsoU2', 'admin');

-- --------------------------------------------------------
-- Database: sneakervault
-- Tabel: orders (Pemesanan Pelanggan)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS orders (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    size VARCHAR(50) NOT NULL,
    quantity INT(11) NOT NULL DEFAULT 1,
    total_price BIGINT(20) NOT NULL,
    status ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO orders (product_name, customer_name, customer_email, customer_phone, address, size, quantity, total_price, status, created_at) VALUES
('Nike Air Max Tailwind', 'Budi Santoso', 'budi@email.com', '081234567890', 'Jl. Merdeka No. 10, Jakarta Pusat', '42', 1, 1850000, 'confirmed', '2026-07-01 10:30:00'),
('Air Jordan 1 Retro', 'Siti Rahmah', 'siti@email.com', '087812345678', 'Jl. Diponegoro No. 25, Bandung', '43', 2, 7500000, 'pending', '2026-07-05 14:15:00'),
('New Balance 574', 'Ahmad Fauzi', 'ahmad@email.com', '085611223344', 'Jl. Sudirman No. 88, Surabaya', '41', 1, 1450000, 'completed', '2026-06-28 09:45:00'),
('Adidas Ultraboost 22', 'Dewi Lestari', 'dewi@email.com', '082134567890', 'Jl. Gatot Subroto No. 15, Medan', '40', 1, 2500000, 'confirmed', '2026-07-08 16:20:00'),
('Converse Chuck Taylor All Star', 'Rudi Hartono', 'rudi@email.com', '081398765432', 'Jl. Pahlawan No. 5, Yogyakarta', '39', 1, 550000, 'cancelled', '2026-06-25 11:10:00'),
('Vans Old Skool', 'Maya Sari', 'maya@email.com', '085722334455', 'Jl. Kusuma Bangsa No. 30, Semarang', '38', 2, 1700000, 'completed', '2026-06-20 08:00:00'),
('Nike Dunk Low Retro', 'Dimas Pratama', 'dimas@email.com', '081267890123', 'Jl. Thamrin No. 55, Jakarta Pusat', '44', 1, 2100000, 'pending', '2026-07-10 13:50:00');

-- --------------------------------------------------------
-- Database: sneakervault
-- Tabel: messages (Pesan dari Form Kontak)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO messages (name, email, message, created_at) VALUES
('Andi Wijaya', 'andi@email.com', 'Halo, saya ingin menanyakan apakah ukuran 45 untuk New Balance 574 tersedia? Mohon informasinya. Terima kasih.', '2026-07-02 09:15:00'),
('Fitria Handayani', 'fitria@email.com', 'Selamat siang, saya tertarik dengan Air Jordan 1 Retro. Apakah masih ada stok untuk ukuran 42? Saya juga ingin tahu estimasi pengiriman ke Bali.', '2026-07-06 14:30:00'),
('Hendra Gunawan', 'hendra@email.com', 'Permisi, saya mau order pre-order untuk Nike Dunk Low Retro ukuran 43. Berapa lama estimasi barang datang? Siap transfer DP.', '2026-07-09 11:45:00'),
('Rina Marlina', 'rina@email.com', 'Halo tim SneakerVault, saya dari Makassar. Apakah ada layanan pengiriman gratis ke Sulawesi? Saya minat beli 2 pasang Vans Old Skool.', '2026-07-11 08:20:00');

-- --------------------------------------------------------
-- Database: sneakervault
-- Tabel: produk (Manajemen Koleksi Sepatu)
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS sneakervault;
USE sneakervault;

CREATE TABLE IF NOT EXISTS produk (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_sepatu VARCHAR(255) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    harga INT(11) NOT NULL,
    stok_status VARCHAR(100) NOT NULL,
    deskripsi TEXT NOT NULL,
    gambar VARCHAR(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contoh data awal
INSERT INTO produk (nama_sepatu, brand, kategori, harga, stok_status, deskripsi, gambar) VALUES
('Nike Air Max Tailwind', 'Nike', 'Running & Sport', 1850000, 'Stok Tersedia', 'Maksimalkan performa lari harian Anda dengan dukungan bantalan udara responsif.', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=640'),
('Air Jordan 1 Retro', 'Jordan', 'Limited Edition / Hype', 3750000, 'Sisa 3 Pasang', 'Siluet potongan tinggi kultur streetwear ikonik dengan material kulit orisinal premium.', 'https://images.unsplash.com/photo-1552346154-21d32810aba3?q=80&w=640'),
('New Balance 574', 'New Balance', 'Casual & Skateboard', 1450000, 'Stok Tersedia', 'Kombinasi bahan premium suede dengan desain estetika retro kasual serbaguna.', 'https://images.unsplash.com/photo-1539185441755-769473a23570?q=80&w=640'),
('Adidas Ultraboost 22', 'Adidas', 'Running & Sport', 2500000, 'Stok Tersedia', 'Solusi lari premium dengan teknologi Boost responsif dan upper Primeknit yang adaptif.', 'https://images.unsplash.com/photo-1587563871167-1ee9c731aefb?q=80&w=640'),
('Converse Chuck Taylor All Star', 'Converse', 'Classic Canvas', 550000, 'Stok Tersedia', 'Ikon kanvas klasik sepanjang masa dengan desain timeless untuk segala gaya kasual.', 'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?q=80&w=640'),
('Vans Old Skool', 'Vans', 'Casual & Skateboard', 850000, 'Stok Tersedia', 'Siluet skateboard legendaris dengan side stripe khas dan material suede-kanvas kokoh.', 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?q=80&w=640'),
('Nike Air Force 1 ''07', 'Nike', 'Casual & Skateboard', 1650000, 'Stok Tersedia', 'Ikon low-profile klasik dengan bantalan Air-Sole empuk dan gaya streetwear tak lekang waktu.', 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?q=80&w=640'),
('Adidas Stan Smith', 'Adidas', 'Casual & Skateboard', 1250000, 'Sisa 5 Pasang', 'Desain minimalis bahan kulit sintetis premium dengan detail perforasi three-stripes elegan.', 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=640'),
('Puma Suede Classic XXI', 'Puma', 'Casual & Skateboard', 950000, 'Stok Tersedia', 'Sneakers suede ikonik gaya casual timeless, nyaman dipakai sehari-hari.', 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=640'),
('Reebok Club C 85', 'Reebok', 'Casual & Skateboard', 1150000, 'Stok Tersedia', 'Tennis klasik era 80-an dengan upper kulit lembut dan sol karet tahan lama.', 'https://images.unsplash.com/photo-1595341888016-a392ef81b7de?q=80&w=640'),
('Nike Dunk Low Retro', 'Nike', 'Limited Edition / Hype', 2100000, 'Sisa 2 Pasang', 'Hype beast dengan warna kontras high-impact, siluet low-cut, konstruksi kulit premium.', 'https://images.unsplash.com/photo-1597045566677-8cf032ed8434?q=80&w=640'),
('ASICS Gel-Kayano 29', 'ASICS', 'Running & Sport', 2300000, 'Stok Tersedia', 'Teknologi GEL cushioning canggih dan upper engineered mesh untuk stabilitas maksimal.', 'https://images.unsplash.com/photo-1562183241-b937e95585b6?q=80&w=640'),
('Vans Authentic Classic', 'Vans', 'Classic Canvas', 750000, 'Stok Tersedia', 'Siluet asli Vans kanvas ringan dan sol waffle karet untuk cengkeraman optimal.', 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?q=80&w=640');
