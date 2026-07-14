<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : produk_data.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Product Data, Statistics & Pagination
 * VERSION     : 2.1.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// FILTER
// ==========================================================

$keyword = trim($_GET['keyword'] ?? '');

$id_kategori = (int) ($_GET['kategori'] ?? 0);

$status = trim($_GET['status'] ?? '');

$page = max(1, (int) ($_GET['page'] ?? 1));

// ==========================================================
// PAGINATION
// ==========================================================

$limit = 10;

$offset = ($page - 1) * $limit;

// ==========================================================
// DEFAULT VALUE
// ==========================================================

$products = [];

$kategori_list = [];

$total_rows = 0;

$total_pages = 1;

// ==========================================================
// SUMMARY
// ==========================================================

$total_produk = 0;

$total_produk_aktif = 0;

$total_produk_nonaktif = 0;

$total_produk_draft = 0;

$total_produk_habis = 0;

$total_produk_featured = 0;

$total_stok_rendah = 0;

$total_views = 0;

$total_sold = 0;

// ==========================================================
// LOAD KATEGORI
// ==========================================================

$sql = "

SELECT

    id_kategori,

    nama_kategori

FROM kategori

WHERE deleted_at IS NULL

ORDER BY

    urutan ASC,

    nama_kategori ASC

";

$result = mysqli_query($db, $sql);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $kategori_list[] = [

            'id_kategori'   => (int) $row['id_kategori'],

            'nama_kategori' => $row['nama_kategori']

        ];
    }
}

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

// ==========================================================
// WHERE SQL
// ==========================================================

$where_sql = implode(' AND ', $where);

// ==========================================================
// PRODUCT SUMMARY
// ==========================================================

$sql = "

SELECT

    COUNT(*) AS total_produk,

    SUM(
        CASE
            WHEN status='aktif'
            THEN 1
            ELSE 0
        END
    ) AS total_produk_aktif,

    SUM(
        CASE
            WHEN status='draft'
            THEN 1
            ELSE 0
        END
    ) AS total_produk_draft,

    SUM(
        CASE
            WHEN status='nonaktif'
            THEN 1
            ELSE 0
        END
    ) AS total_produk_nonaktif,

    SUM(
        CASE
            WHEN status='habis'
            THEN 1
            ELSE 0
        END
    ) AS total_produk_habis,

    SUM(
        CASE
            WHEN is_featured=1
            THEN 1
            ELSE 0
        END
    ) AS total_produk_featured,

    SUM(
        CASE
            WHEN stok<=minimum_stok
            THEN 1
            ELSE 0
        END
    ) AS total_stok_rendah,

    IFNULL(SUM(views),0) AS total_views,

    IFNULL(SUM(sold),0) AS total_sold

FROM produk

WHERE deleted_at IS NULL

";

$result = mysqli_query($db, $sql);

if ($result) {

    $summary = mysqli_fetch_assoc($result);

    $total_produk = (int) $summary['total_produk'];

    $total_produk_aktif = (int) $summary['total_produk_aktif'];

    $total_produk_draft = (int) $summary['total_produk_draft'];

    $total_produk_nonaktif = (int) $summary['total_produk_nonaktif'];

    $total_produk_habis = (int) $summary['total_produk_habis'];

    $total_produk_featured = (int) $summary['total_produk_featured'];

    $total_stok_rendah = (int) $summary['total_stok_rendah'];

    $total_views = (int) $summary['total_views'];

    $total_sold = (int) $summary['total_sold'];
}
// ==========================================================
// TOTAL DATA
// ==========================================================

$sql = "

SELECT

    COUNT(*) AS total

FROM produk p

WHERE

{$where_sql}

";

$stmt = mysqli_prepare($db, $sql);

