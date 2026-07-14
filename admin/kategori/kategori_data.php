<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : kategori_data.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Category Data, Statistics & Pagination
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// FILTER
// ==========================================================

$keyword = trim($_GET['keyword'] ?? '');

$status = trim($_GET['status'] ?? '');

$page = max(1, (int) ($_GET['page'] ?? 1));

// ==========================================================
// PAGINATION
// ==========================================================

$limit = 10;

$offset = ($page - 1) * $limit;

// ==========================================================
// DEFAULT
// ==========================================================

$categories = [];

$total_rows = 0;

$total_pages = 1;

// ==========================================================
// SUMMARY
// ==========================================================

$total_kategori = 0;

$total_aktif = 0;

$total_nonaktif = 0;

$total_produk = 0;

// ==========================================================
// BUILD WHERE
// ==========================================================

$where = [];

$params = [];

$types = '';

$where[] = "k.deleted_at IS NULL";

// ==========================================================
// SEARCH
// ==========================================================

if ($keyword !== '') {

    $where[] = "

    (

        k.nama_kategori LIKE ?

        OR k.slug LIKE ?

        OR k.deskripsi LIKE ?

    )

    ";

    $search = "%{$keyword}%";

    $params[] = $search;

    $params[] = $search;

    $params[] = $search;

    $types .= "sss";
}

// ==========================================================
// FILTER STATUS
// ==========================================================

if ($status !== '') {

    $where[] = "k.status = ?";

    $params[] = $status;

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
// SUMMARY
// ==========================================================

$sql = "

SELECT

    COUNT(*) AS total_kategori,

    SUM(

        CASE

            WHEN status='aktif'

            THEN 1

            ELSE 0

        END

    ) AS total_aktif,

    SUM(

        CASE

            WHEN status='nonaktif'

            THEN 1

            ELSE 0

        END

    ) AS total_nonaktif

FROM kategori

WHERE deleted_at IS NULL

";

$result = mysqli_query(

    $db,

    $sql

);

if ($result) {

    $summary = mysqli_fetch_assoc($result);

    $total_kategori =

        (int) $summary['total_kategori'];

    $total_aktif =

        (int) $summary['total_aktif'];

    $total_nonaktif =

        (int) $summary['total_nonaktif'];
}

// ==========================================================
// TOTAL PRODUK
// ==========================================================

$sql = "

SELECT

    COUNT(*) AS total

FROM produk

WHERE deleted_at IS NULL

";

$result = mysqli_query(

    $db,

    $sql

);

if ($result) {

    $row = mysqli_fetch_assoc($result);

    $total_produk =

        (int) $row['total'];
}

// ==========================================================
// TOTAL DATA
// ==========================================================

$sql = "

SELECT

    COUNT(*) AS total

FROM kategori k

WHERE

{$where_sql}

";

$stmt = mysqli_prepare(

    $db,

    $sql

);

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

        $total_rows =

            (int) mysqli_fetch_assoc(

                $result

            )['total'];
    }

    mysqli_stmt_close($stmt);
}

// ==========================================================
// TOTAL PAGE
// ==========================================================

$total_pages = max(

    1,

    (int) ceil(

        $total_rows / $limit

    )

);

// ==========================================================
// CATEGORY QUERY
// ==========================================================

$sql = "

SELECT

    k.id_kategori,

    k.kode_kategori,

    k.nama_kategori,

    k.slug,

    k.warna,

    k.deskripsi,

    k.icon,

    k.urutan,

    k.status,

    k.created_at,

    k.updated_at,

    COUNT(p.id_produk) AS jumlah_produk

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

    k.deskripsi,

    k.icon,

    k.urutan,

    k.status,

    k.created_at,

    k.updated_at

ORDER BY

    k.urutan ASC,

    k.nama_kategori ASC

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

    // ======================================================
    // FETCH DATA
    // ======================================================

    if ($result) {

        while ($row = mysqli_fetch_assoc($result)) {

            // ------------------------------------------
            // STATUS BADGE
            // ------------------------------------------

            $badge = match ($row['status']) {

                'aktif' => 'success',

                'nonaktif' => 'danger',

                default => 'secondary'
            };

            // ------------------------------------------
            // CATEGORY ARRAY
            // ------------------------------------------

            $categories[] = [

                'id_kategori' => (int) $row['id_kategori'],

                'kode_kategori' => $row['kode_kategori'],

                'nama_kategori' => $row['nama_kategori'],

                'slug' => $row['slug'],

                'warna' => $row['warna'],

                'deskripsi'       => $row['deskripsi'],

                'icon'            => $row['icon'],

                'urutan'          => (int) $row['urutan'],

                'status'          => $row['status'],

                'badge'           => $badge,

                'jumlah_produk'   => (int) $row['jumlah_produk'],

                'created_at'      => $row['created_at'],

                'updated_at'      => $row['updated_at']

            ];
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

    'previous_page' => max(

        1,

        $page - 1

    ),

    'next_page' => min(

        $total_pages,

        $page + 1

    )

];

// ==========================================================
// SUMMARY ARRAY
// ==========================================================

$summary = [

    'total_kategori' => $total_kategori,

    'kategori_aktif' => $total_aktif,

    'kategori_nonaktif' => $total_nonaktif,

    'total_produk' => $total_produk

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
// STATUS OPTION
// ==========================================================

$status_options = [

    'aktif' => 'Aktif',

    'nonaktif' => 'Nonaktif'

];

// ==========================================================
// CATEGORY INFORMATION
// ==========================================================

$category_information = [

    'total_category' => count($categories),

    'empty_result' => empty($categories),

    'has_data' => !empty($categories)

];

// ==========================================================
// END OF FILE
// ==========================================================