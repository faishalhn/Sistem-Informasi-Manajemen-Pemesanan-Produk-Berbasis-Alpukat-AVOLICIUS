<?php

/**
 * ==========================================================
 * FILE        : guest_customer.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Guest Middleware Customer
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once __DIR__ . '/../config/init.php';

// ==========================================================
// CHECK CUSTOMER SESSION
// ==========================================================

if (isset($_SESSION[SESSION_CUSTOMER])) {

    redirect('customer/home.php');
}
