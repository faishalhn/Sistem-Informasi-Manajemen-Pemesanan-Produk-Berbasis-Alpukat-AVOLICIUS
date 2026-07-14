<?php

/**
 * ==========================================================
 * FILE        : database.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Database Connection
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

if (APP_ENV === 'development') {

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

$host = "localhost";

$username = "root";

$password = "";

$database = "avolicius_db";

$db = mysqli_connect(

    $host,

    $username,

    $password,

    $database

);

if (!$db) {

    die("Koneksi database gagal.");
}

mysqli_set_charset(

    $db,

    "utf8mb4"

);
