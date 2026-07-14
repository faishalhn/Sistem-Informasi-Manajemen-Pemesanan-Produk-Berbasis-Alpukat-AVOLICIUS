<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : index.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Product Management
 * VERSION     : 2.1.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';

require_once 'produk_data.php';

/**
 * ==========================================================
 * VARIABLE FROM produk_data.php
 * ==========================================================
 */

/** @var string $keyword */

/** @var int $id_kategori */

/** @var string $status */

/** @var array<int,array<string,mixed>> $kategori_list */

/** @var array<int,array<string,mixed>> $products */

/** @var array<string,mixed> $summary */

/** @var array<string,mixed> $pagination */

/** @var array<string,mixed> $page_information */

/** @var array<string,string> $status_options */

/** @var array<string,mixed> $product_information */

$page_title = 'Manajemen Produk';

$active_menu = 'produk';

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

                <!-- ==========================================
     PAGE HEADER
========================================== -->

                <div class="page-header mb-4">

                    <?php require_once '../../includes/alert.php'; ?>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>

                            <h2 class="fw-bold mb-2">

                                Manajemen Produk

                            </h2>

                            <p class="text-muted mb-0">

                                Kelola seluruh produk AVOLICIUS dengan mudah.

                            </p>

                        </div>

                        <a
                            href="form.php"
                            class="btn btn-success">

                            <i class="bi bi-plus-circle me-2"></i>

                            Tambah Produk

                        </a>

                    </div>

                </div>

                <!-- ==========================================
                     TOOLBAR
                =========================================== -->

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-body">

                        <form
                            method="GET"
                            class="row g-3 align-items-end">

                            <!-- Search -->

                            <div class="col-lg-4    ">

                                <label class="form-label">

                                    Cari Produk

                                </label>

                                <input
                                    type="search"
                                    name="keyword"
                                    value="<?= e($keyword); ?>"
                                    class="form-control"
                                    placeholder="Nama, SKU, Barcode...">

                            </div>

                            <!-- Kategori -->

                            <div class="col-lg-3">

                                <label class="form-label">

                                    Kategori

                                </label>

                                <select
                                    name="kategori"
                                    class="form-select">

                                    <option value="0">

                                        Semua Kategori

                                    </option>

                                    <?php foreach ($kategori_list as $kategori): ?>

                                        <option
                                            value="<?= $kategori['id_kategori']; ?>"
                                            <?= $id_kategori == $kategori['id_kategori']
                                                ? 'selected'
                                                : ''; ?>>

                                            <?= e($kategori['nama_kategori']); ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <!-- Status -->

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

                                    <?php foreach ($status_options as $key => $label): ?>

                                        <option
                                            value="<?= $key; ?>"
                                            <?= $status === $key
                                                ? 'selected'
                                                : ''; ?>>

                                            <?= $label; ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <!-- Button -->

                            <div class="col-lg-3">

                                <div class="d-flex gap-2">

                                    <button
                                        class="btn btn-success flex-fill">

                                        <i class="bi bi-search me-1"></i>

                                        Filter

                                    </button>

                                    <a
                                        href="index.php"
                                        class="btn btn-outline-secondary">

                                        <i class="bi bi-arrow-clockwise"></i>

                                    </a>

                                    <a
                                        href="export.php?keyword=<?= urlencode($keyword); ?>&kategori=<?= $id_kategori; ?>&status=<?= urlencode($status); ?>"
                                        class="btn btn-outline-success">

                                        <i class="bi bi-download me-2"></i>

                                        Export CSV

                                    </a>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

                <!-- ==========================================
                     PRODUCT TABLE
                =========================================== -->

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <div
                            class="d-flex justify-content-between align-items-center">

                            <h5 class="mb-0">

                                Daftar Produk

                            </h5>

                            <span class="badge bg-success">

                                <?= $summary['total_produk']; ?>

                                Produk

                            </span>

                        </div>

                    </div>

                    <div class="table-responsive">

                        <table
                            class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th width="50">

                                        #

                                    </th>

                                    <th width="90">

                                        Foto

                                    </th>

                                    <th>

                                        Produk

                                    </th>

                                    <th>

                                        Kategori

                                    </th>

                                    <th>

                                        Harga

                                    </th>

                                    <th>

                                        Stok

                                    </th>

                                    <th>

                                        Views

                                    </th>

                                    <th>

                                        Sold

                                    </th>

                                    <th>

                                        Status

                                    </th>

                                    <th width="170">

                                        Aksi

                                    </th>

                                </tr>

                            </thead>

                            <tbody>
                                <?php if ($product_information['has_data']) : ?>

                                    <?php foreach ($products as $index => $product) : ?>

                                        <tr>

                                            <!-- ==========================================
                                                NO
                                            =========================================== -->

                                            <td>

                                                <?= $page_information['show_from'] + $index; ?>

                                            </td>

                                            <!-- ==========================================
                                                THUMBNAIL
                                            =========================================== -->

                                            <td>

                                                <img

                                                    src="<?= base_url('assets/uploads/produk/' . $product['thumbnail']); ?>"

                                                    alt="<?= e($product['nama_produk']); ?>"

                                                    class="rounded border"

                                                    width="65"

                                                    height="65"

                                                    style="object-fit:cover;">

                                            </td>

                                            <!-- ==========================================
                                                PRODUK
                                            =========================================== -->

                                            <td>

                                                <div class="fw-semibold">

                                                    <?= e($product['nama_produk']); ?>

                                                </div>

                                                <small class="text-muted">

                                                    SKU :
                                                    <?= e($product['sku']); ?>

                                                </small>

                                                <br>

                                                <small class="text-muted">

                                                    <?= e($product['kode_produk']); ?>

                                                </small>

                                                <?php if ($product['featured']) : ?>

                                                    <br>

                                                    <span class="badge bg-warning text-dark mt-1">

                                                        ⭐ Featured

                                                    </span>

                                                <?php endif; ?>

                                            </td>

                                            <!-- ==========================================
                                               KATEGORI
                                             =========================================== -->

                                            <td>

                                                <?= e($product['kategori']); ?>

                                            </td>

                                            <!-- ==========================================
                                              HARGA
                                             =========================================== -->

                                            <td>

                                                <strong>

                                                    <?= rupiah($product['harga']); ?>

                                                </strong>

                                                <?php if ($product['diskon'] > 0) : ?>

                                                    <br>

                                                    <small class="text-danger">

                                                        Diskon <?= $product['diskon']; ?>%

                                                    </small>

                                                <?php endif; ?>

                                            </td>

                                            <!-- ==========================================
                                                 STOK
                                             =========================================== -->

                                            <td>

                                                <span class="badge

                                                            <?=
                                                            match ($product['stock_status']) {

                                                                'habis' => 'bg-danger',

                                                                'warning' => 'bg-warning text-dark',

                                                                default => 'bg-success'
                                                            };
                                                            ?>">

                                                    <?= $product['stok']; ?>

                                                    <?= e($product['satuan']); ?>

                                                </span>

                                            </td>

                                            <!-- ==========================================
                                                VIEWS
                                            =========================================== -->

                                            <td>

                                                <span class="badge bg-info">

                                                    <i class="bi bi-eye"></i>

                                                    <?= number_format($product['views']); ?>

                                                </span>

                                            </td>

                                            <!-- ==========================================
                                                 SOLD
                                             =========================================== -->

                                            <td>

                                                <span class="badge bg-primary">

                                                    <i class="bi bi-fire"></i>

                                                    <?= number_format($product['sold']); ?>

                                                </span>

                                            </td>

                                            <!-- ==========================================
                                                STATUS
                                            =========================================== -->

                                            <td>

                                                <span class="badge bg-<?= $product['badge']; ?>">

                                                    <?= ucfirst($product['status']); ?>

                                                </span>

                                            </td>

                                            <!-- ==========================================
                                                ACTION
                                            =========================================== -->

                                            <td>

                                                <div class="d-flex gap-1">

                                                    <a

                                                        href="detail.php?id=<?= $product['id_produk']; ?>"

                                                        class="btn btn-sm btn-outline-info"

                                                        data-bs-toggle="tooltip"

                                                        title="Detail">

                                                        <i class="bi bi-eye"></i>

                                                    </a>

                                                    <a

                                                        href="form.php?id=<?= $product['id_produk']; ?>"

                                                        class="btn btn-sm btn-outline-success"

                                                        data-bs-toggle="tooltip"

                                                        title="Edit">

                                                        <i class="bi bi-pencil-square"></i>

                                                    </a>

                                                    <a

                                                        href="detail.php?id=<?= $product['id_produk']; ?>"

                                                        class="btn btn-sm btn-outline-warning"

                                                        data-bs-toggle="tooltip"

                                                        title="Gallery">

                                                        <i class="bi bi-images"></i>

                                                    </a>

                                                    <a

                                                        href="process.php?action=delete&id=<?= $product['id_produk']; ?>"

                                                        class="btn btn-sm btn-outline-danger"

                                                        onclick="return confirm('Hapus produk ini?')"

                                                        data-bs-toggle="tooltip"

                                                        title="Hapus">

                                                        <i class="bi bi-trash"></i>

                                                    </a>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else : ?>

                                    <tr>

                                        <td

                                            colspan="10"

                                            class="text-center py-5">

                                            <i

                                                class="bi bi-box-seam fs-1 text-secondary d-block mb-3">

                                            </i>

                                            <h5 class="text-muted">

                                                Produk belum tersedia

                                            </h5>

                                            <small class="text-muted">

                                                Tambahkan produk pertama
                                            </small>

                                            <div class="mt-4">

                                                <a
                                                    href="form.php"
                                                    class="btn btn-success">

                                                    <i class="bi bi-plus-circle me-2"></i>

                                                    Tambah Produk

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                    <!-- ==========================================
                        PAGINATION
                    ========================================== -->

                    <div class="card-footer bg-white">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                            <div class="text-muted small">

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

                                    <?= $page_information['total_data']; ?>

                                </strong>

                                produk

                            </div>

                            <nav>

                                <ul class="pagination pagination-sm mb-0">

                                    <li class="page-item <?= !$pagination['has_previous'] ? 'disabled' : ''; ?>">

                                        <a
                                            class="page-link"
                                            href="?page=<?= $pagination['previous_page']; ?>&keyword=<?= urlencode($keyword); ?>&kategori=<?= $id_kategori; ?>&status=<?= urlencode($status); ?>">

                                            <i class="bi bi-chevron-left"></i>

                                        </a>

                                    </li>

                                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++) : ?>

                                        <li class="page-item <?= $pagination['page'] == $i ? 'active' : ''; ?>">

                                            <a
                                                class="page-link"
                                                href="?page=<?= $i; ?>&keyword=<?= urlencode($keyword); ?>&kategori=<?= $id_kategori; ?>&status=<?= urlencode($status); ?>">

                                                <?= $i; ?>

                                            </a>

                                        </li>

                                    <?php endfor; ?>

                                    <li class="page-item <?= !$pagination['has_next'] ? 'disabled' : ''; ?>">

                                        <a
                                            class="page-link"
                                            href="?page=<?= $pagination['next_page']; ?>&keyword=<?= urlencode($keyword); ?>&kategori=<?= $id_kategori; ?>&status=<?= urlencode($status); ?>">

                                            <i class="bi bi-chevron-right"></i>

                                        </a>

                                    </li>

                                </ul>

                            </nav>

                        </div>

                    </div>

                </div>

                <!-- ==========================================
                 SUMMARY CARD
                ========================================== -->

                <div class="row mt-4 g-3">

                    <div class="col-xl-3 col-md-6">

                        <div class="dashboard-card">

                            <div class="card-body">

                                <h6 class="text-muted mb-2">

                                    Total Produk

                                </h6>

                                <h2 class="mb-0">

                                    <?= number_format($summary['total_produk']); ?>

                                </h2>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-card">

                            <div class="card-body">

                                <h6 class="text-muted mb-2">

                                    Produk Aktif

                                </h6>

                                <h2 class="mb-0">

                                    <?= number_format($summary['produk_aktif']); ?>

                                </h2>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-card">

                            <div class="card-body">

                                <h6 class="text-muted mb-2">

                                    Stok Rendah
                                </h6>

                                <h2 class="mb-0">

                                    <?= number_format($summary['stok_rendah']); ?>

                                </h2>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="dashboard-card">

                            <div class="card-body">

                                <h6 class="text-muted mb-2">

                                    Total Terjual

                                </h6>

                                <h2 class="mb-0">

                                    <?= number_format($summary['total_sold']); ?>

                                </h2>

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