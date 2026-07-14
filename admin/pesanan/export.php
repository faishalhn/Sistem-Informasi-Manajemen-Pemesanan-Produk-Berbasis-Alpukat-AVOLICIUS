<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : export.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Export Data Pesanan
 * VERSION     : 1.0.0
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

$where = [];
$params = [];
$types  = '';

$where[] = "p.deleted_at IS NULL";

// ==========================================================
// SEARCH
// ==========================================================

if ($keyword !== '') {

    $where[] = "(
        p.invoice LIKE ?
        OR pl.nama LIKE ?
        OR pl.email LIKE ?
    )";

    $search = "%{$keyword}%";

    $params[] = $search;
    $params[] = $search;
    $params[] = $search;

    $types .= "sss";
}

// ==========================================================
// STATUS
// ==========================================================

if ($status !== '') {

    $where[] = "p.status = ?";

    $params[] = $status;

    $types .= "s";
}

$where_sql = implode(' AND ', $where);

// ==========================================================
// QUERY
// ==========================================================

$sql = "

SELECT

    p.invoice,

    pl.nama,

    pl.email,

    pl.telepon,

    p.total,

    p.status,

    COALESCE(pb.status,'belum_bayar') AS status_pembayaran,

    p.created_at

FROM pesanan p

LEFT JOIN pelanggan pl
ON pl.id_pelanggan = p.id_pelanggan

LEFT JOIN pembayaran pb
ON pb.id_pesanan = p.id_pesanan

WHERE {$where_sql}

ORDER BY p.created_at DESC

";

$stmt = mysqli_prepare($db, $sql);

if (!$stmt) {

    exit('Database Error');
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
// DOWNLOAD
// ==========================================================

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=pesanan_' . date('Ymd_His') . '.csv');

$output = fopen('php://output', 'w');

fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// ==========================================================
// HEADER
// ==========================================================

fputcsv($output, [

    'Invoice',

    'Pelanggan',

    'Email',

    'Telepon',

    'Total',

    'Status Pesanan',

    'Status Pembayaran',

    'Tanggal'

]);

// ==========================================================
// DATA
// ==========================================================

while ($row = mysqli_fetch_assoc($result)) {

    fputcsv($output, [

        $row['invoice'],

        $row['nama'],

        $row['email'],

        $row['telepon'],

        $row['total'],

        ucwords(str_replace('_', ' ', $row['status'])),

        ucwords(str_replace('_', ' ', $row['status_pembayaran'])),

        $row['created_at']

    ]);
}

fclose($output);

mysqli_stmt_close($stmt);

exit;
