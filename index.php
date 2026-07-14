<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : index.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Front Controller
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once __DIR__ . '/config/init.php';

// ==========================================================
// ADMIN SUDAH LOGIN
// ==========================================================

if (is_admin_login()) {

    redirect('admin/dashboard/');
}

// ==========================================================
// CUSTOMER SUDAH LOGIN
// ==========================================================

if (is_customer_login()) {

    redirect('customer/home.php');
}

// ==========================================================
// BELUM LOGIN
// ==========================================================

redirect('admin/login.php');
