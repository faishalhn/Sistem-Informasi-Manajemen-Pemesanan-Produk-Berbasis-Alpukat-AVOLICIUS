<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : pelanggan_data.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Customer Data
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

$kota = trim($_GET['kota'] ?? '');

$page = max(

    1,

    (int) ($_GET['page'] ?? 1)

);

$limit = 10;

$offset = ($page - 1) * $limit;

// ==========================================================
// SUMMARY
// ==========================================================

$total_pelanggan = 0;

$total_aktif = 0;

$total_baru = 0;

$total_belanja = 0;
// ==========================================================
// SUMMARY QUERY
// ==========================================================

$sql = "

SELECT

    COUNT(*) AS total_pelanggan,

    SUM(

        CASE

            WHEN status='aktif'

            THEN 1

            ELSE 0

        END

    ) AS total_aktif,

    SUM(

        CASE

            WHEN MONTH(created_at)=MONTH(CURRENT_DATE())

            AND YEAR(created_at)=YEAR(CURRENT_DATE())

            THEN 1

            ELSE 0

        END

    ) AS total_baru,

    COALESCE(

        (

            SELECT

                SUM(total)

            FROM pesanan

            WHERE

                deleted_at IS NULL

        ),

        0

    ) AS total_belanja

FROM pelanggan

WHERE

deleted_at IS NULL

";

$result = mysqli_query(

    $db,

    $sql

);

if ($result) {

    $summaryData = mysqli_fetch_assoc($result);

    $total_pelanggan =

        (int) ($summaryData['total_pelanggan'] ?? 0);

    $total_aktif =

        (int) ($summaryData['total_aktif'] ?? 0);

    $total_baru =

        (int) ($summaryData['total_baru'] ?? 0);

    $total_belanja =

        (float) ($summaryData['total_belanja'] ?? 0);
}
// ==========================================================
// SUMMARY ARRAY
// ==========================================================

$summary = [

    'total_pelanggan' => $total_pelanggan,

    'aktif' => $total_aktif,

    'baru' => $total_baru,

    'total_belanja' => $total_belanja

];

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

    $where[] = "p.status=?";

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
// TOTAL DATA
// ==========================================================

$total_rows = 0;

$sql = "

SELECT

    COUNT(*) AS total

FROM pelanggan p

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

        $total_rows = (int) mysqli_fetch_assoc($result)['total'];
    }

    mysqli_stmt_close($stmt);
}

// ==========================================================
// PAGINATION
// ==========================================================

$total_pages = max(

    1,

    (int) ceil($total_rows / $limit)

);

$page = min(

    $page,

    $total_pages

);

$offset = ($page - 1) * $limit;

$pagination = [

    'current_page' => $page,

    'total_pages'  => $total_pages,

    'total_rows'   => $total_rows,

    'limit'        => $limit,

    'offset'       => $offset

];

// ==========================================================
// QUERY DATA
// ==========================================================

$sql = "

SELECT

    p.id_pelanggan,

    p.kode_customer,

    p.nama,

    p.email,

    p.telepon,

    p.kota,

    p.foto,

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

    p.created_at DESC

LIMIT ?, ?

";
// ==========================================================
// PARAMETER
// ==========================================================

$query_types = $types;

$query_params = $params;

$query_types .= "ii";

$query_params[] = $offset;

$query_params[] = $limit;

// ==========================================================
// EXECUTE
// ==========================================================

$customers = [];

$stmt = mysqli_prepare(
    $db,
    $sql
);

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

            $customers[] = [

                'id_pelanggan' => (int) $row['id_pelanggan'],

                'kode_customer' => $row['kode_customer'],

                'nama' => $row['nama'],

                'email' => $row['email'],

                'telepon' => $row['telepon'],

                'kota' => $row['kota'],

                'foto' => $row['foto'],

                'status' => $row['status'],

                'created_at' => $row['created_at'],

                'total_pesanan' => (int) $row['total_pesanan'],

                'total_belanja' => (float) $row['total_belanja']

            ];
        }

        mysqli_free_result($result);
    }

    mysqli_stmt_close($stmt);
}

// ==========================================================
// DEFAULT
// ==========================================================

$customers ??= [];

$summary ??= [

    'total_pelanggan' => 0,

    'aktif' => 0,

    'baru' => 0,

    'total_belanja' => 0

];

$pagination ??= [

    'current_page' => 1,

    'total_pages' => 1,

    'total_rows' => 0,

    'limit' => $limit,

    'offset' => 0

];
