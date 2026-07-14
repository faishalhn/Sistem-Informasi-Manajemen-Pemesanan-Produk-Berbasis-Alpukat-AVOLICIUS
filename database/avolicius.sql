-- ==========================================================
-- DATABASE  : avolicius_db
-- PROJECT   : AVOLICIUS
-- VERSION   : 1.0.0
-- DBMS      : MySQL 8+
-- CHARSET   : utf8mb4
-- COLLATION : utf8mb4_unicode_ci
-- ENGINE    : InnoDB
-- AUTHOR    : Faishal Hakim Nurrahman
-- ==========================================================

/*
|--------------------------------------------------------------------------
| DATABASE INITIALIZATION
|--------------------------------------------------------------------------
| File ini bertugas:
| 1. Menghapus database lama (development only)
| 2. Membuat database baru
| 3. Mengatur character set & collation
| 4. Mengatur SQL Mode
| 5. Mengatur Time Zone
|--------------------------------------------------------------------------
*/

-- Hapus database lama (Development Only)
DROP DATABASE IF EXISTS avolicius_db;

-- Membuat database baru
CREATE DATABASE avolicius_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Menggunakan database
USE avolicius_db;

-- ==========================================================
-- MYSQL CONFIGURATION
-- ==========================================================

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

SET time_zone = '+07:00';

SET NAMES utf8mb4;

SET CHARACTER SET utf8mb4;

SET FOREIGN_KEY_CHECKS = 0;

SET UNIQUE_CHECKS = 0;

-- ==========================================================
-- END OF FILE
-- ==========================================================
-- 01_database.sql  

-- ==========================================================
-- FILE        : 02_master.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Master Tables
-- VERSION     : 1.0.0
-- DBMS        : MySQL 8+
-- ==========================================================

-- ==========================================================
-- TABLE : admin
-- ==========================================================

