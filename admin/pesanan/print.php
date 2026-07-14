<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : print.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Print Invoice Pesanan
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';
if (
    empty($_SERVER['HTTP_REFERER']) ||
    strpos($_SERVER['HTTP_REFERER'], '/admin/pesanan/') === false
) {

    redirect('admin/pesanan/');
}
require_once '../../config/init.php';

/** @var mysqli $db */

$id_pesanan = (int) ($_GET['id'] ?? 0);

if ($id_pesanan <= 0) {

    exit('Pesanan tidak ditemukan.');
}

// ==========================================================
// LOAD PESANAN
// ==========================================================

$sql = "

SELECT

    p.*,

    pl.nama,

    pl.email,

    pl.telepon,

    pl.alamat,

    COALESCE(pb.status,'belum_bayar') AS status_pembayaran

FROM pesanan p

LEFT JOIN pelanggan pl

ON pl.id_pelanggan = p.id_pelanggan

LEFT JOIN pembayaran pb

ON pb.id_pesanan = p.id_pesanan

WHERE

p.id_pesanan = ?

LIMIT 1

";

$stmt = mysqli_prepare($db, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_pesanan
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {

    exit('Data tidak ditemukan.');
}

$order = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// ==========================================================
// DETAIL PRODUK
// ==========================================================

$sql = "

SELECT

nama_produk,

kode_produk,

harga,

diskon,

qty,

(
(harga-diskon)*qty
) subtotal

FROM pesanan_detail

WHERE id_pesanan=?

ORDER BY id_detail

";

$stmt = mysqli_prepare($db, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_pesanan
);

mysqli_stmt_execute($stmt);

$productResult = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>

        Invoice <?= e($order['invoice']); ?>

    </title>

    <link
        href="../../assets/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {

            font-size: 13px;

            color: #333;

        }

        .invoice-header {

            border-bottom: 3px solid #198754;

            padding-bottom: 20px;

            margin-bottom: 30px;

        }

        .table th {

            background: #f8f9fa;

        }

        @media print {

            .btn-print {

                display: none;

            }

        }
    </style>

</head>

<body>

    <div class="container py-4">

        <div class="text-end mb-4 btn-print">

            <button
                onclick="window.print()"
                class="btn btn-success">

                Cetak

            </button>

        </div>

        <div class="invoice-header">

            <div class="row">

                <div class="col">

                    <h2 class="fw-bold text-success">

                        AVOLICIUS

                    </h2>

                    <div>

                        Invoice Penjualan

                    </div>

                </div>

                <div class="col text-end">

                    <h4>

                        <?= e($order['invoice']); ?>

                    </h4>

                    <div>

                        <?= tanggal_indonesia($order['created_at']); ?>

                    </div>

                </div>

            </div>

        </div>
        <div class="row mb-4">

            <div class="col-md-6">

                <h6 class="fw-bold">

                    Informasi Pelanggan

                </h6>

                <table class="table table-borderless">

                    <tr>

                        <td width="120">

                            Nama

                        </td>

                        <td>

                            <?= e($order['nama']); ?>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Email

                        </td>

                        <td>

                            <?= e($order['email']); ?>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Telepon

                        </td>

                        <td>

                            <?= e($order['telepon']); ?>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Alamat

                        </td>

                        <td>

                            <?= nl2br(e($order['alamat'])); ?>

                        </td>

                    </tr>

                </table>

            </div>

            <div class="col-md-6">

                <h6 class="fw-bold">

                    Status

                </h6>

                <table class="table table-borderless">

                    <tr>

                        <td width="120">

                            Pesanan

                        </td>

                        <td>

                            <?= ucwords(str_replace('_', ' ', $order['status'])); ?>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Pembayaran

                        </td>

                        <td>

                            <?= ucwords(str_replace('_', ' ', $order['status_pembayaran'])); ?>

                        </td>

                    </tr>

                </table>

            </div>

        </div>
        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Produk</th>

                    <th>Harga</th>

                    <th>Qty</th>

                    <th>Subtotal</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $no = 1;

                while ($item = mysqli_fetch_assoc($productResult)):

                ?>

                    <tr>

                        <td>

                            <?= $no++; ?>

                        </td>

                        <td>

                            <strong>

                                <?= e($item['nama_produk']); ?>

                            </strong>

                            <br>

                            <small>

                                <?= e($item['kode_produk']); ?>

                            </small>

                        </td>

                        <td>

                            <?= rupiah((float)$item['harga']); ?>

                        </td>

                        <td>

                            <?= $item['qty']; ?>

                        </td>

                        <td>

                            <?= rupiah((float)$item['subtotal']); ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

        <?php mysqli_stmt_close($stmt); ?>
        <div class="row justify-content-end">

            <div class="col-md-4">

                <table class="table">

                    <tr>

                        <td>

                            Subtotal

                        </td>

                        <td class="text-end">

                            <?= rupiah((float)$order['subtotal']); ?>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Ongkir

                        </td>

                        <td class="text-end">

                            <?= rupiah((float)$order['ongkir']); ?>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Diskon

                        </td>

                        <td class="text-end text-danger">

                            -<?= rupiah((float)$order['diskon']); ?>

                        </td>

                    </tr>

                    <tr class="table-success">

                        <th>

                            Total

                        </th>

                        <th class="text-end">

                            <?= rupiah((float)$order['total']); ?>

                        </th>

                    </tr>

                </table>

            </div>

        </div>

        <div class="mt-5 text-center text-muted">

            Terima kasih telah berbelanja di

            <strong>

                AVOLICIUS

            </strong>

        </div>

    </div>

    <script>
        window.onload = function() {

            window.print();

        };
    </script>

</body>

</html>