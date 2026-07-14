<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : index.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Customer Management
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';
require_once 'pelanggan_data.php';

/** @var array $customers */
/** @var array $summary */
/** @var array $pagination */
/** @var string $keyword */
/** @var string $status */
/** @var string $kota */

$page_title = 'Manajemen Pelanggan';

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

                <!-- ==========================================
                     PAGE HEADER
                =========================================== -->

                <div class="page-header mb-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>

                            <h2 class="fw-bold mb-2">

                                Manajemen Pelanggan

                            </h2>

                            <p class="text-muted mb-0">

                                Kelola seluruh pelanggan AVOLICIUS.

                            </p>

                        </div>

                    </div>

                </div>

                <!-- ==========================================
                     SUMMARY
                =========================================== -->

                <div class="row g-3 mb-4">

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Total Pelanggan

                                </small>

                                <h3 class="mt-2 mb-0">

                                    <?= number_format($summary['total_pelanggan']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Pelanggan Aktif

                                </small>

                                <h3 class="mt-2 mb-0 text-success">

                                    <?= number_format($summary['aktif']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Member Baru Bulan Ini

                                </small>

                                <h3 class="mt-2 mb-0 text-primary">

                                    <?= number_format($summary['baru']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Total Belanja

                                </small>

                                <h5 class="mt-2 mb-0 text-warning">

                                    <?= rupiah($summary['total_belanja']); ?>

                                </h5>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ==========================================
                     FILTER
                =========================================== -->

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <h5 class="mb-0">

                                Filter Pelanggan

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

                                <div class="col-lg-4">

                                    <label class="form-label">

                                        Pencarian

                                    </label>

                                    <input

                                        type="text"

                                        name="keyword"

                                        class="form-control"

                                        placeholder="Kode Customer, Nama, Email..."

                                        value="<?= e($keyword); ?>">

                                </div>

                                <!-- STATUS -->

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

                                        <option value="aktif"

                                            <?= $status === 'aktif' ? 'selected' : ''; ?>>

                                            Aktif

                                        </option>

                                        <option value="nonaktif"

                                            <?= $status === 'nonaktif' ? 'selected' : ''; ?>>

                                            Nonaktif

                                        </option>

                                    </select>

                                </div>

                                <!-- KOTA -->

                                <div class="col-lg-3">

                                    <label class="form-label">

                                        Kota

                                    </label>

                                    <input

                                        type="text"

                                        name="kota"

                                        class="form-control"

                                        placeholder="Nama Kota"

                                        value="<?= e($kota); ?>">

                                </div>

                                <!-- BUTTON -->

                                <div class="col-lg-3">

                                    <div class="d-flex gap-2">

                                        <button

                                            type="submit"

                                            class="btn btn-success flex-fill">

                                            <i class="bi bi-search me-2"></i>

                                            Cari

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
                =========================================== -->
                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <h5 class="mb-0">

                                Daftar Pelanggan

                            </h5>

                            <small class="text-muted">

                                Total :

                                <strong>

                                    <?= number_format($pagination['total_rows']); ?>

                                </strong>

                                pelanggan

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

                                    <th width="80">

                                        Foto

                                    </th>

                                    <th>

                                        Pelanggan

                                    </th>

                                    <th width="160">

                                        Telepon

                                    </th>

                                    <th width="140">

                                        Kota

                                    </th>

                                    <th width="140">

                                        Total Pesanan

                                    </th>

                                    <th width="180">

                                        Total Belanja

                                    </th>

                                    <th width="120">

                                        Status

                                    </th>

                                    <th width="120" class="text-center">

                                        Aksi

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php if (empty($customers)) : ?>

                                    <tr>

                                        <td colspan="9" class="text-center py-5 text-muted">

                                            <i class="bi bi-people fs-1 d-block mb-3"></i>

                                            Belum ada data pelanggan.

                                        </td>

                                    </tr>

                                <?php else : ?>

                                    <?php

                                    $no = $pagination['offset'] + 1;

                                    ?>

                                    <?php foreach ($customers as $customer) : ?>

                                        <tr>

                                            <td>

                                                <?= $no++; ?>

                                            </td>

                                            <td>

                                                <?php

                                                $foto = !empty($customer['foto'])

                                                    ? '../../assets/uploads/pelanggan/' . $customer['foto']

                                                    : '../../assets/images/default-user.png';

                                                ?>

                                                <img

                                                    src="<?= $foto; ?>"

                                                    class="rounded-circle border"

                                                    width="50"

                                                    height="50"

                                                    style="object-fit:cover;">

                                            </td>

                                            <td>

                                                <div class="fw-semibold">

                                                    <?= e($customer['nama']); ?>

                                                </div>

                                                <div class="small text-muted">

                                                    <?= e($customer['kode_customer']); ?>

                                                </div>

                                                <div class="small text-muted">

                                                    <?= e($customer['email']); ?>

                                                </div>

                                            </td>

                                            <td>

                                                <?= e($customer['telepon']); ?>

                                            </td>

                                            <td>

                                                <?= e($customer['kota']); ?>

                                            </td>

                                            <td>

                                                <span class="badge bg-primary">

                                                    <?= number_format($customer['total_pesanan']); ?>

                                                </span>

                                            </td>

                                            <td>

                                                <strong class="text-success">

                                                    <?= rupiah((float)$customer['total_belanja']); ?>

                                                </strong>

                                            </td>

                                            <td>

                                                <?php

                                                $badge =

                                                    $customer['status'] === 'aktif'

                                                    ? 'success'

                                                    : 'secondary';

                                                ?>

                                                <span class="badge bg-<?= $badge; ?>">

                                                    <?= ucfirst($customer['status']); ?>

                                                </span>

                                            </td>

                                            <td class="text-center">

                                                <a

                                                    href="detail.php?id=<?= $customer['id_pelanggan']; ?>"

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