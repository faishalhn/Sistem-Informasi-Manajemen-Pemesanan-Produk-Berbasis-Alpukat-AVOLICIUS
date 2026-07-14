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
