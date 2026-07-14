<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : index.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Dashboard Administrator
 * VERSION     : 2.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';

require_once 'dashboard_data.php';

/** @var int $total_produk */
/** @var int $total_kategori */
/** @var int $total_pelanggan */
/** @var int $total_pesanan */
/** @var float $total_pendapatan */

/** @var array<int,float> $monthly_revenue */
/** @var array<int,string> $category_labels */
/** @var array<int,int> $category_totals */

/** @var string $monthly_revenue_json */
/** @var string $category_labels_json */
/** @var string $category_totals_json */

require_once 'recent_orders.php';

$page_title = 'Dashboard';

$active_menu = 'dashboard';
$dashboard_page = true;
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

            <!-- ======================================================
                 TOP NAVBAR
            ======================================================= -->

            <?php require '../../includes/navbar.php'; ?>

            <!-- ======================================================
                 DASHBOARD CONTENT
            ======================================================= -->

            <div class="container-fluid py-4">

                <?php require_once '../../includes/alert.php'; ?>

                <!-- HEADER -->

                <div class="dashboard-header mb-4">

                    <h2 class="fw-bold mb-2">

                        Dashboard

                    </h2>

                    <p class="text-muted mb-0">

                        Selamat datang kembali,

                        <strong>

                            <?= e($_SESSION[SESSION_ADMIN]['nama']); ?>

                        </strong>

                        👋

                    </p>

                </div>

                <!-- ==================================================
                     SUMMARY CARD
                =================================================== -->

                <div class="row g-4">

                    <!-- TOTAL PRODUK -->

                    <div class="col-xl-3 col-md-6">

                        <div class="card dashboard-card chart-card  border-0 shadow-sm">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <small class="text-muted">

                                            Total Produk

                                        </small>

                                        <h2 class="fw-bold mt-2 mb-1">

                                            <?= $total_produk; ?>

                                        </h2>

                                        <small class="text-success">

                                            Data Produk

                                        </small>

                                    </div>

                                    <div class="dashboard-icon bg-success-subtle">

                                        <i class="bi bi-box-seam text-success"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- TOTAL PESANAN -->

                    <div class="col-xl-3 col-md-6">

                        <div class="card dashboard-card chart-card  border-0 shadow-sm">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <small class="text-muted">

                                            Total Pesanan

                                        </small>

                                        <h2 class="fw-bold mt-2 mb-1">

                                            <?= $total_pesanan; ?>

                                        </h2>

                                        <small class="text-warning">

                                            Semua Pesanan

                                        </small>

                                    </div>

                                    <div class="dashboard-icon bg-warning-subtle">

                                        <i class="bi bi-cart-check text-warning"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                    <!-- TOTAL PELANGGAN -->

                    <div class="col-xl-3 col-md-6">

                        <div class="card dashboard-card chart-card  border-0 shadow-sm">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <small class="text-muted">

                                            Total Pelanggan

                                        </small>

                                        <h2 class="fw-bold mt-2 mb-1">

                                            <?= $total_pelanggan; ?>

                                        </h2>

                                        <small class="text-primary">

                                            Customer Aktif

                                        </small>

                                    </div>

                                    <div class="dashboard-icon bg-primary-subtle">

                                        <i class="bi bi-people text-primary"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- TOTAL PENDAPATAN -->

                    <div class="col-xl-3 col-md-6">

                        <div class="card dashboard-card chart-card  border-0 shadow-sm">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>

                                        <small class="text-muted">

                                            Pendapatan

                                        </small>

                                        <h4 class="fw-bold mt-2 mb-1">

                                            <?= rupiah($total_pendapatan); ?>

                                        </h4>

                                        <small class="text-danger">

                                            Revenue

                                        </small>

                                    </div>

                                    <div class="dashboard-icon bg-danger-subtle">

                                        <i class="bi bi-cash-stack text-danger"></i>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ==================================================
                     CHART
                =================================================== -->

                <div class="row mt-4">

                    <!-- MONTHLY REVENUE -->

                    <div class="col-lg-8 mb-4">

                        <div class="card dashboard-card chart-card  border-0 shadow-sm h-100">

                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

                                <div>

                                    <h5 class="mb-1">

                                        Monthly Revenue

                                    </h5>

                                    <small class="text-muted">

                                        Pendapatan Bulanan

                                    </small>

                                </div>

                                <span class="badge bg-success">

                                    <?= date('Y'); ?>

                                </span>

                            </div>

                            <div class="card-body">

                                <canvas
                                    id="revenueChart"
                                    height="120">

                                </canvas>

                            </div>

                        </div>

                    </div>

                    <!-- CATEGORY -->

                    <div class="col-lg-4 mb-4">

                        <div class="card dashboard-card chart-card  border-0 shadow-sm h-100">

                            <div class="card-header bg-white border-0">

                                <h5 class="mb-1">

                                    Category Distribution

                                </h5>

                                <small class="text-muted">

                                    Produk per Kategori

                                </small>

                            </div>

                            <div class="card-body d-flex align-items-center justify-content-center">

                                <canvas
                                    id="categoryChart"
                                    width="250"
                                    height="250">

                                </canvas>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ==================================================
                     RECENT ORDERS
                =================================================== -->

                <div class="card dashboard-card chart-card  border-0 shadow-sm">

                    <div class="card-header bg-white border-0">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h5 class="mb-1">

                                    Recent Orders

                                </h5>

                                <small class="text-muted">

                                    Pesanan terbaru pelanggan

                                </small>

                            </div>

                            <a
                                href="<?= base_url('admin/pesanan/'); ?>"
                                class="btn btn-success btn-sm">

                                <i class="bi bi-eye"></i>

                                Lihat Semua

                            </a>

                        </div>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th width="18%">

                                        Invoice

                                    </th>

                                    <th>

                                        Penerima

                                    </th>

                                    <th width="18%">

                                        Total

                                    </th>

                                    <th width="18%">

                                        Status

                                    </th>

                                    <th width="18%">

                                        Tanggal

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php if (!empty($recent_orders)) : ?>

                                    <?php foreach ($recent_orders as $order) : ?>

                                        <?php

                                        $badge = match (strtolower($order['status'])) {

                                            'selesai' => 'success',

                                            'diproses' => 'warning',

                                            'dikirim' => 'primary',

                                            'dibatalkan' => 'danger',

                                            default => 'secondary'
                                        };

                                        ?>
                                        <tr>

                                            <td>

                                                <strong>

                                                    <?= e($order['invoice']); ?>

                                                </strong>

                                            </td>

                                            <td>

                                                <?= e($order['nama_penerima']); ?>

                                            </td>

                                            <td>

                                                <?= rupiah((float) $order['total']); ?>

                                            </td>

                                            <td>

                                                <span class="badge bg-<?= $badge; ?>">

                                                    <?= e($order['status']); ?>

                                                </span>

                                            </td>

                                            <td>

                                                <?= tanggal_indonesia($order['created_at']); ?>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else : ?>

                                    <tr>

                                        <td
                                            colspan="5"
                                            class="text-center py-5">

                                            <i class="bi bi-cart-x fs-1 text-secondary d-block mb-3"></i>

                                            <h5 class="text-muted">

                                                Belum ada data pesanan.

                                            </h5>

                                            <small class="text-muted">

                                                Data transaksi pelanggan akan tampil di sini.

                                            </small>

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>
                        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">

                            <div class="text-muted small">

                                Menampilkan <strong><?= isset($recent_orders)
                                                        ? count($recent_orders)
                                                        : 0; ?></strong> pesanan terbaru dari seluruh transaksi.


                            </div>

                        </div>
                    </div>

                </div>
                <?php

                require_once '../../includes/footer.php';

                ?>
            </div>

        </main>

    </div>

</div>