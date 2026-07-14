<?php

/**
 * ==========================================================
 * FILE        : auth_customer.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Customer Authentication
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once __DIR__ . '/../config/init.php';

if (!isset($_SESSION[SESSION_CUSTOMER])) {

    redirect('customer/home.php');
}
