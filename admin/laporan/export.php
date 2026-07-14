<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : export.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Export Laporan CSV
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

$status = trim($_GET['status'] ?? '');

$tanggal_awal = trim($_GET['tanggal_awal'] ?? '');

$tanggal_akhir = trim($_GET['tanggal_akhir'] ?? '');

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

        p.invoice LIKE ?

        OR pl.nama LIKE ?

        OR pl.email LIKE ?

    )

    ";

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

// ==========================================================
// FILTER TANGGAL
// ==========================================================

if ($tanggal_awal !== '') {

    $where[] = "DATE(p.created_at) >= ?";

    $params[] = $tanggal_awal;

    $types .= "s";
}

if ($tanggal_akhir !== '') {

    $where[] = "DATE(p.created_at) <= ?";

    $params[] = $tanggal_akhir;

    $types .= "s";
}

$where_sql = implode(' AND ', $where);

// ==========================================================
// QUERY
// ==========================================================

$sql = "

SELECT

    p.invoice,

    p.created_at,

    pl.nama,

    pl.email,

    COUNT(pd.id_detail) AS total_item,

    p.total,

    p.status,

    COALESCE(

        pb.status,

        'belum_bayar'

    ) AS status_pembayaran

FROM pesanan p

LEFT JOIN pelanggan pl

ON

    pl.id_pelanggan = p.id_pelanggan

LEFT JOIN pesanan_detail pd

ON

    pd.id_pesanan = p.id_pesanan

LEFT JOIN (

    SELECT

        id_pesanan,

        MAX(id_pembayaran) AS id_pembayaran

    FROM pembayaran

    GROUP BY id_pesanan

) last_pb

ON

    last_pb.id_pesanan = p.id_pesanan

LEFT JOIN pembayaran pb

ON

    pb.id_pembayaran = last_pb.id_pembayaran

WHERE

{$where_sql}

GROUP BY

    p.id_pesanan

ORDER BY

    p.created_at DESC

";

$stmt = mysqli_prepare($db, $sql);

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

    'Content-Disposition: attachment; filename="laporan_penjualan_' .

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

// HEADER CSV
fputcsv($output, [

    'No',

    'Invoice',

    'Tanggal',

    'Pelanggan',

    'Email',

    'Jumlah Item',

    'Total',

    'Status Pesanan',

    'Status Pembayaran'

]);

// DATA
$no = 1;

while ($row = mysqli_fetch_assoc($result)) {

    fputcsv($output, [

        $no++,

        $row['invoice'],

        $row['created_at'],

        $row['nama'],

        $row['email'],

        $row['total_item'],

        $row['total'],

        ucwords(str_replace('_', ' ', $row['status'])),

        ucwords(str_replace('_', ' ', $row['status_pembayaran']))

    ]);
}

fclose($output);

mysqli_stmt_close($stmt);

exit;
