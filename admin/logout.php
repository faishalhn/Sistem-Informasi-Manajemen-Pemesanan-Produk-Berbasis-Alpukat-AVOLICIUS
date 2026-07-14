<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : logout.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Redirect Logout Administrator
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../config/init.php';

redirect('process/auth/logout.php');
