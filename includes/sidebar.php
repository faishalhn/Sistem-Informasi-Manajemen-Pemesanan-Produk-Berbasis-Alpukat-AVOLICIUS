<?php

/**
 * ==========================================================
 * FILE        : sidebar.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Sidebar Administrator
 * VERSION     : 2.0.0
 * ==========================================================
 */

$active_menu = $active_menu ?? '';

?>

<aside class="sidebar" id="sidebar">

    <!-- ======================================================
         BRAND
    ======================================================= -->

    <div class="sidebar-brand">

        <a
            href="<?= base_url('admin/dashboard/'); ?>"
            class="sidebar-brand-link">

            <img
                src="<?= base_url('assets/images/logo/logo.png'); ?>"
                alt="<?= APP_NAME; ?>"
                class="sidebar-logo">

            <div>

                <h5 class="mb-0">

                    <?= APP_NAME; ?>

                </h5>

                <small>

                    Admin Panel

                </small>

            </div>

        </a>

    </div>

    <hr>

    <!-- ======================================================
         MENU
    ======================================================= -->

    <nav>

        <ul class="nav flex-column sidebar-menu">

            <li class="nav-item">

                <a
                    href="<?= base_url('admin/dashboard/'); ?>"
                    class="nav-link <?= $active_menu === 'dashboard' ? 'active' : ''; ?>">

                    <i class="bi bi-grid-1x2-fill"></i>

                    <span>Dashboard</span>

                </a>

            </li>

            <li class="nav-item">

                <a
                    href="<?= base_url('admin/kategori/'); ?>"
                    class="nav-link <?= $active_menu === 'kategori' ? 'active' : ''; ?>">

                    <i class="bi bi-tags-fill"></i>

                    <span>Kategori</span>

                </a>

            </li>

            <li class="nav-item">

                <a
                    href="<?= base_url('admin/produk/'); ?>"
                    class="nav-link <?= $active_menu === 'produk' ? 'active' : ''; ?>">

                    <i class="bi bi-box-seam-fill"></i>

                    <span>Produk</span>

                </a>

            </li>

            <li class="nav-item">

                <a
                    href="<?= base_url('admin/pesanan/'); ?>"
                    class="nav-link <?= $active_menu === 'pesanan' ? 'active' : ''; ?>">

                    <i class="bi bi-bag-check-fill"></i>

                    <span>Pesanan</span>

                </a>

            </li>

            <li class="nav-item">

                <a
                    href="<?= base_url('admin/pelanggan/'); ?>"
                    class="nav-link <?= $active_menu === 'pelanggan' ? 'active' : ''; ?>">

                    <i class="bi bi-people-fill"></i>

                    <span>Pelanggan</span>

                </a>

            </li>

            <li class="nav-item">

                <a
                    href="<?= base_url('admin/laporan/'); ?>"
                    class="nav-link <?= $active_menu === 'laporan' ? 'active' : ''; ?>">

                    <i class="bi bi-graph-up-arrow"></i>

                    <span>Laporan</span>

                </a>

            </li>

            <li class="nav-item">

                <a
                    href="<?= base_url('admin/pengaturan/'); ?>"
                    class="nav-link <?= $active_menu === 'pengaturan' ? 'active' : ''; ?>">

                    <i class="bi bi-gear-fill"></i>

                    <span>Pengaturan</span>

                </a>

            </li>

        </ul>

    </nav>

    <!-- ======================================================
         FOOTER
    ======================================================= -->

    <div class="sidebar-footer">

        <a
            href="<?= base_url(); ?>"
            target="_blank"
            class="btn btn-success w-100 mb-2">

            <i class="bi bi-globe"></i>

            Lihat Website

        </a>

        <a
            href="<?= base_url('admin/logout.php'); ?>"
            class="btn btn-outline-light w-100">

            <i class="bi bi-box-arrow-right"></i>

            Logout

        </a>

    </div>

</aside>