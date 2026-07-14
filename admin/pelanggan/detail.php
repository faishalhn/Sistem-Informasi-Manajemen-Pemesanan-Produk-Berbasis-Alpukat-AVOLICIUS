<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : detail.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Detail Pelanggan
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';
require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// DEFAULT
// ==========================================================

$id_pelanggan = (int) ($_GET['id'] ?? 0);

if ($id_pelanggan <= 0) {

    $_SESSION['error'] = 'Pelanggan tidak ditemukan.';

    redirect('admin/pelanggan/');
}

// ==========================================================
// LOAD DATA PELANGGAN
// ==========================================================

$sql = "

SELECT

    p.*,

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

    p.id_pelanggan = ?

    AND p.deleted_at IS NULL

GROUP BY

    p.id_pelanggan

LIMIT 1

";

$stmt = mysqli_prepare(

    $db,

    $sql

);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $id_pelanggan

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (

    !$result ||

    mysqli_num_rows($result) === 0

) {

    $_SESSION['error'] = 'Data pelanggan tidak ditemukan.';

    redirect('admin/pelanggan/');
}

$customer = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// ==========================================================
// PAGE
// ==========================================================

$page_title = 'Detail Pelanggan';

$active_menu = 'pelanggan';

require_once '../../includes/header.php';

?>

<div class="container-fluid p-0">

    <div class="row g-0">

        <div class="col-auto">

            <?php require_once '../../includes/sidebar.php'; ?>

        </div>

        <main class="col">

            <?php require_once '../../includes/navbar.php'; ?>

            <div class="container-fluid py-4">

                <?php require_once '../../includes/alert.php'; ?>

                <?php

                $badgeStatus =

                    $customer['status'] === 'aktif'

                    ? 'success'

                    : 'secondary';

                ?>

                <!-- ==========================================
                     PAGE HEADER
                =========================================== -->

                <div class="page-header mb-4">

                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-4">

                        <div>

                            <h2 class="fw-bold mb-2">

                                Detail Pelanggan

                            </h2>

                            <p class="text-muted mb-3">

                                Informasi lengkap pelanggan AVOLICIUS.

                            </p>

                            <div class="d-flex flex-wrap gap-2">

                                <span class="badge bg-dark px-3 py-2">

                                    <?= e($customer['kode_customer']); ?>

                                </span>

                                <span class="badge bg-<?= $badgeStatus; ?> px-3 py-2">

                                    <?= ucfirst($customer['status']); ?>

                                </span>

                            </div>

                        </div>

                        <div class="text-end">

                            <div class="text-muted small">

                                Total Belanja

                            </div>

                            <div class="fs-3 fw-bold text-success mb-3">

                                <?= rupiah((float)$customer['total_belanja']); ?>

                            </div>

                            <a

                                href="index.php"

                                class="btn btn-outline-secondary">

                                <i class="bi bi-arrow-left me-2"></i>

                                Kembali

                            </a>

                        </div>

                    </div>

                </div>
                <div class="row g-4 mb-4">

                    <div class="col-lg-4">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-body text-center">

                                <?php

                                $foto = !empty($customer['foto'])

                                    ? '../../assets/uploads/pelanggan/' . $customer['foto']

                                    : '../../assets/images/default-user.png';

                                ?>

                                <img

                                    src="<?= $foto; ?>"

                                    class="rounded-circle border mb-3"

                                    width="140"

                                    height="140"

                                    style="object-fit:cover;">

                                <h4 class="fw-bold">

                                    <?= e($customer['nama']); ?>

                                </h4>

                                <div class="text-muted">

                                    <?= e($customer['email']); ?>

                                </div>

                                <div class="mt-3">

                                    <span class="badge bg-<?= $badgeStatus; ?>">

                                        <?= ucfirst($customer['status']); ?>

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-8">

                        <div class="card shadow-sm border-0">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    Informasi Pelanggan

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row g-4">

                                    <div class="col-md-6">

                                        <strong>Telepon</strong>

                                        <div>

                                            <?= e((string) ($customer['telepon'] ?? '-')); ?>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <strong>Kota</strong>

                                        <div>

                                            <?= e((string) ($customer['kota'] ?? '-')); ?>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <strong>Provinsi</strong>

                                        <div>

                                            <?= e((string) ($customer['provinsi'] ?? '-')); ?>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <strong>Kode Pos</strong>

                                        <div>

                                            <?= e((string) ($customer['kode_pos'] ?? '-')); ?>

                                        </div>

                                    </div>

                                    <div class="col-12">

                                        <strong>Alamat</strong>

                                        <div>

                                            <?= nl2br(e((string) ($customer['alamat'] ?? '-'))); ?>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- ==========================================
     STATISTIK
