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
