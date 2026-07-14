<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : session.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Session Configuration
 * VERSION     : 1.1.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

// ==========================================================
// SESSION CONFIGURATION
// ==========================================================

ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');

// ==========================================================
// START SESSION
// ==========================================================

if (session_status() === PHP_SESSION_NONE) {

    session_start();
}