if ($stmt) {

    if ($types !== '') {

        mysqli_stmt_bind_param(

            $stmt,

            $types,

            ...$params

        );
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {

        $total_rows = (int) mysqli_fetch_assoc($result)['total'];
    }

    mysqli_stmt_close($stmt);
}

// ==========================================================
// TOTAL PAGE
// ==========================================================

$total_pages = max(

    1,

    (int) ceil($total_rows / $limit)

);

// ==========================================================
// PRODUCT QUERY
// ==========================================================

$sql = "

SELECT

    p.id_produk,

    p.kode_produk,

    p.sku,

    p.barcode,

    p.id_kategori,

    p.nama_produk,

    p.slug,

    p.deskripsi,

    p.harga,

    p.diskon,

    p.stok,

    p.minimum_stok,

    p.satuan,

    p.berat,

    p.thumbnail,

    p.meta_title,

    p.meta_description,

    p.views,

    p.sold,

    p.is_featured,

    p.status,

    p.created_by,

    p.updated_by,

    p.created_at,

    p.updated_at,

    k.nama_kategori

FROM produk p

LEFT JOIN kategori k

ON k.id_kategori = p.id_kategori

WHERE

{$where_sql}

ORDER BY

    p.created_at DESC,

    p.id_produk DESC

LIMIT ?, ?

";

// ==========================================================
// PARAMETER QUERY
// ==========================================================

$query_types = $types;

$query_params = $params;

$query_types .= "ii";

$query_params[] = $offset;

$query_params[] = $limit;

// ==========================================================
// EXECUTE
// ==========================================================

$stmt = mysqli_prepare($db, $sql);

if ($stmt) {

    mysqli_stmt_bind_param(

        $stmt,

        $query_types,

        ...$query_params

    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    // ==========================================================
    // FETCH PRODUCT
    // ==========================================================

    if ($result) {

        while ($row = mysqli_fetch_assoc($result)) {

            // ------------------------------------------
            // THUMBNAIL
            // ------------------------------------------

            $thumbnail = !empty($row['thumbnail'])

                ? $row['thumbnail']

                : 'default-product.png';

            // ------------------------------------------
            // STATUS BADGE
            // ------------------------------------------

            $badge = match ($row['status']) {

                'aktif' => 'success',

                'draft' => 'secondary',

                'nonaktif' => 'warning',

                'habis' => 'danger',

                default => 'dark'
            };

            // ------------------------------------------
            // STOCK STATUS
            // ------------------------------------------

            $stock_status = 'normal';

            if ((int) $row['stok'] <= 0) {

                $stock_status = 'habis';
            } elseif (

                (int) $row['stok']

                <=

                (int) $row['minimum_stok']

            ) {

                $stock_status = 'warning';
            }

            // ------------------------------------------
            // FEATURED
            // ------------------------------------------

            $featured =

                (bool) $row['is_featured'];

            // ------------------------------------------
            // PRODUCT ARRAY
            // ------------------------------------------

            $product = [

                'id_produk' => (int) $row['id_produk'],

                'kode_produk' => $row['kode_produk'],

                'sku' => $row['sku'],

                'barcode' => $row['barcode'],

                'id_kategori' => (int) $row['id_kategori'],

                'nama_produk' => $row['nama_produk'],

                'slug' => $row['slug'],

                'deskripsi' => $row['deskripsi'],

                'harga' => (float) $row['harga'],

                'diskon' => (float) $row['diskon'],

                'stok' => (int) $row['stok'],

                'minimum_stok' => (int) $row['minimum_stok'],

                'satuan' => $row['satuan'],

                'berat' => (float) $row['berat'],

                'thumbnail' => $thumbnail,

                'meta_title' => $row['meta_title'],

                'meta_description' => $row['meta_description'],

                'views' => (int) $row['views'],

                'sold' => (int) $row['sold'],

                'featured' => $featured,

                'status' => $row['status'],

                'badge' => $badge,

                'stock_status' => $stock_status,

                'created_by' => $row['created_by'],

                'updated_by' => $row['updated_by'],

                'created_at' => $row['created_at'],

                'updated_at' => $row['updated_at'],

                'kategori' => $row['nama_kategori']

            ];

            $products[] = $product;
        }
    }

    mysqli_stmt_close($stmt);
}
// ==========================================================
// PAGINATION ARRAY
// ==========================================================

$pagination = [

    'page' => $page,

    'limit' => $limit,

    'offset' => $offset,

    'total_rows' => $total_rows,

    'total_pages' => $total_pages,

    'has_previous' => $page > 1,

    'has_next' => $page < $total_pages,

    'previous_page' => max(1, $page - 1),

    'next_page' => min($total_pages, $page + 1)

];

// ==========================================================
// SUMMARY ARRAY
// ==========================================================

$summary = [

    'total_produk' => $total_produk,

    'produk_aktif' => $total_produk_aktif,

    'produk_nonaktif' => $total_produk_nonaktif,

    'produk_draft' => $total_produk_draft,

    'produk_habis' => $total_produk_habis,

    'produk_featured' => $total_produk_featured,

    'stok_rendah' => $total_stok_rendah,

    'total_views' => $total_views,

    'total_sold' => $total_sold

];

// ==========================================================
// PAGE INFORMATION
// ==========================================================

$page_information = [

    'show_from' =>

    $total_rows > 0

        ? ($offset + 1)

        : 0,

    'show_to' =>

    min(

        $offset + $limit,

        $total_rows

    ),

    'total_data' => $total_rows

];

// ==========================================================
// SORT OPTION
// ==========================================================

$sort_options = [

    'newest' => 'Terbaru',

    'oldest' => 'Terlama',

    'name_asc' => 'Nama A-Z',

    'name_desc' => 'Nama Z-A',

    'price_low' => 'Harga Termurah',

    'price_high' => 'Harga Tertinggi',

    'stock_low' => 'Stok Terendah',

    'stock_high' => 'Stok Tertinggi'

];

// ==========================================================
// STATUS OPTION
// ==========================================================

$status_options = [

    'aktif' => 'Aktif',

    'draft' => 'Draft',

    'nonaktif' => 'Nonaktif',

    'habis' => 'Habis'

];

// ==========================================================
// PRODUCT INFORMATION
// ==========================================================

$product_information = [

    'total_products' => count($products),

    'empty_result' => empty($products),

    'has_data' => !empty($products)

];

// ==========================================================
// END OF FILE
// ==========================================================