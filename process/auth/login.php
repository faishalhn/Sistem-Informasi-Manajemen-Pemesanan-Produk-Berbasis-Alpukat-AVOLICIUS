<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : login.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Login Process
 * VERSION     : 3.0.0
 * ==========================================================
 */

require_once '../../config/init.php';
/** @var mysqli $db */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect('admin/login.php');
}


$email = trim($_POST['email'] ?? '');

$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {

    $_SESSION['error'] = 'Email dan Password wajib diisi.';

    redirect('admin/login.php');
}

$sql = "

SELECT *

FROM admin

WHERE

email = ?

AND status='aktif'

LIMIT 1

";

$stmt = mysqli_prepare($db, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "s",

    $email

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$admin = mysqli_fetch_assoc($result)) {

    $_SESSION['error'] = 'Email atau Password salah.';

    redirect('admin/login.php');
}

if (!password_verify($password, $admin['password'])) {

    $_SESSION['error'] = 'Email atau Password salah.';

    redirect('admin/login.php');
}

/*
|--------------------------------------------------------------------------
| SESSION
|--------------------------------------------------------------------------
*/

$_SESSION[SESSION_ADMIN] = [

    'id'    => $admin['id_admin'],

    'nama'  => $admin['nama'],

    'email' => $admin['email'],

    'role'  => $admin['role']

];

/*
|--------------------------------------------------------------------------
| UPDATE LAST LOGIN
|--------------------------------------------------------------------------
*/

$sql = "

UPDATE admin

SET

last_login = NOW()

WHERE

id_admin = ?

";

$stmt = mysqli_prepare($db, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $admin['id_admin']

);

mysqli_stmt_execute($stmt);

$_SESSION['success'] = 'Login berhasil.';

redirect('admin/dashboard/');
