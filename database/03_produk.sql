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
