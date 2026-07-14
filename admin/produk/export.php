<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : export.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Export Product CSV
 * VERSION     : 2.1.0
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

$id_kategori = (int) ($_GET['kategori'] ?? 0);

$status = trim($_GET['status'] ?? '');

// ==========================================================
// BUILD WHERE
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

        p.nama_produk LIKE ?

        OR p.kode_produk LIKE ?

        OR p.sku LIKE ?

        OR p.barcode LIKE ?

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
// FILTER KATEGORI
// ==========================================================

if ($id_kategori > 0) {

    $where[] = "p.id_kategori = ?";

    $params[] = $id_kategori;

    $types .= "i";
}

// ==========================================================
// FILTER STATUS
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

    p.kode_produk,

    p.nama_produk,

    k.nama_kategori,

    p.sku,

    p.barcode,

    p.harga,

    p.diskon,

    p.stok,

    p.satuan,

    p.views,

    p.sold,

    p.status,

    p.is_featured,

    p.created_at

FROM produk p

LEFT JOIN kategori k

ON k.id_kategori = p.id_kategori

WHERE

{$where_sql}

ORDER BY

    p.created_at DESC,

    p.id_produk DESC

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
// HEADER DOWNLOAD
// ==========================================================

$filename =

    'Produk_'

    . date('Y-m-d_H-i-s')

    . '.csv';

header('Content-Type: text/csv; charset=UTF-8');

header(

    'Content-Disposition: attachment; filename="' . $filename . '"'

);

// UTF-8 BOM
echo "\xEF\xBB\xBF";

$output = fopen('php://output', 'w');

// ==========================================================
// HEADER CSV
// ==========================================================

fputcsv(

    $output,

    [

        'Kode Produk',

        'Nama Produk',

        'Kategori',

        'SKU',

        'Barcode',

        'Harga',

        'Diskon',

        'Stok',

        'Satuan',

        'Views',

        'Sold',

        'Status',

        'Featured',

        'Tanggal Dibuat'

    ]

);
// ==========================================================
// EXPORT DATA
// ==========================================================

while ($row = mysqli_fetch_assoc($result)) {

    $featured =

        (int) $row['is_featured'] === 1

        ? 'Ya'

        : 'Tidak';

    $harga = number_format(

        (float) $row['harga'],

        0,

        ',',

        '.'

    );

    $diskon = number_format(

        (float) $row['diskon'],

        0,

        ',',

        '.'

    );

    $tanggal =

        !empty($row['created_at'])

        ? date(

            'd-m-Y H:i',

            strtotime($row['created_at'])

        )

        : '-';

    fputcsv(

        $output,

        [

            $row['kode_produk'],

            $row['nama_produk'],

            $row['nama_kategori'],

            $row['sku'],

            $row['barcode'],

            $harga,

            $diskon,

            $row['stok'],

            $row['satuan'],

            $row['views'],

            $row['sold'],

            ucfirst($row['status']),

            $featured,

            $tanggal

        ]

    );
}

// ==========================================================
// CLOSE
// ==========================================================

fclose($output);

mysqli_stmt_close($stmt);

exit;
