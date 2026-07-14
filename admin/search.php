<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : search.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Global Search API
 * VERSION     : 2.0.0
 * ==========================================================
 */

require_once '../config/init.php';

/** @var mysqli $db */

header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION[SESSION_ADMIN])) {

    http_response_code(403);

    echo json_encode([]);

    exit;
}

$keyword = trim($_GET['q'] ?? '');

if ($keyword === '') {

    echo json_encode([]);

    exit;
}

$search = '%' . $keyword . '%';

$results = [];

/**
 * ==========================================================
 * PUSH RESULT
 * ==========================================================
 */

function push_result(
    array &$results,
    string $type,
    string $icon,
    string $title,
    string $subtitle,
    string $url
): void {

    $results[] = [

        'type'      => $type,
        'icon'      => $icon,
        'title'     => $title,
        'subtitle'  => $subtitle,
        'url'       => $url

    ];
}
// ==========================================================
// PRODUK
// ==========================================================

$sql = "

SELECT

    id_produk,
    nama_produk,
    kode_produk,
    sku,
    barcode,
    harga

FROM produk

WHERE

    deleted_at IS NULL

    AND status = 'aktif'

    AND (

        nama_produk LIKE ?

        OR kode_produk LIKE ?

        OR sku LIKE ?

        OR barcode LIKE ?

    )

ORDER BY

    nama_produk ASC

LIMIT 5

";

$stmt = mysqli_prepare($db, $sql);

if ($stmt) {

    mysqli_stmt_bind_param(

        $stmt,

        'ssss',

        $search,
        $search,
        $search,
        $search

    );

    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($query)) {

        push_result(

            $results,

            'Produk',

            'box-seam',

            $row['nama_produk'],

            'Kode : ' .
                $row['kode_produk'] .
                ' • Rp ' .
                number_format(
                    (float) $row['harga'],
                    0,
                    ',',
                    '.'
                ),

            BASE_URL .
                'admin/produk/form.php?id=' .
                $row['id_produk']

        );
    }

    mysqli_stmt_close($stmt);
}
// ==========================================================
// KATEGORI
// ==========================================================

$sql = "

SELECT

    id_kategori,
    kode_kategori,
    nama_kategori,
    slug

FROM kategori

WHERE

    deleted_at IS NULL

    AND status = 'aktif'

    AND (

        nama_kategori LIKE ?

        OR kode_kategori LIKE ?

        OR slug LIKE ?

    )

ORDER BY

    nama_kategori ASC

LIMIT 5

";

$stmt = mysqli_prepare($db, $sql);

if ($stmt) {

    mysqli_stmt_bind_param(

        $stmt,

        'sss',

        $search,
        $search,
        $search

    );

    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($query)) {

        push_result(

            $results,

            'Kategori',

            'tags',

            $row['nama_kategori'],

            'Kode : ' . $row['kode_kategori'],

            BASE_URL . 'admin/kategori/'

        );
    }

    mysqli_stmt_close($stmt);
}
// ==========================================================
// PELANGGAN
// ==========================================================

$sql = "

SELECT

    id_pelanggan,
    kode_customer,
    nama,
    email,
    telepon

FROM pelanggan

WHERE

    deleted_at IS NULL

    AND status = 'aktif'

    AND (

        kode_customer LIKE ?

        OR nama LIKE ?

        OR email LIKE ?

        OR telepon LIKE ?

    )

ORDER BY

    nama ASC

LIMIT 5

";

$stmt = mysqli_prepare($db, $sql);

if ($stmt) {

    mysqli_stmt_bind_param(

        $stmt,

        'ssss',

        $search,
        $search,
        $search,
        $search

    );

    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($query)) {

        push_result(

            $results,

            'Pelanggan',

            'person',

            $row['nama'],

            $row['kode_customer'] .
                ' • ' .
                $row['email'],

            BASE_URL .
                'admin/pelanggan/detail.php?id=' .
                $row['id_pelanggan']

        );
    }

    mysqli_stmt_close($stmt);
}
// ==========================================================
// PESANAN
// ==========================================================

$sql = "

SELECT

    id_pesanan,
    invoice,
    nama_penerima,
    telepon_penerima,
    total,
    status

FROM pesanan

WHERE

    deleted_at IS NULL

    AND (

        invoice LIKE ?

        OR nama_penerima LIKE ?

        OR telepon_penerima LIKE ?

    )

ORDER BY

    id_pesanan DESC

LIMIT 5

";

$stmt = mysqli_prepare($db, $sql);

if ($stmt) {

    mysqli_stmt_bind_param(

        $stmt,

        'sss',

        $search,
        $search,
        $search

    );

    mysqli_stmt_execute($stmt);

    $query = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($query)) {

        push_result(

            $results,

            'Pesanan',

            'receipt',

            $row['invoice'],

            $row['nama_penerima'] .
                ' • Rp ' .
                number_format(
                    (float) $row['total'],
                    0,
                    ',',
                    '.'
                ),

            BASE_URL .
                'admin/pesanan/detail.php?id=' .
                $row['id_pesanan']

        );
    }

    mysqli_stmt_close($stmt);
}
// ==========================================================
// SORT RESULT
// ==========================================================

usort(

    $results,

    static function (array $a, array $b): int {

        $priority = [

            'Produk'    => 1,
            'Kategori'  => 2,
            'Pelanggan' => 3,
            'Pesanan'   => 4

        ];

        $left = $priority[$a['type']] ?? 99;

        $right = $priority[$b['type']] ?? 99;

        if ($left === $right) {

            return strcmp($a['title'], $b['title']);
        }

        return $left <=> $right;
    }

);

// ==========================================================
// LIMIT RESULT
// ==========================================================

$results = array_slice($results, 0, 20);

// ==========================================================
// OUTPUT JSON
// ==========================================================

echo json_encode(

    $results,

    JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES

);

exit;
