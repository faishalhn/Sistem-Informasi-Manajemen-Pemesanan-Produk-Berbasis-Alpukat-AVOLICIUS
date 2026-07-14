<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : laporan_data.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Report Data
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

$tanggal_awal = trim($_GET['tanggal_awal'] ?? '');

$tanggal_akhir = trim($_GET['tanggal_akhir'] ?? '');

$page = max(
    1,
    (int) ($_GET['page'] ?? 1)
);

$limit = 10;

$offset = ($page - 1) * $limit;

// ==========================================================
// SUMMARY
// ==========================================================

$total_pendapatan = 0;

$total_pesanan = 0;

$total_produk = 0;

$total_pelanggan = 0;
// ==========================================================
// SUMMARY QUERY
// ==========================================================

$sql = "

SELECT

    COUNT(*) AS total_pesanan,

    COALESCE(

        SUM(total),

        0

    ) AS total_pendapatan

FROM pesanan

WHERE

    deleted_at IS NULL

";

$result = mysqli_query(
    $db,
    $sql
);

if ($result) {

    $summaryData = mysqli_fetch_assoc($result);

    $total_pesanan =

        (int) ($summaryData['total_pesanan'] ?? 0);

    $total_pendapatan =

        (float) ($summaryData['total_pendapatan'] ?? 0);
}
// ==========================================================
// TOTAL PRODUK
// ==========================================================

$sql = "

SELECT

    COUNT(*) AS total

FROM produk

WHERE

    deleted_at IS NULL

";

$result = mysqli_query(
    $db,
    $sql
);

if ($result) {

    $row = mysqli_fetch_assoc($result);

    $total_produk =

        (int) ($row['total'] ?? 0);
}
// ==========================================================
// TOTAL PELANGGAN
// ==========================================================

$sql = "

SELECT

    COUNT(*) AS total

FROM pelanggan

WHERE

    deleted_at IS NULL

";

$result = mysqli_query(
    $db,
    $sql
);

if ($result) {

    $row = mysqli_fetch_assoc($result);

    $total_pelanggan =

        (int) ($row['total'] ?? 0);
}
// ==========================================================
// SUMMARY ARRAY
// ==========================================================

$summary = [

    'pendapatan' => $total_pendapatan,

    'pesanan' => $total_pesanan,

    'produk' => $total_produk,

    'pelanggan' => $total_pelanggan

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

FROM pesanan p

LEFT JOIN pelanggan pl

ON

    pl.id_pelanggan = p.id_pelanggan

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

    'total_pages' => $total_pages,

    'total_rows' => $total_rows,

    'limit' => $limit,

    'offset' => $offset

];

// ==========================================================
// QUERY DATA
// ==========================================================

$sql = "

SELECT

    p.id_pesanan,

    p.invoice,

    p.total,

    p.status,

    p.created_at,

    pl.nama,

    pl.email,

    COUNT(pd.id_detail) AS total_item,

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

$reports = [];

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

            // --------------------------------------------------
            // BADGE STATUS PESANAN
            // --------------------------------------------------

            $badge_status = match ($row['status']) {

                'menunggu_pembayaran' => 'warning',

                'diproses' => 'info',

                'dikirim' => 'primary',

                'selesai' => 'success',

                'dibatalkan' => 'danger',

                default => 'secondary'
            };

            // --------------------------------------------------
            // BADGE STATUS PEMBAYARAN
            // --------------------------------------------------

            $badge_pembayaran = match ($row['status_pembayaran']) {

                'belum_bayar' => 'secondary',

                'menunggu_verifikasi' => 'warning',

                'lunas' => 'success',

                'ditolak' => 'danger',

                default => 'secondary'
            };

            // --------------------------------------------------
            // ARRAY
            // --------------------------------------------------

            $reports[] = [

                'id_pesanan' => (int) $row['id_pesanan'],

                'invoice' => $row['invoice'],

                'nama' => $row['nama'],

                'email' => $row['email'],

                'total' => (float) $row['total'],

                'total_item' => (int) $row['total_item'],

                'status' => $row['status'],

                'status_pembayaran' => $row['status_pembayaran'],

                'badge_status' => $badge_status,

                'badge_pembayaran' => $badge_pembayaran,

                'created_at' => $row['created_at']

            ];
        }

        mysqli_free_result($result);
    }

    mysqli_stmt_close($stmt);
}

// ==========================================================
// DEFAULT
// ==========================================================

$reports ??= [];

$summary ??= [

    'pendapatan' => 0,

    'pesanan' => 0,

    'produk' => 0,

    'pelanggan' => 0

];

$pagination ??= [

    'current_page' => 1,

    'total_pages' => 1,

    'total_rows' => 0,

    'limit' => $limit,

    'offset' => 0

];