========================================== -->

                <div class="row g-4 mb-4">

                    <div class="col-lg-3">

                        <div class="dashboard-card h-100">

                            <div class="card-body">

                                <small class="text-muted">

                                    Total Pesanan

                                </small>

                                <h3 class="mt-2 mb-0 text-primary">

                                    <?= number_format($customer['total_pesanan']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3">

                        <div class="dashboard-card h-100">

                            <div class="card-body">

                                <small class="text-muted">

                                    Total Belanja

                                </small>

                                <h5 class="mt-2 mb-0 text-success">

                                    <?= rupiah((float)$customer['total_belanja']); ?>

                                </h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3">

                        <div class="dashboard-card h-100">

                            <div class="card-body">

                                <small class="text-muted">

                                    Member Sejak

                                </small>

                                <div class="fw-semibold mt-2">

                                    <?= tanggal_indonesia($customer['created_at']); ?>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-3">

                        <div class="dashboard-card h-100">

                            <div class="card-body">

                                <small class="text-muted">

                                    Last Login

                                </small>

                                <div class="fw-semibold mt-2">

                                    <?= !empty($customer['last_login'])

                                        ? tanggal_indonesia($customer['last_login'])

                                        : '-'; ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <?php

                // ==========================================================
                // RIWAYAT PESANAN
                // ==========================================================

                $sql = "

                SELECT

                    p.id_pesanan,

                    p.invoice,

                    p.total,

                    p.status,

                    p.created_at,

                    COALESCE(

                        pb.status,

                        'belum_bayar'

                    ) AS status_pembayaran

                FROM pesanan p

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

                    p.id_pelanggan = ?

                    AND p.deleted_at IS NULL

                ORDER BY

                    p.created_at DESC

                ";

                $stmt = mysqli_prepare(

                    $db,

                    $sql

                );

                mysqli_stmt_bind_param(

                    $stmt,

                    "i",

                    $id_pelanggan

                );

                mysqli_stmt_execute($stmt);

                $orderResult = mysqli_stmt_get_result($stmt);

                ?>
                <!-- ==========================================
     RIWAYAT PESANAN
========================================== -->

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Riwayat Pesanan

                        </h5>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th width="70">

                                        No

                                    </th>

                                    <th>

                                        Invoice

                                    </th>

                                    <th width="170">

                                        Total

                                    </th>

                                    <th width="170">

                                        Status

                                    </th>

                                    <th width="170">

                                        Pembayaran

                                    </th>

                                    <th width="170">

                                        Tanggal

                                    </th>

                                    <th width="90">

                                        Aksi

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $no = 1;

                                while ($order = mysqli_fetch_assoc($orderResult)) :

                                ?>

                                    <?php

                                    $badgeStatus = match ($order['status']) {

                                        'menunggu_pembayaran' => 'warning',

                                        'diproses' => 'info',

                                        'dikirim' => 'primary',

                                        'selesai' => 'success',

                                        'dibatalkan' => 'danger',

                                        default => 'secondary'
                                    };

                                    $badgeBayar = match ($order['status_pembayaran']) {

                                        'belum_bayar' => 'secondary',

                                        'menunggu_verifikasi' => 'warning',

                                        'lunas' => 'success',

                                        'ditolak' => 'danger',

                                        default => 'secondary'
                                    };

                                    ?>

                                    <tr>

                                        <td>

                                            <?= $no++; ?>

                                        </td>

                                        <td>

                                            <strong>

                                                <?= e($order['invoice']); ?>

                                            </strong>

                                        </td>

                                        <td>

                                            <?= rupiah((float)$order['total']); ?>

                                        </td>

                                        <td>

                                            <span class="badge bg-<?= $badgeStatus; ?>">

                                                <?= ucwords(str_replace('_', ' ', $order['status'])); ?>

                                            </span>

                                        </td>

                                        <td>

                                            <span class="badge bg-<?= $badgeBayar; ?>">

                                                <?= ucwords(str_replace('_', ' ', $order['status_pembayaran'])); ?>

                                            </span>

                                        </td>

                                        <td>

                                            <?= tanggal_indonesia($order['created_at']); ?>

                                        </td>

                                        <td>

                                            <a

                                                href="../pesanan/detail.php?id=<?= $order['id_pesanan']; ?>"

                                                class="btn btn-outline-primary btn-sm">

                                                <i class="bi bi-eye"></i>

                                            </a>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

                <?php mysqli_stmt_close($stmt); ?>
                <!-- ==========================================
     ACTION
========================================== -->

                <div class="d-flex justify-content-end gap-2 mb-4">

                    <a

                        href="index.php"

                        class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-left me-2"></i>

                        Kembali

                    </a>

                    <a

                        href="export.php?id=<?= $id_pelanggan; ?>"

                        class="btn btn-outline-success">

                        <i class="bi bi-download me-2"></i>

                        Export

                    </a>

                </div>

            </div>
            <?php require_once '../../includes/footer.php'; ?>

        </main>

    </div>

</div>