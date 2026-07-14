<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : recent_orders.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Recent Orders Dashboard
 * VERSION     : 3.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// PAGINATION
// ==========================================================

$page = isset($_GET['page'])
    ? max(1, (int) $_GET['page'])
    : 1;

$limit = 5;

$offset = ($page - 1) * $limit;

// ==========================================================
// TOTAL DATA
// ==========================================================

$total_rows = 0;

$sql = "

SELECT COUNT(*) AS total

FROM pesanan

WHERE deleted_at IS NULL

";

$result = mysqli_query($db, $sql);

if ($result) {

    $total_rows = (int) mysqli_fetch_assoc($result)['total'];
}

$total_pages = max(1, (int) ceil($total_rows / $limit));

// ==========================================================
// RECENT ORDERS
// ==========================================================

$recent_orders = [];

$sql = "

SELECT

    id_pesanan,

    invoice,

    nama_penerima,

    telepon_penerima,

    total,

    status,

    created_at

FROM pesanan

WHERE deleted_at IS NULL

ORDER BY

    created_at DESC,

    id_pesanan DESC

LIMIT {$offset}, {$limit}

";

$result = mysqli_query($db, $sql);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $recent_orders[] = [

            'id_pesanan'       => (int) $row['id_pesanan'],

            'invoice'          => $row['invoice'],

            'nama_penerima'    => $row['nama_penerima'],

            'telepon_penerima' => $row['telepon_penerima'],

            'total'            => (float) $row['total'],

            'status'           => strtolower($row['status']),

            'created_at'       => $row['created_at']

        ];
    }
}


// ==========================================================
// PAGINATION DATA
// ==========================================================

$current_page = $page;

$per_page = $limit;

$total_data = $total_rows;

$last_page = $total_pages;

$start_data = $offset + 1;

$end_data = min(

    $offset + $limit,

    $total_rows

);
