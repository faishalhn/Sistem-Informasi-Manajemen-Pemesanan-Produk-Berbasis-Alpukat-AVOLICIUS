<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : dashboard_data.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Dashboard Statistics & Charts
 * VERSION     : 2.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// DEFAULT VALUE
// ==========================================================

$total_produk        = 0;
$total_kategori      = 0;
$total_pelanggan     = 0;
$total_pesanan       = 0;
$total_pendapatan    = 0;

$total_stok          = 0;
$total_produk_aktif  = 0;
$total_produk_habis  = 0;

$monthly_revenue = array_fill(0, 12, 0);

$category_labels = [];
$category_totals = [];

// ==========================================================
// TOTAL PRODUK
// ==========================================================

$sql = "

SELECT COUNT(*) AS total

FROM produk

WHERE deleted_at IS NULL

";

$result = mysqli_query($db, $sql);

if ($result) {

    $total_produk = (int) mysqli_fetch_assoc($result)['total'];
}

// ==========================================================
// TOTAL KATEGORI
// ==========================================================

$sql = "

SELECT COUNT(*) AS total

FROM kategori

WHERE deleted_at IS NULL

";

$result = mysqli_query($db, $sql);

if ($result) {

    $total_kategori = (int) mysqli_fetch_assoc($result)['total'];
}

// ==========================================================
// TOTAL PELANGGAN
// ==========================================================

$sql = "

SELECT COUNT(*) AS total

FROM pelanggan

WHERE deleted_at IS NULL

";

$result = mysqli_query($db, $sql);

if ($result) {

    $total_pelanggan = (int) mysqli_fetch_assoc($result)['total'];
}

// ==========================================================
// TOTAL PESANAN
// ==========================================================

$sql = "

SELECT COUNT(*) AS total

FROM pesanan

WHERE deleted_at IS NULL

";

$result = mysqli_query($db, $sql);

if ($result) {

    $total_pesanan = (int) mysqli_fetch_assoc($result)['total'];
}

// ==========================================================
// TOTAL PENDAPATAN
// ==========================================================

$sql = "

SELECT

IFNULL(SUM(total),0) AS total

FROM pesanan

WHERE deleted_at IS NULL

AND status='selesai'

";

$result = mysqli_query($db, $sql);

if ($result) {

    $total_pendapatan = (float) mysqli_fetch_assoc($result)['total'];
}

// ==========================================================
// TOTAL STOK
// ==========================================================

$sql = "

SELECT

IFNULL(SUM(stok),0) AS total

FROM produk

WHERE deleted_at IS NULL

";

$result = mysqli_query($db, $sql);

if ($result) {

    $total_stok = (int) mysqli_fetch_assoc($result)['total'];
}

// ==========================================================
// PRODUK AKTIF
// ==========================================================

$sql = "

SELECT COUNT(*) AS total

FROM produk

WHERE

deleted_at IS NULL

AND status='aktif'

";

$result = mysqli_query($db, $sql);

if ($result) {

    $total_produk_aktif = (int) mysqli_fetch_assoc($result)['total'];
}

// ==========================================================
// PRODUK HABIS
// ==========================================================

$sql = "

SELECT COUNT(*) AS total

FROM produk

WHERE

deleted_at IS NULL

AND stok=0

";

$result = mysqli_query($db, $sql);

if ($result) {

    $total_produk_habis = (int) mysqli_fetch_assoc($result)['total'];
}

// ==========================================================
// MONTHLY REVENUE
// ==========================================================

$sql = "

SELECT

MONTH(created_at) AS bulan,

SUM(total) AS pendapatan

FROM pesanan

WHERE

deleted_at IS NULL

AND status='selesai'

GROUP BY MONTH(created_at)

ORDER BY MONTH(created_at)

";

$result = mysqli_query($db, $sql);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $index = (int) $row['bulan'] - 1;

        if ($index >= 0 && $index < 12) {

            $monthly_revenue[$index] = (float) $row['pendapatan'];
        }
    }
}

// ==========================================================
// CATEGORY DISTRIBUTION
// ==========================================================

$sql = "

SELECT

k.nama_kategori,

COUNT(p.id_produk) AS jumlah

FROM kategori k

LEFT JOIN produk p

ON p.id_kategori = k.id_kategori

AND p.deleted_at IS NULL

WHERE

k.deleted_at IS NULL

GROUP BY

k.id_kategori,

k.nama_kategori

ORDER BY

k.urutan ASC,

k.nama_kategori ASC

";

$result = mysqli_query($db, $sql);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $category_labels[] = $row['nama_kategori'];

        $category_totals[] = (int) $row['jumlah'];
    }
}

// ==========================================================
// CHART JSON
// ==========================================================

$monthly_revenue_json = json_encode($monthly_revenue);

$category_labels_json = json_encode($category_labels);

$category_totals_json = json_encode($category_totals);
