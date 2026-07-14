<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : index.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Order Management
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';

require_once 'pesanan_data.php';

/**
 * ==========================================================
 * VARIABLE FROM pesanan_data.php
 * ==========================================================
 */

/** @var array<int,array<string,mixed>> $orders */

/** @var array<string,mixed> $summary */

/** @var array<string,mixed> $pagination */

/** @var array<string,string> $status_options */

/** @var array<string,string> $status_bayar_options */

/** @var string $keyword */

/** @var string $status */

/** @var string $status_bayar */

/** @var string $tanggal_awal */

/** @var string $tanggal_akhir */

$page_title = 'Manajemen Pesanan';

$active_menu = 'pesanan';

require_once '../../includes/header.php';

?>

<div class="container-fluid p-0">

    <div class="row g-0">

        <!-- ==========================================================
             SIDEBAR
        =========================================================== -->

        <div class="col-auto">

            <?php require_once '../../includes/sidebar.php'; ?>

        </div>

        <!-- ==========================================================
             MAIN CONTENT
        =========================================================== -->

        <main class="col">

            <?php require_once '../../includes/navbar.php'; ?>

            <div class="container-fluid ">

                <?php require_once '../../includes/alert.php'; ?>

                <!-- ==========================================
                     PAGE HEADER
                =========================================== -->

                <div class="page-header mb-4 py-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>

                            <h2 class="fw-bold mb-2">

                                Manajemen Pesanan

                            </h2>

                            <p class="text-muted mb-0">

                                Kelola seluruh transaksi pelanggan AVOLICIUS.

                            </p>

                        </div>

                    </div>

                </div>
                <!-- ==========================================
     SUMMARY
