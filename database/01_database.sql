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
