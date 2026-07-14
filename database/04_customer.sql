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
