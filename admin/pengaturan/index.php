<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : index.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Website Settings
 * VERSION     : 1.0.0
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';
require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// LOAD SETTINGS
// ==========================================================

$settings = [];

$sql = "

SELECT

    setting_key,

    setting_value,

    setting_group

FROM setting

ORDER BY

    setting_group,

    setting_key

";

$result = mysqli_query(
    $db,
    $sql
);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $settings[$row['setting_key']] = $row['setting_value'];
    }
}

$page_title = 'Pengaturan';

$active_menu = 'pengaturan';

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
                <div class="page-header mb-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h2 class="fw-bold mb-2">

                                Pengaturan Website

                            </h2>

                            <p class="text-muted mb-0">

                                Kelola konfigurasi AVOLICIUS.

                            </p>

                        </div>

                    </div>

                </div>
                <form

                    action="process.php"

                    method="POST"

                    enctype="multipart/form-data">

                    <div class="row g-4">
                        <div class="col-lg-6">

                            <div class="card shadow-sm border-0 h-100">

                                <div class="card-header bg-white">

                                    <h5 class="mb-0">

                                        General

                                    </h5>

                                </div>

                                <div class="card-body">

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Nama Website

                                        </label>

                                        <input

                                            type="text"

                                            name="website_name"

                                            class="form-control"

                                            value="<?= e($settings['website_name'] ?? ''); ?>">

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Tagline

                                        </label>

                                        <input

                                            type="text"

                                            name="tagline"

                                            class="form-control"

                                            value="<?= e($settings['tagline'] ?? ''); ?>">

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Email

                                        </label>

                                        <input

                                            type="email"

                                            name="email"

                                            class="form-control"

                                            value="<?= e($settings['email'] ?? ''); ?>">

                                    </div>

                                    <div>

                                        <label class="form-label">

                                            Telepon

                                        </label>

                                        <input

                                            type="text"

                                            name="phone"

                                            class="form-control"

                                            value="<?= e($settings['phone'] ?? ''); ?>">

                                    </div>

                                </div>

                            </div>

                        </div>
                        <div class="col-lg-6">

                            <div class="card shadow-sm border-0 h-100">

                                <div class="card-header bg-white">

                                    <h5 class="mb-0">

                                        Alamat

                                    </h5>

                                </div>

                                <div class="card-body">

                                    <label class="form-label">

                                        Alamat

                                    </label>

                                    <textarea

                                        name="address"

                                        rows="6"

                                        class="form-control"><?= e($settings['address'] ?? ''); ?></textarea>

                                </div>

                            </div>

                        </div>
                        <div class="col-lg-6">

                            <div class="card shadow-sm border-0 h-100">

                                <div class="card-header bg-white">

                                    <h5 class="mb-0">

                                        Appearance

                                    </h5>

                                </div>

                                <div class="card-body">

                                    <div class="mb-4">

                                        <label class="form-label">

                                            Logo Website

                                        </label>

                                        <input

                                            type="file"

                                            name="logo"

                                            class="form-control"

                                            accept=".png,.jpg,.jpeg,.webp">

                                        <?php if (!empty($settings['logo'])) : ?>

                                            <div class="mt-3">

                                                <img

                                                    src="../../assets/images/<?= e($settings['logo']); ?>"

                                                    class="img-thumbnail"

                                                    style="max-height:90px;">

                                            </div>

                                        <?php endif; ?>

                                    </div>

                                    <div>

                                        <label class="form-label">

                                            Favicon

                                        </label>

                                        <input

                                            type="file"

                                            name="favicon"

                                            class="form-control"

                                            accept=".png,.ico">

                                        <?php if (!empty($settings['favicon'])) : ?>

                                            <div class="mt-3">

                                                <img

                                                    src="../../assets/images/<?= e($settings['favicon']); ?>"

                                                    class="img-thumbnail"

                                                    style="max-height:60px;">

                                            </div>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>

                        </div>
                        <div class="col-lg-6">

                            <div class="card shadow-sm border-0 h-100">

                                <div class="card-header bg-white">

                                    <h5 class="mb-0">

                                        Social Media

                                    </h5>

                                </div>

                                <div class="card-body">

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Facebook

                                        </label>

                                        <input

                                            type="url"

                                            name="facebook"

                                            class="form-control"

                                            value="<?= e($settings['facebook'] ?? ''); ?>">

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Instagram

                                        </label>

                                        <input

                                            type="url"

                                            name="instagram"

                                            class="form-control"

                                            value="<?= e($settings['instagram'] ?? ''); ?>">

                                    </div>

                                    <div>

                                        <label class="form-label">

                                            WhatsApp

                                        </label>

                                        <input

                                            type="text"

                                            name="whatsapp"

                                            class="form-control"

                                            value="<?= e($settings['whatsapp'] ?? ''); ?>">

                                    </div>

                                </div>

                            </div>

                        </div>
                        <div class="col-12">

                            <div class="card shadow-sm border-0">

                                <div class="card-body text-end">

                                    <button

                                        type="submit"

                                        class="btn btn-success px-4">

                                        <i class="bi bi-check-circle me-2"></i>

                                        Simpan Pengaturan

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>
            </div>
            <?php require_once '../../includes/footer.php'; ?>

        </main>

    </div>

</div>