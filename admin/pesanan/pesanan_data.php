<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : pesanan_data.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Order Data
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

$status_bayar = trim($_GET['status_bayar'] ?? '');

$tanggal_awal = trim($_GET['tanggal_awal'] ?? '');

$tanggal_akhir = trim($_GET['tanggal_akhir'] ?? '');

// ==========================================================
// PAGINATION
// ==========================================================

$page = max(1, (int) ($_GET['page'] ?? 1));

$limit = 10;

$offset = ($page - 1) * $limit;

// ==========================================================
// DEFAULT
// ==========================================================

$orders = [];

$total_rows = 0;

$total_pages = 1;

// ==========================================================
// SUMMARY
// ==========================================================

$total_pesanan = 0;

$total_pending = 0;

$total_diproses = 0;

$total_selesai = 0;

$total_pendapatan = 0;

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

        OR pl.telepon LIKE ?

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
// FILTER STATUS PESANAN
// ==========================================================

if ($status !== '') {

    $where[] = "p.status = ?";

    $params[] = $status;

    $types .= "s";
}

// ==========================================================
// FILTER STATUS PEMBAYARAN
// ==========================================================

if ($status_bayar !== '') {

    $where[] = "COALESCE(pb.status, 'belum_bayar') = ?";

    $params[] = $status_bayar;

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
// SUMMARY
// ==========================================================

$sql = "

SELECT

    COUNT(*) AS total_pesanan,

    SUM(

        CASE

            WHEN status = 'menunggu_pembayaran'

            THEN 1

            ELSE 0

        END

    ) AS total_pending,

    SUM(

        CASE

            WHEN status = 'diproses'

            THEN 1

            ELSE 0

        END

    ) AS total_diproses,

    SUM(

        CASE

            WHEN status = 'selesai'

            THEN 1

            ELSE 0

        END

    ) AS total_selesai,

    SUM(

        CASE

            WHEN status = 'selesai'

            THEN total

            ELSE 0

        END

    ) AS total_pendapatan

FROM pesanan

WHERE deleted_at IS NULL

";

$result = mysqli_query($db, $sql);

if ($result) {

    $summary = mysqli_fetch_assoc($result);

    $total_pesanan = (int) ($summary['total_pesanan'] ?? 0);

    $total_pending = (int) ($summary['total_pending'] ?? 0);

    $total_diproses = (int) ($summary['total_diproses'] ?? 0);

    $total_selesai = (int) ($summary['total_selesai'] ?? 0);

    $total_pendapatan = (float) ($summary['total_pendapatan'] ?? 0);
}
// ==========================================================
// TOTAL DATA
// ==========================================================

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

    (int) ceil(

        $total_rows / $limit

    )

);

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

    pl.telepon,

    COALESCE(

        pb.status,

        'belum_bayar'

    ) AS status_pembayaran

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

ORDER BY

    p.created_at DESC

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
// EXECUTE QUERY
// ==========================================================

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
            // ARRAY DATA
            // --------------------------------------------------

            $orders[] = [

                'id_pesanan' => (int) $row['id_pesanan'],

                'invoice' => $row['invoice'],

                'nama' => $row['nama'],

                'email' => $row['email'],

                'telepon' => $row['telepon'],

                'total' => (float) $row['total'],

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
// SUMMARY ARRAY
// ==========================================================

$summary = [

    'total_pesanan' => $total_pesanan,

    'pending' => $total_pending,

    'diproses' => $total_diproses,

    'selesai' => $total_selesai,

    'pendapatan' => $total_pendapatan

];

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
// STATUS OPTION
// ==========================================================

$status_options = [

    'menunggu_pembayaran' => 'Menunggu Pembayaran',

    'diproses' => 'Diproses',

    'dikirim' => 'Dikirim',

    'selesai' => 'Selesai',

    'dibatalkan' => 'Dibatalkan'

];

// ==========================================================
// STATUS PEMBAYARAN
// ==========================================================

$status_bayar_options = [

    'belum_bayar' => 'Belum Bayar',

    'menunggu_verifikasi' => 'Menunggu Verifikasi',

    'lunas' => 'Lunas',

    'ditolak' => 'Ditolak'

];

// ==========================================================
// END OF FILE
// ==========================================================