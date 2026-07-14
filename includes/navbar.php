<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : navbar.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Final Admin Navbar
 * VERSION     : 3.0.0
 * ==========================================================
 */

if (!isset($db)) {
    require_once __DIR__ . '/../config/init.php';
}

/** @var mysqli $db */

// ==========================================================
// LOAD WEBSITE SETTINGS
// ==========================================================

$settings = [];

$result = mysqli_query(
    $db,
    "
    SELECT
        setting_key,
        setting_value
    FROM setting
    "
);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $settings[$row['setting_key']] = $row['setting_value'];
    }
}

// ==========================================================
// NOTIFICATION
// ==========================================================

$total_pesanan_baru = 0;
$total_produk_habis = 0;
$total_pelanggan_baru = 0;

/*
|--------------------------------------------------------------------------
| Pesanan Baru
|--------------------------------------------------------------------------
*/

$sql = "

SELECT COUNT(*) total

FROM pesanan

WHERE

status='menunggu_pembayaran'

";

$res = mysqli_query($db, $sql);

if ($res) {

    $total_pesanan_baru = (int) mysqli_fetch_assoc($res)['total'];
}

/*
|--------------------------------------------------------------------------
| Produk Hampir Habis
|--------------------------------------------------------------------------
*/

$sql = "

SELECT COUNT(*) total

FROM produk

WHERE

stok<=5

AND deleted_at IS NULL

";

$res = mysqli_query($db, $sql);

if ($res) {

    $total_produk_habis = (int) mysqli_fetch_assoc($res)['total'];
}

/*
|--------------------------------------------------------------------------
| Pelanggan Hari Ini
|--------------------------------------------------------------------------
*/

$sql = "

SELECT COUNT(*) total

FROM pelanggan

WHERE

DATE(created_at)=CURDATE()

";

$res = mysqli_query($db, $sql);

if ($res) {

    $total_pelanggan_baru = (int) mysqli_fetch_assoc($res)['total'];
}

$notification_total =

    $total_pesanan_baru
    +
    $total_produk_habis
    +
    $total_pelanggan_baru;

$logo =

    $settings['logo']

    ??

    'logo.png';

$role =

    $_SESSION[SESSION_ADMIN]['role']

    ??

    'Administrator';
?>

<nav class="dashboard-navbar">

    <!-- ==========================================
         LEFT
    =========================================== -->

    <div class="navbar-left">

        <!-- Sidebar Toggle -->

        <button

            type="button"

            id="sidebarToggle"

            class="sidebar-toggle"

            aria-label="Toggle Sidebar">

            <i class="bi bi-list"></i>

        </button>

        <!-- ======================================
             GLOBAL SEARCH
        ======================================= -->

        <div class="navbar-search-wrapper">

            <form

                id="globalSearchForm"

                class="navbar-search"

                autocomplete="off"

                action="javascript:void(0)">

                <i class="bi bi-search"></i>

                <input

                    id="globalSearch"

                    name="q"

                    type="search"

                    class="form-control"

                    placeholder="Cari produk, pesanan, pelanggan..."

                    maxlength="100"

                    spellcheck="false"

                    autocomplete="off">

            </form>
            <div
                id="searchDropdown"
                class="search-dropdown d-none">

                <div
                    id="searchLoading"
                    class="search-loading d-none">

                    <div class="text-center py-4">

                        <div
                            class="spinner-border spinner-border-sm text-success"
                            role="status">
                        </div>

                        <div class="mt-2 text-muted small">

                            Mencari data...

                        </div>

                    </div>

                </div>

                <div
                    id="searchContent"
                    class="search-content">

                </div>

                <div
                    id="searchEmpty"
                    class="search-empty d-none">

                    <div class="text-center py-4">

                        <i class="bi bi-search fs-3 text-muted"></i>

                        <div class="mt-2 fw-semibold">

                            Tidak ada hasil ditemukan

                        </div>

                        <small class="text-muted">

                            Coba gunakan kata kunci lain.

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- ==========================================
         RIGHT
    =========================================== -->

    <div class="navbar-right">
        <!-- ===============================================
     NOTIFICATION
