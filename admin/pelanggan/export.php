<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : export.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Export Customer CSV
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';
require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// FILTER
// ==========================================================

$keyword = trim($_GET['keyword'] ?? '');
$status  = trim($_GET['status'] ?? '');
$kota    = trim($_GET['kota'] ?? '');

// ==========================================================
// WHERE
// ==========================================================

$where = [];
$params = [];
$types = '';

$where[] = "p.deleted_at IS NULL";

// ==========================================================
// SEARCH
// ==========================================================

if ($keyword !== '') {

    $where[] = "

    (

        p.kode_customer LIKE ?

        OR p.nama LIKE ?

        OR p.email LIKE ?

        OR p.telepon LIKE ?

    )

    ";

    $search = "%{$keyword}%";

    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;

    $types .= "ssss";
}

// ==========================================================
// STATUS
// ==========================================================

if ($status !== '') {

    $where[] = "p.status = ?";

    $params[] = $status;

    $types .= "s";
}

// ==========================================================
// KOTA
// ==========================================================

if ($kota !== '') {

    $where[] = "p.kota LIKE ?";

    $params[] = "%{$kota}%";

    $types .= "s";
}

// ==========================================================
// WHERE SQL
// ==========================================================

$where_sql = implode(

    ' AND ',

    $where

);

// ==========================================================
// QUERY
// ==========================================================

$sql = "

SELECT

    p.kode_customer,

    p.nama,

    p.email,

    p.telepon,

    p.kota,

    p.status,

    p.created_at,

    COUNT(ps.id_pesanan) AS total_pesanan,

    COALESCE(

        SUM(ps.total),

        0

    ) AS total_belanja

FROM pelanggan p

LEFT JOIN pesanan ps

ON

    ps.id_pelanggan = p.id_pelanggan

    AND ps.deleted_at IS NULL

WHERE

{$where_sql}

GROUP BY

    p.id_pelanggan

ORDER BY

    p.nama ASC

";

$stmt = mysqli_prepare(
    $db,
    $sql
);

if (!$stmt) {

    exit('Query gagal.');
}

if ($types !== '') {

    mysqli_stmt_bind_param(

        $stmt,

        $types,

        ...$params

    );
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

// ==========================================================
// EXPORT CSV
// ==========================================================

header('Content-Type: text/csv; charset=UTF-8');

header(

    'Content-Disposition: attachment; filename="pelanggan_' .

        date('Ymd_His') .

        '.csv"'

);

$output = fopen(

    'php://output',

    'w'

);

// UTF-8 BOM
fprintf(

    $output,

    chr(0xEF) .

        chr(0xBB) .

        chr(0xBF)

);

// HEADER
fputcsv($output, [

    'No',

    'Kode Customer',

    'Nama',

    'Email',

    'Telepon',

    'Kota',

    'Status',

    'Total Pesanan',

    'Total Belanja',

    'Tanggal Bergabung'

]);

// DATA
$no = 1;

while ($row = mysqli_fetch_assoc($result)) {

    fputcsv($output, [

        $no++,

        $row['kode_customer'],

        $row['nama'],

        $row['email'],

        $row['telepon'],

        $row['kota'],

        ucfirst($row['status']),

        $row['total_pesanan'],

        $row['total_belanja'],

        $row['created_at']

    ]);
}

fclose($output);

mysqli_stmt_close($stmt);

exit;
