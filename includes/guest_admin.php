<?php

/**
 * ==========================================================
 * FILE        : guest_admin.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Guest Admin
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once __DIR__ . '/../config/init.php';

if (is_admin_login()) {

    redirect('admin/dashboard/');
}
