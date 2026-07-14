<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : index.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Category Management
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';

require_once 'kategori_data.php';

/**
 * ==========================================================
 * VARIABLE FROM kategori_data.php
 * ==========================================================
 */

/** @var string $keyword */

/** @var string $status */

/** @var array<int,array<string,mixed>> $categories */

/** @var array<string,mixed> $summary */

/** @var array<string,mixed> $pagination */

/** @var array<string,mixed> $page_information */

/** @var array<string,string> $status_options */

/** @var array<string,mixed> $category_information */

$page_title = 'Manajemen Kategori';

$active_menu = 'kategori';

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

            <div class="container-fluid py-4">

                <?php require_once '../../includes/alert.php'; ?>

                <!-- ==========================================
     PAGE HEADER
========================================== -->

                <div class="page-header mb-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>

                            <h2 class="fw-bold mb-2">

                                Manajemen Kategori

                            </h2>

                            <p class="text-muted mb-0">

                                Kelola seluruh kategori produk AVOLICIUS.

                            </p>

                        </div>

                        <a
                            href="form.php"
                            class="btn btn-success">

                            <i class="bi bi-plus-circle me-2"></i>

                            Tambah Kategori

                        </a>

                    </div>

                </div>

                <!-- ==========================================
     SUMMARY
========================================== -->

                <div class="row g-3 mb-4">

                    <div class="col-xl-3 col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Total Kategori

                                </small>

                                <h3 class="mt-2 mb-0">

                                    <?= number_format($summary['total_kategori']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-3 col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Kategori Aktif

                                </small>

                                <h3 class="mt-2 mb-0 text-success">

                                    <?= number_format($summary['kategori_aktif']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-3 col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Kategori Nonaktif

                                </small>

                                <h3 class="mt-2 mb-0 text-danger">

                                    <?= number_format($summary['kategori_nonaktif']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-3 col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <small class="text-muted">

                                    Total Produk

                                </small>

                                <h3 class="mt-2 mb-0 text-primary">

                                    <?= number_format($summary['total_produk']); ?>

                                </h3>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ==========================================
     FILTER
========================================== -->

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-body">

                        <form method="GET">

                            <div class="row g-3 align-items-end">

                                <div class="col-lg-5">

                                    <label class="form-label">

                                        Pencarian

                                    </label>

                                    <input
                                        type="text"
                                        name="keyword"
                                        class="form-control"
                                        value="<?= e($keyword); ?>"
                                        placeholder="Nama, slug atau deskripsi...">

                                </div>

                                <div class="col-lg-3">

                                    <label class="form-label">

                                        Status

                                    </label>

                                    <select
                                        name="status"
                                        class="form-select">

                                        <option value="">

                                            Semua Status

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

                                <div class="col-lg-4">

                                    <div class="d-flex gap-2 justify-content-end">

                                        <!-- FILTER -->

                                        <button
                                            type="submit"
                                            class="btn btn-success flex-grow-1">

                                            <i class="bi bi-search me-2"></i>

                                            Filter

                                        </button>

                                        <!-- RESET -->

                                        <a
                                            href="index.php"
                                            class="btn btn-outline-secondary">

                                            <i class="bi bi-arrow-clockwise"></i>

                                        </a>

                                        <!-- EXPORT -->

                                        <a
                                            href="export.php?<?= http_build_query($_GET); ?>"
                                            class="btn btn-outline-success">

                                            <i class="bi bi-download me-2"></i>

                                            Export CSV

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>
                <!-- ==========================================
                     DATA TABLE
                =========================================== -->

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <h5 class="mb-0">

                                Daftar Kategori

                            </h5>

                            <span class="badge rounded-pill bg-success">

                                <?= number_format($page_information['total_data']); ?>

                                Kategori

                            </span>

                        </div>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th width="70">

                                        No

                                    </th>

                                    <th width="120">

                                        Kode

                                    </th>

                                    <th>

                                        Nama Kategori

                                    </th>

                                    <th class="text-center">

                                        Warna

                                    </th>

                                    <th class="text-center">

                                        Produk

                                    </th>

                                    <th class="text-center">

                                        Urutan

                                    </th>

                                    <th class="text-center">

                                        Status

                                    </th>

                                    <th width="180" class="text-center">

                                        Aksi

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php if (empty($categories)) : ?>

                                    <tr>

                                        <td
                                            colspan="9"
                                            class="text-center py-5 text-muted">

                                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>

                                            Belum ada data kategori.

                                        </td>

                                    </tr>

                                <?php else : ?>

                                    <?php

                                    $no = $page_information['show_from'];

                                    foreach ($categories as $category) :

                                    ?>

                                        <tr>

                                            <td>

                                                <?= $no++; ?>

                                            </td>

                                            <td>

                                                <span class="badge bg-dark">

                                                    <?= e($category['kode_kategori']); ?>

                                                </span>

                                            </td>

                                            <td>

                                                <strong>

                                                    <?= e($category['nama_kategori']); ?>

                                                </strong>

                                            </td>

                                            <td class="text-center">

                                                <span

                                                    class="d-inline-block rounded-circle border"

                                                    style="width:24px;height:24px;background:<?= e($category['warna']); ?>">

                                                </span>

                                            </td>

                                            <td class="text-center">

                                                <span class="badge bg-primary">

                                                    <?= number_format($category['jumlah_produk']); ?>

                                                </span>

                                            </td>

                                            <td class="text-center">

                                                <?= $category['urutan']; ?>

                                            </td>

                                            <td class="text-center">

                                                <span class="badge bg-<?= e($category['badge']); ?>">

                                                    <?= ucfirst(e($category['status'])); ?>

                                                </span>

                                            </td>

                                            <td class="text-center">

                                                <div class="btn-group btn-group-sm">

                                                    <a
                                                        href="form.php?id=<?= $category['id_kategori']; ?>"
                                                        class="btn btn-outline-primary">

                                                        <i class="bi bi-pencil"></i>

                                                    </a>

                                                    <a
                                                        href="process.php?action=delete&id=<?= $category['id_kategori']; ?>"
                                                        class="btn btn-outline-danger"
                                                        onclick="return confirm('Hapus kategori ini?')">

                                                        <i class="bi bi-trash"></i>

                                                    </a>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>
                    <!-- ==========================================
                     PAGINATION
                =========================================== -->

                    <div class="card-footer bg-white">

                        <div class="row align-items-center">

                            <div class="col-md-6">

                                <small class="text-muted">

                                    Menampilkan

                                    <strong>

                                        <?= $page_information['show_from']; ?>

                                    </strong>

                                    -

                                    <strong>

                                        <?= $page_information['show_to']; ?>

                                    </strong>

                                    dari

                                    <strong>

                                        <?= number_format($page_information['total_data']); ?>

                                    </strong>

                                    kategori

                                </small>

                            </div>

                            <div class="col-md-6">

                                <?php if ($pagination['total_pages'] > 1) : ?>

                                    <nav>

                                        <ul class="pagination pagination-sm justify-content-md-end mb-0">

                                            <li class="page-item <?= $pagination['has_previous'] ? '' : 'disabled'; ?>">

                                                <a

                                                    class="page-link"

                                                    href="?page=<?= $pagination['previous_page']; ?>&keyword=<?= urlencode($keyword); ?>&status=<?= urlencode($status); ?>">

                                                    <i class="bi bi-chevron-left"></i>

                                                </a>

                                            </li>

                                            <?php

                                            for (

                                                $i = 1;

                                                $i <= $pagination['total_pages'];

                                                $i++

                                            ) :

                                            ?>

                                                <li

                                                    class="page-item <?= $i === $pagination['page'] ? 'active' : ''; ?>">

                                                    <a

                                                        class="page-link"

                                                        href="?page=<?= $i; ?>&keyword=<?= urlencode($keyword); ?>&status=<?= urlencode($status); ?>">

                                                        <?= $i; ?>

                                                    </a>

                                                </li>

                                            <?php endfor; ?>

                                            <li class="page-item <?= $pagination['has_next'] ? '' : 'disabled'; ?>">

                                                <a

                                                    class="page-link"

                                                    href="?page=<?= $pagination['next_page']; ?>&keyword=<?= urlencode($keyword); ?>&status=<?= urlencode($status); ?>">

                                                    <i class="bi bi-chevron-right"></i>

                                                </a>

                                            </li>

                                        </ul>

                                    </nav>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>
                <?php require_once '../../includes/footer.php'; ?>

        </main>

    </div>

</div>