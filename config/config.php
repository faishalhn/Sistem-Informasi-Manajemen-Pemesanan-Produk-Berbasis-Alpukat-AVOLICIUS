<?php

/**
 * ==========================================================
 * FILE        : config.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Konfigurasi Aplikasi
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

// ==========================================================
// APPLICATION
// ==========================================================

define('APP_NAME', 'AVOLICIUS');

define('APP_VERSION', '1.0.0');

define('APP_ENV', 'development');

// ==========================================================
// URL
// ==========================================================

#define('BASE_URL', 'http://localhost/avolicius/');
define('BASE_URL', 'http://webbeta.test/');

// ==========================================================
// TIMEZONE
// ==========================================================

date_default_timezone_set('Asia/Jakarta');

// ==========================================================
// SESSION
// ==========================================================

define('SESSION_ADMIN', 'admin');

define('SESSION_CUSTOMER', 'customer');
