<?php

/**
 * ==========================================================
 * FILE        : login.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Halaman Login Administrator
 * VERSION     : 1.2.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../includes/guest_admin.php';

$page_title = "Login Administrator";

require_once '../includes/header.php';

?>

<div class="login-page">

    <div class="login-card card shadow-lg border-0">

        <div class="card-body p-4">

            <!-- Back -->
            <div class="mb-3">

                <a
                    href="<?= base_url(); ?>"
                    class="text-decoration-none text-success">

                    <i class="bi bi-arrow-left"></i>

                    Kembali ke Website

                </a>

            </div>

            <!-- Logo -->
            <div class="text-center mb-4">

                <img
                    src="<?= base_url('assets/images/logo/logo.png'); ?>"
                    alt="Logo AVOLICIUS"
                    class="login-logo">

                <h2 class="login-title">
                    <?= APP_NAME; ?>
                </h2>

                <p class="login-subtitle">
                    Administrator Login
                </p>

            </div>

            <?php require_once '../includes/alert.php'; ?>

            <form
                action="<?= base_url('process/auth/login.php'); ?>"
                method="POST"
                autocomplete="off">

                <!-- CSRF -->
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= csrf_token(); ?>">

                <!-- Email -->
                <div class="mb-3">

                    <label
                        for="email"
                        class="form-label">

                        Email

                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="Masukkan Email"
                        required
                        autofocus>

                </div>

                <!-- Password -->
                <div class="mb-3">

                    <label
                        for="password"
                        class="form-label">

                        Password

                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan Password"
                        required>

                </div>

                <!-- Remember -->
                <div class="form-check mb-4">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="remember">

                    <label
                        class="form-check-label"
                        for="remember">

                        Ingat Saya

                    </label>

                </div>

                <!-- Button -->
                <button
                    type="submit"
                    class="btn btn-avolicius w-100">

                    <i class="bi bi-box-arrow-in-right"></i>

                    Login

                </button>

            </form>

            <hr>

            <div class="text-center small text-muted">

                &copy; <?= date('Y'); ?>

                <?= APP_NAME; ?>

                <br>

                Version <?= APP_VERSION; ?>

            </div>

        </div>

    </div>

</div>

<?php

require_once '../includes/footer.php';

?>