CREATE TABLE admin (

    id_admin INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    kode_admin VARCHAR(20) NOT NULL,

    nama VARCHAR(100) NOT NULL,

    username VARCHAR(50) NOT NULL,

    email VARCHAR(100) NOT NULL,

    password VARCHAR(255) NOT NULL,

    foto VARCHAR(255) DEFAULT NULL,

    role ENUM(
        'super_admin',
        'admin'
    ) NOT NULL DEFAULT 'admin',

    status ENUM(
        'aktif',
        'nonaktif'
    ) NOT NULL DEFAULT 'aktif',

    last_login DATETIME DEFAULT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT uk_admin_kode UNIQUE (kode_admin),

    CONSTRAINT uk_admin_username UNIQUE (username),

    CONSTRAINT uk_admin_email UNIQUE (email)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- TABLE : kategori
-- ==========================================================

CREATE TABLE kategori (

    id_kategori INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    kode_kategori VARCHAR(20) NOT NULL,

    nama_kategori VARCHAR(100) NOT NULL,

    slug VARCHAR(150) NOT NULL,

    warna VARCHAR(20) DEFAULT '#4CAF50',

    icon VARCHAR(100) DEFAULT NULL,

    urutan SMALLINT UNSIGNED NOT NULL DEFAULT 1,

    deskripsi TEXT DEFAULT NULL,

    status ENUM(
        'aktif',
        'nonaktif'
    ) NOT NULL DEFAULT 'aktif',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT uk_kategori_kode UNIQUE (kode_kategori),

    CONSTRAINT uk_kategori_slug UNIQUE (slug)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- TABLE : setting
-- ==========================================================

CREATE TABLE setting (

    id_setting INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    setting_key VARCHAR(100) NOT NULL,

    setting_value LONGTEXT NULL,

    setting_group VARCHAR(50)
        NOT NULL DEFAULT 'general',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uk_setting_key UNIQUE (setting_key)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- TABLE : testimonial
-- ==========================================================

CREATE TABLE testimonial (

    id_testimonial INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    nama VARCHAR(100) NOT NULL,

    pekerjaan VARCHAR(100) DEFAULT NULL,

    foto VARCHAR(255) DEFAULT NULL,

    isi_testimoni TEXT NOT NULL,

    rating TINYINT UNSIGNED NOT NULL DEFAULT 5,

    urutan SMALLINT UNSIGNED NOT NULL DEFAULT 1,

    is_featured BOOLEAN NOT NULL DEFAULT FALSE,

    status ENUM(
        'tampil',
        'sembunyi'
    ) NOT NULL DEFAULT 'tampil',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- TABLE : log_aktivitas
-- ==========================================================

CREATE TABLE log_aktivitas (

    id_log BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    id_admin INT UNSIGNED NOT NULL,

    aktivitas VARCHAR(255) NOT NULL,

    ip_address VARCHAR(45) DEFAULT NULL,

    user_agent TEXT DEFAULT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- ==========================================================
-- FILE        : 03_produk.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Product Module
-- VERSION     : 1.0.0
-- DBMS        : MySQL 8+
-- ==========================================================

-- ==========================================================
-- TABLE : produk
-- ==========================================================

CREATE TABLE produk (

    id_produk INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    kode_produk VARCHAR(20) NOT NULL,

    sku VARCHAR(50) NOT NULL,

    barcode VARCHAR(50) DEFAULT NULL,

    id_kategori INT UNSIGNED NOT NULL,

    nama_produk VARCHAR(150) NOT NULL,

    slug VARCHAR(180) NOT NULL,

    deskripsi LONGTEXT DEFAULT NULL,

    harga DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    diskon DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    stok INT UNSIGNED NOT NULL DEFAULT 0,

    minimum_stok INT UNSIGNED NOT NULL DEFAULT 5,

    satuan VARCHAR(20) NOT NULL DEFAULT 'kg',

    berat INT UNSIGNED NOT NULL DEFAULT 0,

    thumbnail VARCHAR(255) DEFAULT NULL,

    meta_title VARCHAR(255) DEFAULT NULL,

    meta_description TEXT DEFAULT NULL,

    views INT UNSIGNED NOT NULL DEFAULT 0,

    sold INT UNSIGNED NOT NULL DEFAULT 0,

    is_featured BOOLEAN NOT NULL DEFAULT FALSE,

    status ENUM(
        'draft',
        'aktif',
        'nonaktif',
        'habis'
    ) NOT NULL DEFAULT 'draft',

    created_by INT UNSIGNED DEFAULT NULL,

    updated_by INT UNSIGNED DEFAULT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT uk_produk_kode UNIQUE (kode_produk),

    CONSTRAINT uk_produk_slug UNIQUE (slug),

    CONSTRAINT uk_produk_sku UNIQUE (sku)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- TABLE : produk_gambar
-- ==========================================================

CREATE TABLE produk_gambar (

    id_gambar INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    id_produk INT UNSIGNED NOT NULL,

    nama_file VARCHAR(255) NOT NULL,

    alt_text VARCHAR(255) DEFAULT NULL,

    urutan SMALLINT UNSIGNED NOT NULL DEFAULT 1,

    is_thumbnail BOOLEAN NOT NULL DEFAULT FALSE,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- FILE        : 04_customer.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Customer Module
-- VERSION     : 1.0.0
-- DBMS        : MySQL 8+
-- ==========================================================

-- ==========================================================
-- TABLE : pelanggan
-- ==========================================================

CREATE TABLE pelanggan (

    id_pelanggan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    kode_customer VARCHAR(20) NOT NULL,

    nama VARCHAR(100) NOT NULL,

    email VARCHAR(100) NOT NULL,

    password VARCHAR(255) NOT NULL,

    telepon VARCHAR(20) NOT NULL,

    alamat TEXT DEFAULT NULL,

    kota VARCHAR(100) DEFAULT NULL,

    provinsi VARCHAR(100) DEFAULT NULL,

    kode_pos VARCHAR(10) DEFAULT NULL,

    foto VARCHAR(255) DEFAULT NULL,

    email_verified_at DATETIME DEFAULT NULL,

    last_login DATETIME DEFAULT NULL,

    status ENUM(
        'aktif',
        'nonaktif',
        'blokir'
    ) NOT NULL DEFAULT 'aktif',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT uk_pelanggan_kode UNIQUE (kode_customer),

    CONSTRAINT uk_pelanggan_email UNIQUE (email)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- TABLE : keranjang
-- ==========================================================

CREATE TABLE keranjang (

    id_keranjang INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    id_pelanggan INT UNSIGNED NOT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- TABLE : keranjang_detail
-- ==========================================================

CREATE TABLE keranjang_detail (

    id_detail INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    id_keranjang INT UNSIGNED NOT NULL,

    id_produk INT UNSIGNED NOT NULL,

    qty INT UNSIGNED NOT NULL DEFAULT 1,

    harga DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- FILE        : 05_order.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Order Module
-- VERSION     : 1.0.0
-- DBMS        : MySQL 8+
-- ==========================================================

-- ==========================================================
-- TABLE : pesanan
-- ==========================================================

CREATE TABLE pesanan (

    id_pesanan INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    invoice VARCHAR(50) NOT NULL,

    id_pelanggan INT UNSIGNED NOT NULL,

    id_voucher INT UNSIGNED DEFAULT NULL,

    nama_penerima VARCHAR(100) NOT NULL,

    telepon_penerima VARCHAR(20) NOT NULL,

    alamat_pengiriman TEXT NOT NULL,

    kota_pengiriman VARCHAR(100) NOT NULL,

    provinsi_pengiriman VARCHAR(100) NOT NULL,

    kode_pos_pengiriman VARCHAR(10) DEFAULT NULL,

    subtotal DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    ongkir DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    diskon DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    total DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    catatan TEXT DEFAULT NULL,

    status ENUM(
        'menunggu_pembayaran',
        'diproses',
        'dikirim',
        'selesai',
        'dibatalkan'
    ) NOT NULL DEFAULT 'menunggu_pembayaran',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT uk_pesanan_invoice UNIQUE (invoice)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- TABLE : pesanan_detail
-- ==========================================================

CREATE TABLE pesanan_detail (

    id_detail INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    id_pesanan INT UNSIGNED NOT NULL,

    id_produk INT UNSIGNED NOT NULL,

    kode_produk VARCHAR(20) NOT NULL,

    nama_produk VARCHAR(150) NOT NULL,

    harga DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    diskon DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    qty INT UNSIGNED NOT NULL DEFAULT 1,

    berat INT UNSIGNED NOT NULL DEFAULT 0,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- FILE        : 06_payment.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Payment Module
-- VERSION     : 1.0.0
-- DBMS        : MySQL 8+
-- ==========================================================

-- ==========================================================
-- TABLE : voucher
-- ==========================================================

CREATE TABLE voucher (

    id_voucher INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    kode VARCHAR(30) NOT NULL,

    nama VARCHAR(100) NOT NULL,

    tipe ENUM(
        'persen',
        'nominal'
    ) NOT NULL,

    nilai DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    minimal_belanja DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    kuota INT UNSIGNED NOT NULL DEFAULT 0,

    digunakan INT UNSIGNED NOT NULL DEFAULT 0,

    tanggal_mulai DATE NOT NULL,

    tanggal_selesai DATE NOT NULL,

    status ENUM(
        'aktif',
        'nonaktif',
        'expired'
    ) NOT NULL DEFAULT 'aktif',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT uk_voucher_kode UNIQUE (kode)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- TABLE : pembayaran
-- ==========================================================

CREATE TABLE pembayaran (

    id_pembayaran INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    id_pesanan INT UNSIGNED NOT NULL,

    metode ENUM(
        'transfer',
        'qris',
        'cod',
        'ewallet'
    ) NOT NULL,

    bank VARCHAR(100) DEFAULT NULL,

    atas_nama VARCHAR(100) DEFAULT NULL,

    nomor_referensi VARCHAR(100) DEFAULT NULL,

    nominal DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    bukti_bayar VARCHAR(255) DEFAULT NULL,

    tanggal_bayar DATETIME DEFAULT NULL,

    verified_by INT UNSIGNED DEFAULT NULL,

    verified_at DATETIME DEFAULT NULL,

    status ENUM(
        'belum_bayar',
        'menunggu_verifikasi',
        'lunas',
        'ditolak'
    ) NOT NULL DEFAULT 'belum_bayar',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- TABLE : pengiriman
-- ==========================================================

CREATE TABLE pengiriman (

    id_pengiriman INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    id_pesanan INT UNSIGNED NOT NULL,

    kurir VARCHAR(50) NOT NULL,

    layanan VARCHAR(50) NOT NULL,

    nomor_resi VARCHAR(100) DEFAULT NULL,

    ongkir DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    tanggal_kirim DATETIME DEFAULT NULL,

    tanggal_terima DATETIME DEFAULT NULL,

    catatan TEXT DEFAULT NULL,

    status ENUM(
        'belum_dikirim',
        'dikemas',
        'dikirim',
        'diterima'
    ) NOT NULL DEFAULT 'belum_dikirim',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- TABLE : transaksi
-- ==========================================================

CREATE TABLE transaksi (

    id_transaksi INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    id_pembayaran INT UNSIGNED NOT NULL,

    kode_transaksi VARCHAR(30) NOT NULL,

    invoice VARCHAR(50) NOT NULL,

    total DECIMAL(12,2) NOT NULL DEFAULT 0.00,

    status ENUM(
        'berhasil',
        'gagal'
    ) NOT NULL DEFAULT 'berhasil',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uk_transaksi_kode UNIQUE (kode_transaksi)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ==========================================================
-- FILE        : 07_constraints.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Foreign Key Constraints
-- VERSION     : 1.0.0
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ==========================================================
-- PRODUK
-- ==========================================================

ALTER TABLE produk
ADD CONSTRAINT fk_produk_kategori
FOREIGN KEY (id_kategori)
REFERENCES kategori(id_kategori)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE produk
ADD CONSTRAINT fk_produk_created_by
FOREIGN KEY (created_by)
REFERENCES admin(id_admin)
ON UPDATE CASCADE
ON DELETE SET NULL;

ALTER TABLE produk
ADD CONSTRAINT fk_produk_updated_by
FOREIGN KEY (updated_by)
REFERENCES admin(id_admin)
ON UPDATE CASCADE
ON DELETE SET NULL;

-- ==========================================================
-- PRODUK GAMBAR
-- ==========================================================

ALTER TABLE produk_gambar
ADD CONSTRAINT fk_produk_gambar_produk
FOREIGN KEY (id_produk)
REFERENCES produk(id_produk)
ON UPDATE CASCADE
ON DELETE CASCADE;

-- ==========================================================
-- KERANJANG
-- ==========================================================

ALTER TABLE keranjang
ADD CONSTRAINT fk_keranjang_pelanggan
FOREIGN KEY (id_pelanggan)
REFERENCES pelanggan(id_pelanggan)
ON UPDATE CASCADE
ON DELETE CASCADE;

-- ==========================================================
-- KERANJANG DETAIL
-- ==========================================================

ALTER TABLE keranjang_detail
ADD CONSTRAINT fk_keranjang_detail_keranjang
FOREIGN KEY (id_keranjang)
REFERENCES keranjang(id_keranjang)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE keranjang_detail
ADD CONSTRAINT fk_keranjang_detail_produk
FOREIGN KEY (id_produk)
REFERENCES produk(id_produk)
ON UPDATE CASCADE
ON DELETE RESTRICT;

-- ==========================================================
-- PESANAN
-- ==========================================================

ALTER TABLE pesanan
ADD CONSTRAINT fk_pesanan_pelanggan
FOREIGN KEY (id_pelanggan)
REFERENCES pelanggan(id_pelanggan)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE pesanan
ADD CONSTRAINT fk_pesanan_voucher
FOREIGN KEY (id_voucher)
REFERENCES voucher(id_voucher)
ON UPDATE CASCADE
ON DELETE SET NULL;

-- ==========================================================
-- PESANAN DETAIL
-- ==========================================================

ALTER TABLE pesanan_detail
ADD CONSTRAINT fk_pesanan_detail_pesanan
FOREIGN KEY (id_pesanan)
REFERENCES pesanan(id_pesanan)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE pesanan_detail
ADD CONSTRAINT fk_pesanan_detail_produk
FOREIGN KEY (id_produk)
REFERENCES produk(id_produk)
ON UPDATE CASCADE
ON DELETE RESTRICT;

-- ==========================================================
-- PEMBAYARAN
-- ==========================================================

ALTER TABLE pembayaran
ADD CONSTRAINT fk_pembayaran_pesanan
FOREIGN KEY (id_pesanan)
REFERENCES pesanan(id_pesanan)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE pembayaran
ADD CONSTRAINT fk_pembayaran_verified_by
FOREIGN KEY (verified_by)
REFERENCES admin(id_admin)
ON UPDATE CASCADE
ON DELETE SET NULL;

-- ==========================================================
-- PENGIRIMAN
-- ==========================================================

ALTER TABLE pengiriman
ADD CONSTRAINT fk_pengiriman_pesanan
FOREIGN KEY (id_pesanan)
REFERENCES pesanan(id_pesanan)
ON UPDATE CASCADE
ON DELETE CASCADE;

-- ==========================================================
-- TRANSAKSI
-- ==========================================================

ALTER TABLE transaksi
ADD CONSTRAINT fk_transaksi_pembayaran
FOREIGN KEY (id_pembayaran)
REFERENCES pembayaran(id_pembayaran)
ON UPDATE CASCADE
ON DELETE CASCADE;

-- ==========================================================
-- LOG AKTIVITAS
-- ==========================================================

ALTER TABLE log_aktivitas
ADD CONSTRAINT fk_log_admin
FOREIGN KEY (id_admin)
REFERENCES admin(id_admin)
ON UPDATE CASCADE
ON DELETE RESTRICT;

SET FOREIGN_KEY_CHECKS = 1;



-- ==========================================================
-- FILE        : 08_indexes.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Database Performance Index
-- VERSION     : 1.0.0
-- DBMS        : MySQL 8+
-- ==========================================================

-- ==========================================================
-- ADMIN
-- ==========================================================

CREATE INDEX idx_admin_status
ON admin(status);

CREATE INDEX idx_admin_last_login
ON admin(last_login);



-- ==========================================================
-- KATEGORI
-- ==========================================================

CREATE INDEX idx_kategori_status
ON kategori(status);

CREATE INDEX idx_kategori_urutan
ON kategori(urutan);



-- ==========================================================
-- TESTIMONIAL
-- ==========================================================

CREATE INDEX idx_testimonial_status
ON testimonial(status);

CREATE INDEX idx_testimonial_featured
ON testimonial(is_featured);

CREATE INDEX idx_testimonial_urutan
ON testimonial(urutan);



-- ==========================================================
-- PRODUK
-- ==========================================================

CREATE INDEX idx_produk_kategori
ON produk(id_kategori);

CREATE INDEX idx_produk_status
ON produk(status);

CREATE INDEX idx_produk_featured
ON produk(is_featured);

CREATE INDEX idx_produk_views
ON produk(views);

CREATE INDEX idx_produk_sold
ON produk(sold);

CREATE INDEX idx_produk_created_at
ON produk(created_at);



-- ==========================================================
-- PRODUK GAMBAR
-- ==========================================================

CREATE INDEX idx_produk_gambar_produk
ON produk_gambar(id_produk);



-- ==========================================================
-- PELANGGAN
-- ==========================================================

CREATE INDEX idx_pelanggan_status
ON pelanggan(status);

CREATE INDEX idx_pelanggan_last_login
ON pelanggan(last_login);



-- ==========================================================
-- KERANJANG
-- ==========================================================

CREATE INDEX idx_keranjang_pelanggan
ON keranjang(id_pelanggan);



-- ==========================================================
-- KERANJANG DETAIL
-- ==========================================================

CREATE INDEX idx_keranjang_detail_keranjang
ON keranjang_detail(id_keranjang);

CREATE INDEX idx_keranjang_detail_produk
ON keranjang_detail(id_produk);



-- ==========================================================
-- PESANAN
-- ==========================================================

CREATE INDEX idx_pesanan_customer
ON pesanan(id_pelanggan);

CREATE INDEX idx_pesanan_status
ON pesanan(status);

CREATE INDEX idx_pesanan_created
ON pesanan(created_at);

CREATE INDEX idx_pesanan_voucher
ON pesanan(id_voucher);



-- ==========================================================
-- PESANAN DETAIL
-- ==========================================================

CREATE INDEX idx_pesanan_detail_pesanan
ON pesanan_detail(id_pesanan);

CREATE INDEX idx_pesanan_detail_produk
ON pesanan_detail(id_produk);



-- ==========================================================
-- VOUCHER
-- ==========================================================

CREATE INDEX idx_voucher_status
ON voucher(status);

CREATE INDEX idx_voucher_tanggal
ON voucher(tanggal_mulai, tanggal_selesai);



-- ==========================================================
-- PEMBAYARAN
-- ==========================================================

CREATE INDEX idx_pembayaran_pesanan
ON pembayaran(id_pesanan);

CREATE INDEX idx_pembayaran_status
ON pembayaran(status);

CREATE INDEX idx_pembayaran_tanggal
ON pembayaran(tanggal_bayar);

CREATE INDEX idx_pembayaran_verified
ON pembayaran(verified_by);



-- ==========================================================
-- PENGIRIMAN
-- ==========================================================

CREATE INDEX idx_pengiriman_pesanan
ON pengiriman(id_pesanan);

CREATE INDEX idx_pengiriman_status
ON pengiriman(status);

CREATE INDEX idx_pengiriman_resi
ON pengiriman(nomor_resi);



-- ==========================================================
-- TRANSAKSI
-- ==========================================================

CREATE INDEX idx_transaksi_pembayaran
ON transaksi(id_pembayaran);

CREATE INDEX idx_transaksi_invoice
ON transaksi(invoice);



-- ==========================================================
-- LOG AKTIVITAS
-- ==========================================================

CREATE INDEX idx_log_admin
ON log_aktivitas(id_admin);

CREATE INDEX idx_log_created
ON log_aktivitas(created_at);



-- ==========================================================
-- FILE        : 09_seed.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Seed Data (Starter Demo)
-- VERSION     : 1.0.0
-- ==========================================================

-- ==========================================================
-- ADMIN
-- Password hash harus disesuaikan dengan password_hash() aplikasi.
-- ==========================================================

INSERT INTO admin
(kode_admin,nama,username,email,password,foto,role,status)
VALUES
('ADM001','Faishal Hakim Nurrahman','faishal','admin@avolicius.id','CHANGE_WITH_PASSWORD_HASH',NULL,'super_admin','aktif'),
('ADM002','Admin Operasional','admin','operator@avolicius.id','CHANGE_WITH_PASSWORD_HASH',NULL,'admin','aktif');

-- ==========================================================
-- SETTING
-- ==========================================================

INSERT INTO setting(setting_key,setting_value,setting_group) VALUES
('website_name','AVOLICIUS','general'),
('tagline','Fresh Avocado Everyday','general'),
('email','admin@avolicius.id','general'),
('phone','081234567890','general'),
('address','Jl. Raya Kediri No.100','general'),
('logo','logo.png','appearance'),
('favicon','favicon.ico','appearance'),
('instagram','https://instagram.com/avolicius','social'),
('facebook','https://facebook.com/avolicius','social'),
('whatsapp','6281234567890','social');

-- ==========================================================
-- KATEGORI
-- ==========================================================

INSERT INTO kategori
(kode_kategori,nama_kategori,slug,warna,urutan,status)
VALUES
('KT001','Alpukat Mentega','alpukat-mentega','#4CAF50',1,'aktif'),
('KT002','Alpukat Hass','alpukat-hass','#8BC34A',2,'aktif'),
('KT003','Alpukat Miki','alpukat-miki','#689F38',3,'aktif'),
('KT004','Alpukat Kendil','alpukat-kendil','#558B2F',4,'aktif'),
('KT005','Paket Hemat','paket-hemat','#33691E',5,'aktif');

-- ==========================================================
-- TESTIMONIAL
-- ==========================================================

INSERT INTO testimonial
(nama,pekerjaan,isi_testimoni,rating,is_featured,status)
VALUES
('Ahmad Fauzi','Guru','Alpukat sangat segar.',5,1,'tampil'),
('Dewi Lestari','Dokter','Pengiriman cepat.',5,1,'tampil'),
('Rina Putri','Karyawan','Packing rapi.',5,0,'tampil'),
('Yoga Pratama','Wiraswasta','Kualitas premium.',5,0,'tampil');

-- ==========================================================
-- CONTOH PRODUK (lanjutkan pola hingga 25 produk)
-- ==========================================================

INSERT INTO produk
(kode_produk,sku,id_kategori,nama_produk,slug,harga,diskon,stok,minimum_stok,satuan,berat,thumbnail,is_featured,status)
VALUES
('PRD001','SKU000001',1,'Alpukat Mentega Premium 1 Kg','alpukat-mentega-premium-1kg',45000,5000,120,5,'kg',1000,'produk1.jpg',1,'aktif'),
('PRD002','SKU000002',2,'Alpukat Hass Grade A','alpukat-hass-grade-a',65000,10000,80,5,'kg',1000,'produk2.jpg',1,'aktif'),
('PRD003','SKU000003',5,'Paket Keluarga','paket-keluarga',120000,15000,40,5,'paket',3000,'produk3.jpg',0,'aktif');

INSERT INTO produk_gambar(id_produk,nama_file,alt_text,urutan,is_thumbnail)
VALUES
(1,'produk1.jpg','Alpukat Mentega Premium',1,1),
(1,'produk1_2.jpg','Alpukat Mentega Premium',2,0),
(2,'produk2.jpg','Alpukat Hass Grade A',1,1),
(3,'produk3.jpg','Paket Keluarga',1,1);

-- ==========================================================
-- CUSTOMER
-- ==========================================================

INSERT INTO pelanggan
(kode_customer,nama,email,password,telepon,alamat,kota,provinsi,status)
VALUES
('CUS001','Ahmad Fauzi','ahmad@mail.com','CHANGE_WITH_PASSWORD_HASH','081111111111','Jl. Mawar 1','Kediri','Jawa Timur','aktif'),
('CUS002','Dewi Lestari','dewi@mail.com','CHANGE_WITH_PASSWORD_HASH','082222222222','Jl. Melati 2','Nganjuk','Jawa Timur','aktif');

-- ==========================================================
-- VOUCHER
-- ==========================================================

INSERT INTO voucher
(kode,nama,tipe,nilai,minimal_belanja,kuota,digunakan,tanggal_mulai,tanggal_selesai,status)
VALUES
('WELCOME10','Voucher Member Baru','persen',10,100000,100,5,'2026-01-01','2026-12-31','aktif'),
('HEMAT20','Voucher Hemat','nominal',20000,150000,50,8,'2026-01-01','2026-12-31','aktif');

-- ==========================================================
-- CONTOH ORDER
-- ==========================================================

INSERT INTO pesanan
(invoice,id_pelanggan,id_voucher,nama_penerima,telepon_penerima,alamat_pengiriman,kota_pengiriman,provinsi_pengiriman,kode_pos_pengiriman,subtotal,ongkir,diskon,total,status)
VALUES
('INV-20260701-000001',1,1,'Ahmad Fauzi','081111111111','Jl. Mawar 1','Kediri','Jawa Timur','64111',90000,10000,9000,91000,'selesai');

INSERT INTO pesanan_detail
(id_pesanan,id_produk,kode_produk,nama_produk,harga,diskon,qty,berat)
VALUES
(1,1,'PRD001','Alpukat Mentega Premium 1 Kg',45000,5000,2,1000);

INSERT INTO pembayaran
(id_pesanan,metode,bank,atas_nama,nomor_referensi,nominal,tanggal_bayar,status)
VALUES
(1,'transfer','BCA','Ahmad Fauzi','TRX000001',91000,'2026-07-01 10:00:00','lunas');

INSERT INTO pengiriman
(id_pesanan,kurir,layanan,nomor_resi,ongkir,tanggal_kirim,tanggal_terima,status)
VALUES
(1,'JNE','REG','JNE123456789',10000,'2026-07-01 15:00:00','2026-07-03 10:00:00','diterima');

INSERT INTO transaksi
(id_pembayaran,kode_transaksi,invoice,total,status)
VALUES
(1,'TRX000001','INV-20260701-000001',91000,'berhasil');

INSERT INTO log_aktivitas
(id_admin,aktivitas)
VALUES
(1,'Login'),
(1,'Menambah Produk'),
(2,'Verifikasi Pembayaran'),
(1,'Logout');

-- CATATAN:
-- Perbanyak pola INSERT di atas hingga mencapai target:
-- 25 produk, 12 pelanggan, 20 pesanan, 15 pembayaran,
-- 15 pengiriman, 15 transaksi, dan 50 log aktivitas.




-- ==========================================================
-- FILE        : 10_views.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Database Views
-- VERSION     : 1.0.0
-- DBMS        : MySQL 8+
-- ==========================================================

-- ==========================================================
-- VIEW : Dashboard Summary
-- ==========================================================

CREATE OR REPLACE VIEW vw_dashboard_summary AS

SELECT

    (SELECT COUNT(*) FROM produk
        WHERE deleted_at IS NULL) AS total_produk,

    (SELECT COUNT(*) FROM pelanggan
        WHERE deleted_at IS NULL) AS total_customer,

    (SELECT COUNT(*) FROM pesanan
        WHERE deleted_at IS NULL) AS total_pesanan,

    (SELECT COUNT(*) FROM kategori
        WHERE deleted_at IS NULL) AS total_kategori,

    (SELECT IFNULL(SUM(total),0)
        FROM transaksi
        WHERE status='berhasil') AS total_pendapatan;

-- ==========================================================
-- VIEW : Produk Terlaris
-- ==========================================================

CREATE OR REPLACE VIEW vw_produk_terlaris AS

SELECT

    id_produk,
    kode_produk,
    nama_produk,
    sold,
    views,
    harga,
    diskon,
    stok

FROM produk

WHERE deleted_at IS NULL

ORDER BY sold DESC;



-- ==========================================================
-- VIEW : Produk Aktif
-- ==========================================================

CREATE OR REPLACE VIEW vw_produk_aktif AS

SELECT *

FROM produk

WHERE status='aktif'
AND deleted_at IS NULL;



-- ==========================================================
-- VIEW : Customer Aktif
-- ==========================================================

CREATE OR REPLACE VIEW vw_customer_aktif AS

SELECT

id_pelanggan,
kode_customer,
nama,
email,
telepon,
last_login

FROM pelanggan

WHERE status='aktif'
AND deleted_at IS NULL;



-- ==========================================================
-- VIEW : Voucher Aktif
-- ==========================================================

CREATE OR REPLACE VIEW vw_voucher_aktif AS

SELECT *

FROM voucher

WHERE status='aktif';



-- ==========================================================
-- VIEW : Pesanan Terbaru
-- ==========================================================

CREATE OR REPLACE VIEW vw_pesanan_terbaru AS

SELECT

invoice,

nama_penerima,

total,

status,

created_at

FROM pesanan

ORDER BY created_at DESC;



-- ==========================================================
-- VIEW : Pembayaran Lunas
-- ==========================================================

CREATE OR REPLACE VIEW vw_pembayaran_lunas AS

SELECT

id_pembayaran,

id_pesanan,

metode,

nominal,

tanggal_bayar

FROM pembayaran

WHERE status='lunas';



-- ==========================================================
-- VIEW : Pengiriman Aktif
-- ==========================================================

CREATE OR REPLACE VIEW vw_pengiriman_aktif AS

SELECT

id_pengiriman,

id_pesanan,

kurir,

layanan,

nomor_resi,

status

FROM pengiriman

WHERE status IN
('dikemas','dikirim');



-- ==========================================================
-- VIEW : Produk Stok Menipis
-- ==========================================================

CREATE OR REPLACE VIEW vw_produk_stok_minimum AS

SELECT

id_produk,

kode_produk,

nama_produk,

stok,

minimum_stok

FROM produk

WHERE stok<=minimum_stok
AND deleted_at IS NULL;



-- ==========================================================
-- VIEW : Riwayat Login Admin
-- ==========================================================

CREATE OR REPLACE VIEW vw_log_login_admin AS

SELECT

a.nama,

l.aktivitas,

l.ip_address,

l.created_at

FROM log_aktivitas l

INNER JOIN admin a
ON a.id_admin=l.id_admin

ORDER BY l.created_at DESC;