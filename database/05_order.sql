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
