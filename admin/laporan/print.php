<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : print.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Print Report
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

$tanggal_awal = trim($_GET['tanggal_awal'] ?? '');

$tanggal_akhir = trim($_GET['tanggal_akhir'] ?? '');

// ==========================================================
// WHERE
// ==========================================================

$where = [];

$params = [];

$types = '';

$where[] = "p.deleted_at IS NULL";

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

if ($status !== '') {

    $where[] = "p.status = ?";

    $params[] = $status;

    $types .= "s";
}

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

$where_sql = implode(

    ' AND ',

    $where

);

// ==========================================================
// QUERY
// ==========================================================

$sql = "

SELECT

    p.invoice,

    p.created_at,

    pl.nama,

    COUNT(pd.id_detail) AS total_item,

    p.total,

    p.status,

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

        MAX(id_pembayaran) id_pembayaran

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

";

$stmt = mysqli_prepare($db, $sql);

if ($types !== '') {

    mysqli_stmt_bind_param(

        $stmt,

        $types,

        ...$params

    );
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>

        Laporan Penjualan

    </title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">

    <style>
        body {

            font-size: 13px;

            color: #222;

        }

        .table th {

            background: #f8f9fa;

        }

        @media print {

            .no-print {

                display: none;

            }

        }
    </style>

</head>

<body>

    <div class="container mt-4">

        <div class="text-center mb-4">

            <h3>

                AVOLICIUS

            </h3>

            <h5>

                LAPORAN PENJUALAN

            </h5>

            <small>

                Dicetak :

                <?= date('d/m/Y H:i'); ?>

            </small>

        </div>

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Invoice</th>

                    <th>Tanggal</th>

                    <th>Pelanggan</th>

                    <th>Item</th>

                    <th>Total</th>

                    <th>Status</th>

                    <th>Pembayaran</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $no = 1;

                $total = 0;

                while ($row = mysqli_fetch_assoc($result)):

                    $total += (float)$row['total'];

                ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td><?= e($row['invoice']); ?></td>

                        <td><?= tanggal_indonesia($row['created_at']); ?></td>

                        <td><?= e($row['nama']); ?></td>

                        <td><?= $row['total_item']; ?></td>

                        <td><?= rupiah((float)$row['total']); ?></td>

                        <td><?= ucwords(str_replace('_', ' ', $row['status'])); ?></td>

                        <td><?= ucwords(str_replace('_', ' ', $row['status_pembayaran'])); ?></td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

            <tfoot>

                <tr>

                    <th colspan="5">

                        Total Pendapatan

                    </th>

                    <th colspan="3">

                        <?= rupiah($total); ?>

                    </th>

                </tr>

            </tfoot>

        </table>

        <div class="row mt-5">

            <div class="col-7"></div>

            <div class="col-5 text-center">

                Kediri,

                <?= date('d F Y'); ?>

                <br><br><br><br>

                _______________________

                <br>

                Administrator

            </div>

        </div>

    </div>

    <script>
        window.print();
    </script>

</body>

</html>

<?php

mysqli_stmt_close($stmt);