========================================== -->

                <div class="row g-3 mb-4">

                    <!-- TOTAL PESANAN -->

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Total Pesanan

                                </small>

                                <h3 class="mt-2 mb-0">

                                    <?= number_format($summary['total_pesanan']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <!-- MENUNGGU PEMBAYARAN -->

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Menunggu Pembayaran

                                </small>

                                <h3 class="mt-2 mb-0 text-warning">

                                    <?= number_format($summary['pending']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <!-- DIPROSES -->

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Diproses

                                </small>

                                <h3 class="mt-2 mb-0 text-info">

                                    <?= number_format($summary['diproses']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <!-- SELESAI -->

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Selesai

                                </small>

                                <h3 class="mt-2 mb-0 text-success">

                                    <?= number_format($summary['selesai']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <!-- PENDAPATAN -->

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Total Pendapatan

                                </small>

                                <h5 class="mt-2 mb-0 text-primary">

                                    <?= rupiah($summary['pendapatan']); ?>

                                </h5>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- ==========================================
     FILTER
========================================== -->

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <h5 class="mb-0">

                                Filter Pesanan

                            </h5>

                            <a
                                href="export.php?<?= http_build_query($_GET); ?>"
                                class="btn btn-outline-success">

                                <i class="bi bi-download me-2"></i>

                                Export CSV

                            </a>

                        </div>

                    </div>

                    <div class="card-body">

                        <form method="GET">

                            <div class="row g-3 align-items-end">

                                <!-- PENCARIAN -->

                                <div class="col-lg-3 col-md-6">

                                    <label class="form-label">

                                        Pencarian

                                    </label>

                                    <input
                                        type="text"
                                        name="keyword"
                                        class="form-control"
                                        placeholder="Invoice, Nama, Email..."
                                        value="<?= e($keyword); ?>">

                                </div>

                                <!-- STATUS -->

                                <div class="col-lg-2 col-md-6">

                                    <label class="form-label">

                                        Status Pesanan

                                    </label>

                                    <select
                                        name="status"
                                        class="form-select">

                                        <option value="">

                                            Semua

                                        </option>

                                        <?php foreach ($status_options as $key => $label) : ?>

                                            <option
                                                value="<?= e($key); ?>"
                                                <?= $status === $key ? 'selected' : ''; ?>>

                                                <?= e($label); ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                                <!-- PEMBAYARAN -->

                                <div class="col-lg-2 col-md-6">

                                    <label class="form-label">

                                        Pembayaran

                                    </label>

                                    <select
                                        name="status_bayar"
                                        class="form-select">

                                        <option value="">

                                            Semua

                                        </option>

                                        <?php foreach ($status_bayar_options as $key => $label) : ?>

                                            <option
                                                value="<?= e($key); ?>"
                                                <?= $status_bayar === $key ? 'selected' : ''; ?>>

                                                <?= e($label); ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                                <!-- DARI -->

                                <div class="col-lg-2 col-md-6">

                                    <label class="form-label">

                                        Dari

                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_awal"
                                        class="form-control"
                                        value="<?= e($tanggal_awal); ?>">

                                </div>

                                <!-- SAMPAI -->

                                <div class="col-lg-2 col-md-6">

                                    <label class="form-label">

                                        Sampai

                                    </label>

                                    <input
                                        type="date"
                                        name="tanggal_akhir"
                                        class="form-control"
                                        value="<?= e($tanggal_akhir); ?>">

                                </div>

                                <!-- BUTTON -->

                                <div class="col-lg-1 col-md-12">

                                    <div class="d-flex gap-2">

                                        <button
                                            type="submit"
                                            class="btn btn-success flex-fill">

                                            <i class="bi bi-search"></i>

                                        </button>

                                        <a
                                            href="index.php"
                                            class="btn btn-outline-secondary">

                                            <i class="bi bi-arrow-clockwise"></i>

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>
                <!-- ==========================================
     DATA TABLE
========================================== -->

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <h5 class="mb-0 fw-semibold">

                                Daftar Pesanan

                            </h5>

                            <small class="text-muted">

                                Total Data :

                                <strong>

                                    <?= number_format($pagination['total_rows']); ?>

                                </strong>

                            </small>

                        </div>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th width="60">

                                        No

                                    </th>

                                    <th width="170">

                                        Invoice

                                    </th>

                                    <th>

                                        Pelanggan

                                    </th>

                                    <th width="170">

                                        Total

                                    </th>

                                    <th width="170">

                                        Status Pesanan

                                    </th>

                                    <th width="180">

                                        Status Pembayaran

                                    </th>

                                    <th width="170">

                                        Tanggal

                                    </th>

                                    <th width="110" class="text-center">

                                        Aksi

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php if (empty($orders)) : ?>

                                    <tr>

                                        <td colspan="8" class="text-center py-5 text-muted">

                                            <i class="bi bi-inbox fs-2 d-block mb-3"></i>

                                            Belum ada data pesanan.

                                        </td>

                                    </tr>

                                <?php else : ?>

                                    <?php

                                    $no = $pagination['offset'] + 1;

                                    ?>

                                    <?php foreach ($orders as $order) : ?>

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

                                                <div class="fw-semibold">

                                                    <?= e($order['nama']); ?>

                                                </div>

                                                <small class="text-muted">

                                                    <?= e($order['email']); ?>

                                                </small>

                                            </td>

                                            <td>

                                                <strong class="text-success">

                                                    <?= rupiah($order['total']); ?>

                                                </strong>

                                            </td>

                                            <td>

                                                <span class="badge bg-<?= e($order['badge_status']); ?>">

                                                    <?= ucwords(str_replace('_', ' ', $order['status'])); ?>

                                                </span>

                                            </td>

                                            <td>

                                                <span class="badge bg-<?= e($order['badge_pembayaran']); ?>">

                                                    <?= ucwords(str_replace('_', ' ', $order['status_pembayaran'])); ?>

                                                </span>

                                            </td>

                                            <td>

                                                <?= tanggal_indonesia($order['created_at']); ?>

                                            </td>

                                            <td class="text-center">

                                                <a

                                                    href="detail.php?id=<?= $order['id_pesanan']; ?>"

                                                    class="btn btn-outline-primary btn-sm">

                                                    <i class="bi bi-eye"></i>

                                                </a>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>
                <!-- ==========================================
     PAGINATION
========================================== -->

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">

                    <div class="text-muted small">

                        Menampilkan

                        <strong>

                            <?= $pagination['total_rows'] > 0 ? $pagination['offset'] + 1 : 0; ?>

                        </strong>

                        -

                        <strong>

                            <?= min(

                                $pagination['offset'] + $pagination['limit'],

                                $pagination['total_rows']

                            ); ?>

                        </strong>

                        dari

                        <strong>

                            <?= number_format($pagination['total_rows']); ?>

                        </strong>

                        data.

                    </div>

                    <?php if ($pagination['total_pages'] > 1) : ?>

                        <nav>

                            <ul class="pagination pagination-sm mb-0">

                                <!-- PREVIOUS -->

                                <li class="page-item <?= !$pagination['has_previous'] ? 'disabled' : ''; ?>">

                                    <a

                                        class="page-link"

                                        href="?<?= http_build_query(array_merge($_GET, [

                                                    'page' => $pagination['previous_page']

                                                ])); ?>">

                                        <i class="bi bi-chevron-left"></i>

                                    </a>

                                </li>

                                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++) : ?>

                                    <li class="page-item <?= $pagination['page'] == $i ? 'active' : ''; ?>">

                                        <a

                                            class="page-link"

                                            href="?<?= http_build_query(array_merge($_GET, [

                                                        'page' => $i

                                                    ])); ?>">

                                            <?= $i; ?>

                                        </a>

                                    </li>

                                <?php endfor; ?>

                                <!-- NEXT -->

                                <li class="page-item <?= !$pagination['has_next'] ? 'disabled' : ''; ?>">

                                    <a

                                        class="page-link"

                                        href="?<?= http_build_query(array_merge($_GET, [

                                                    'page' => $pagination['next_page']

                                                ])); ?>">

                                        <i class="bi bi-chevron-right"></i>

                                    </a>

                                </li>

                            </ul>

                        </nav>

                    <?php endif; ?>

                </div>

            </div>
            <?php require_once '../../includes/footer.php'; ?>

        </main>

    </div>

</div>