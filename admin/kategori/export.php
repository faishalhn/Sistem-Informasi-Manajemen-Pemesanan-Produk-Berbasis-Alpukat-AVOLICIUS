<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : export.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Export Category CSV
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

$status = trim($_GET['status'] ?? '');

// ==========================================================
// WHERE
// ==========================================================

$where = [];

$params = [];

$types = '';

$where[] = "k.deleted_at IS NULL";

if ($keyword !== '') {

    $where[] = "

    (

        k.kode_kategori LIKE ?

        OR k.nama_kategori LIKE ?

        OR k.slug LIKE ?

        OR k.deskripsi LIKE ?

    )

    ";

    $search = "%{$keyword}%";

    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;

    $types .= "ssss";
}

if ($status !== '') {

    $where[] = "k.status=?";

    $params[] = $status;

    $types .= "s";
}

$where_sql = implode(

    ' AND ',

    $where

);

// ==========================================================
// QUERY
// ==========================================================

$sql = "

SELECT

    k.kode_kategori,

    k.nama_kategori,

    k.slug,

    k.warna,

    k.urutan,

    k.status,

    COUNT(DISTINCT p.id_produk) AS jumlah_produk

FROM kategori k

LEFT JOIN produk p

ON

    p.id_kategori = k.id_kategori

    AND p.deleted_at IS NULL

WHERE

{$where_sql}

GROUP BY

    k.id_kategori,

    k.kode_kategori,

    k.nama_kategori,

    k.slug,

    k.warna,

    k.urutan,

    k.status

ORDER BY

    k.urutan ASC,

    k.nama_kategori ASC

";

$stmt = mysqli_prepare(

    $db,

    $sql

);

if (!$stmt) {

    exit('Gagal menyiapkan query.');
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

$filename =

    'kategori_'

    . date('Ymd_His')

    . '.csv';

header('Content-Type: text/csv; charset=UTF-8');

header(

    'Content-Disposition: attachment; filename="' . $filename . '"'

);

// UTF-8 BOM

echo "\xEF\xBB\xBF";

$output = fopen(

    'php://output',

    'w'

);

// ==========================================================
// HEADER
// ==========================================================

fputcsv(

    $output,

    [

        'No',

        'Kode Kategori',

        'Nama Kategori',

        'Slug',

        'Warna',

        'Jumlah Produk',

        'Urutan',

        'Status'

    ]

);

// ==========================================================
// DATA
// ==========================================================

$no = 1;

while (

    $row = mysqli_fetch_assoc($result)

) {

    fputcsv(

        $output,

        [

            $no++,

            $row['kode_kategori'],

            $row['nama_kategori'],

            $row['slug'],

            $row['warna'],

            $row['jumlah_produk'],

            $row['urutan'],

            ucfirst($row['status'])

        ]

    );
}

fclose($output);

mysqli_stmt_close($stmt);

exit;