================================================ -->

        <div class="dropdown">

            <button

                class="navbar-icon position-relative"

                type="button"

                data-bs-toggle="dropdown"

                data-bs-auto-close="outside"

                aria-expanded="false">

                <i class="bi bi-bell"></i>

                <?php if ($notification_total > 0) : ?>

                    <span class="notification-badge">

                        <?= $notification_total; ?>

                    </span>

                <?php endif; ?>

            </button>

            <div class="dropdown-menu dropdown-menu-end shadow border-0 notification-dropdown">

                <!-- Header -->

                <div class="dropdown-header d-flex justify-content-between align-items-center">

                    <span>

                        <i class="bi bi-bell-fill me-2"></i>

                        Notifikasi

                    </span>

                    <span class="badge bg-primary">

                        <?= $notification_total; ?>

                    </span>

                </div>

                <div class="dropdown-divider"></div>

                <!-- ======================================
             PESANAN BARU
        ======================================= -->

                <a

                    href="<?= base_url('admin/pesanan/'); ?>"

                    class="dropdown-item d-flex align-items-center">

                    <div class="me-3 text-success">

                        <i class="bi bi-cart-check fs-5"></i>

                    </div>

                    <div class="flex-grow-1">

                        <div class="fw-semibold">

                            Pesanan Baru

                        </div>

                        <small class="text-muted">

                            <?= number_format($total_pesanan_baru); ?>

                            transaksi menunggu

                        </small>

                    </div>

                    <span class="badge bg-success">

                        <?= $total_pesanan_baru; ?>

                    </span>

                </a>

                <!-- ======================================
             PRODUK HAMPIR HABIS
        ======================================= -->

                <a

                    href="<?= base_url('admin/produk/'); ?>"

                    class="dropdown-item d-flex align-items-center">

                    <div class="me-3 text-warning">

                        <i class="bi bi-box-seam fs-5"></i>

                    </div>

                    <div class="flex-grow-1">

                        <div class="fw-semibold">

                            Produk Hampir Habis

                        </div>

                        <small class="text-muted">

                            Stok ≤ 5

                        </small>

                    </div>

                    <span class="badge bg-warning text-dark">

                        <?= $total_produk_habis; ?>

                    </span>

                </a>

                <!-- ======================================
             PELANGGAN BARU
        ======================================= -->

                <a

                    href="<?= base_url('admin/pelanggan/'); ?>"

                    class="dropdown-item d-flex align-items-center">

                    <div class="me-3 text-primary">

                        <i class="bi bi-person-plus fs-5"></i>

                    </div>

                    <div class="flex-grow-1">

                        <div class="fw-semibold">

                            Pelanggan Baru

                        </div>

                        <small class="text-muted">

                            Hari ini

                        </small>

                    </div>

                    <span class="badge bg-primary">

                        <?= $total_pelanggan_baru; ?>

                    </span>

                </a>

                <div class="dropdown-divider"></div>

                <div class="p-2 text-center">

                    <small class="text-muted">

                        Total

                        <strong>

                            <?= $notification_total; ?>

                        </strong>

                        notifikasi

                    </small>

                </div>

            </div>

        </div>
        <!-- ===============================================
     PROFILE
================================================ -->

        <div class="dropdown">

            <button

                type="button"

                class="navbar-profile"

                data-bs-toggle="dropdown"

                data-bs-auto-close="outside"

                aria-expanded="false">

                <img

                    src="<?= base_url('assets/images/' . e($logo)); ?>"

                    alt="<?= e($settings['website_name'] ?? 'AVOLICIUS'); ?>">

                <div class="profile-info">

                    <h6>

                        <?= e((string) ($_SESSION[SESSION_ADMIN]['nama'] ?? 'Administrator')); ?>

                    </h6>

                    <small>

                        <?= e(ucfirst((string) $role)); ?>

                    </small>

                </div>

                <i class="bi bi-chevron-down"></i>

            </button>

            <div class="dropdown-menu dropdown-menu-end shadow border-0 profile-dropdown">

                <!-- ======================================
             HEADER
        ======================================= -->

                <div class="dropdown-header text-center">

                    <img

                        src="<?= base_url('assets/images/' . e($logo)); ?>"

                        class="rounded-circle mb-2"

                        width="70"

                        height="70"

                        alt="<?= e($settings['website_name'] ?? 'AVOLICIUS'); ?>">

                    <h6 class="mb-1">

                        <?= e((string) ($_SESSION[SESSION_ADMIN]['nama'] ?? 'Administrator')); ?>

                    </h6>

                    <small class="text-muted">

                        <?= e(ucfirst((string) $role)); ?>

                    </small>

                </div>

                <div class="dropdown-divider"></div>

                <!-- Dashboard -->

                <a

                    href="<?= base_url('admin/dashboard/'); ?>"

                    class="dropdown-item">

                    <i class="bi bi-speedometer2 me-2"></i>

                    Dashboard

                </a>

                <!-- Pengaturan -->

                <a

                    href="<?= base_url('admin/pengaturan/'); ?>"

                    class="dropdown-item">

                    <i class="bi bi-gear me-2"></i>

                    Pengaturan

                </a>

                <!-- Website -->

                <a

                    href="<?= base_url(); ?>"

                    target="_blank"

                    rel="noopener"

                    class="dropdown-item">

                    <i class="bi bi-globe me-2"></i>

                    Lihat Website

                </a>

                <div class="dropdown-divider"></div>

                <!-- Logout -->

                <a

                    href="<?= base_url('admin/logout.php'); ?>"

                    class="dropdown-item text-danger"

                    id="logoutButton">

                    <i class="bi bi-box-arrow-right me-2"></i>

                    Logout

                </a>

            </div>

        </div>
    </div>

</nav>