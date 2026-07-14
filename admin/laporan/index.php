<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : index.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Report Management
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';
require_once 'laporan_data.php';

/** @var array $reports */
/** @var array $summary */
/** @var array $pagination */
/** @var string $keyword */
/** @var string $status */
/** @var string $tanggal_awal */
/** @var string $tanggal_akhir */

$page_title = 'Laporan';

$active_menu = 'laporan';

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
                <!-- ==========================================
     PAGE HEADER
========================================== -->

                <div class="page-header mb-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>

                            <h2 class="fw-bold mb-2">

                                Laporan Penjualan

                            </h2>

                            <p class="text-muted mb-0">

                                Rekapitulasi transaksi AVOLICIUS.

                            </p>

                        </div>

                    </div>

                </div>
                <!-- ==========================================
     SUMMARY
========================================== -->

                <div class="row g-3 mb-4">

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Total Pendapatan

                                </small>

                                <h4 class="mt-2 mb-0 text-success">

                                    <?= rupiah($summary['pendapatan']); ?>

                                </h4>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Jumlah Pesanan

                                </small>

                                <h3 class="mt-2 mb-0">

                                    <?= number_format($summary['pesanan']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Produk

                                </small>

                                <h3 class="mt-2 mb-0 text-primary">

                                    <?= number_format($summary['produk']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Pelanggan

                                </small>

                                <h3 class="mt-2 mb-0 text-warning">

                                    <?= number_format($summary['pelanggan']); ?>

                                </h3>

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

                                Filter Laporan

                            </h5>

                            <div class="d-flex gap-2">

                                <a
                                    href="export.php?<?= http_build_query($_GET); ?>"
                                    class="btn btn-outline-success">

                                    <i class="bi bi-download me-2"></i>

                                    Export CSV

                                </a>

                                <a
                                    href="print.php?<?= http_build_query($_GET); ?>"
                                    target="_blank"
                                    class="btn btn-outline-primary">

                                    <i class="bi bi-printer me-2"></i>

                                    Print

                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="card-body">

                        <form method="GET">

                            <div class="row g-3 align-items-end">

                                <div class="col-lg-4">

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

                                <div class="col-lg-2">

                                    <label class="form-label">

                                        Status

                                    </label>

                                    <select

                                        name="status"

                                        class="form-select">

                                        <option value="">

                                            Semua

                                        </option>

                                        <option value="menunggu_pembayaran" <?= $status == 'menunggu_pembayaran' ? 'selected' : ''; ?>>

                                            Menunggu

                                        </option>

                                        <option value="diproses" <?= $status == 'diproses' ? 'selected' : ''; ?>>

                                            Diproses

                                        </option>

                                        <option value="dikirim" <?= $status == 'dikirim' ? 'selected' : ''; ?>>

                                            Dikirim

                                        </option>

                                        <option value="selesai" <?= $status == 'selesai' ? 'selected' : ''; ?>>

                                            Selesai

                                        </option>

                                        <option value="dibatalkan" <?= $status == 'dibatalkan' ? 'selected' : ''; ?>>

                                            Dibatalkan

                                        </option>

                                    </select>

                                </div>

                                <div class="col-lg-2">

                                    <label class="form-label">

                                        Dari

                                    </label>

                                    <input

                                        type="date"

                                        name="tanggal_awal"

                                        class="form-control"

                                        value="<?= e($tanggal_awal); ?>">

                                </div>

                                <div class="col-lg-2">

                                    <label class="form-label">

                                        Sampai

                                    </label>

                                    <input

                                        type="date"

                                        name="tanggal_akhir"

                                        class="form-control"

                                        value="<?= e($tanggal_akhir); ?>">

                                </div>

                                <div class="col-lg-2">

                                    <div class="d-flex gap-2">

                                        <button

                                            class="btn btn-success flex-fill"

                                            type="submit">

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
     TABLE
========================================== -->
                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <h5 class="mb-0">

                                Daftar Laporan

                            </h5>

                            <small class="text-muted">

                                Total :

                                <strong>

                                    <?= number_format($pagination['total_rows']); ?>

                                </strong>

                                transaksi

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

                                    <th>

                                        Invoice

                                    </th>

                                    <th>

                                        Pelanggan

                                    </th>

                                    <th width="110">

                                        Item

                                    </th>

                                    <th width="170">

                                        Total

                                    </th>

                                    <th width="170">

                                        Status Pesanan

                                    </th>

                                    <th width="180">

                                        Pembayaran

                                    </th>

                                    <th width="150">

                                        Tanggal

                                    </th>

                                    <th width="90" class="text-center">

                                        Aksi

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php if (empty($reports)) : ?>

                                    <tr>

                                        <td colspan="9" class="text-center py-5 text-muted">

                                            <i class="bi bi-bar-chart fs-1 d-block mb-3"></i>

                                            Belum ada data laporan.

                                        </td>

                                    </tr>

                                <?php else : ?>

                                    <?php

                                    $no = $pagination['offset'] + 1;

                                    ?>

                                    <?php foreach ($reports as $report) : ?>

                                        <tr>

                                            <td>

                                                <?= $no++; ?>

                                            </td>

                                            <td>

                                                <strong>

                                                    <?= e((string) $report['invoice']); ?>

                                                </strong>

                                            </td>

                                            <td>

                                                <div class="fw-semibold">

                                                    <?= e((string) $report['nama']); ?>

                                                </div>

                                                <small class="text-muted">

                                                    <?= e((string) $report['email']); ?>

                                                </small>

                                            </td>

                                            <td>

                                                <span class="badge bg-info">

                                                    <?= number_format($report['total_item']); ?>

                                                </span>

                                            </td>

                                            <td>

                                                <strong class="text-success">

                                                    <?= rupiah((float) $report['total']); ?>

                                                </strong>

                                            </td>

                                            <td>

                                                <span class="badge bg-<?= e((string) $report['badge_status']); ?>">

                                                    <?= ucwords(str_replace('_', ' ', $report['status'])); ?>

                                                </span>

                                            </td>

                                            <td>

                                                <span class="badge bg-<?= e((string) $report['badge_pembayaran']); ?>">

                                                    <?= ucwords(str_replace('_', ' ', $report['status_pembayaran'])); ?>

                                                </span>

                                            </td>

                                            <td>

                                                <?= tanggal_indonesia($report['created_at']); ?>

                                            </td>

                                            <td class="text-center">

                                                <a

                                                    href="../pesanan/detail.php?id=<?= $report['id_pesanan']; ?>"

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
                <?php if ($pagination['total_pages'] > 1) : ?>

                    <nav class="mt-4">

                        <ul class="pagination justify-content-end mb-0">

                            <li class="page-item <?= $pagination['current_page'] <= 1 ? 'disabled' : ''; ?>">

                                <a

                                    class="page-link"

                                    href="?<?= http_build_query(array_merge($_GET, [

                                                'page' => $pagination['current_page'] - 1

                                            ])); ?>">

                                    <i class="bi bi-chevron-left"></i>

                                </a>

                            </li>

                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++) : ?>

                                <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : ''; ?>">

                                    <a

                                        class="page-link"

                                        href="?<?= http_build_query(array_merge($_GET, [

                                                    'page' => $i

                                                ])); ?>">

                                        <?= $i; ?>

                                    </a>

                                </li>

                            <?php endfor; ?>

                            <li class="page-item <?= $pagination['current_page'] >= $pagination['total_pages'] ? 'disabled' : ''; ?>">

                                <a

                                    class="page-link"

                                    href="?<?= http_build_query(array_merge($_GET, [

                                                'page' => $pagination['current_page'] + 1

                                            ])); ?>">

                                    <i class="bi bi-chevron-right"></i>

                                </a>

                            </li>

                        </ul>

                    </nav>

                <?php endif; ?>

            </div>
            <?php require_once '../../includes/footer.php'; ?>

        </main>

    </div>

</div>