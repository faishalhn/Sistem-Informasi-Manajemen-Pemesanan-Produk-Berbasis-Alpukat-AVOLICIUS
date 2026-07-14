<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : form.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Category Form
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';

require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// DEFAULT
// ==========================================================

$id_kategori = (int) ($_GET['id'] ?? 0);

$is_edit = $id_kategori > 0;

$category = [

    'id_kategori' => 0,

    'kode_kategori' => '',

    'nama_kategori' => '',

    'slug' => '',

    'warna' => '#198754',

    'deskripsi' => '',

    'icon' => 'bi-grid',

    'urutan' => 1,

    'status' => 'aktif'

];

// ==========================================================
// LOAD DATA
// ==========================================================

if ($is_edit) {

    $stmt = mysqli_prepare(

        $db,

        "

        SELECT

            *

        FROM kategori

        WHERE

            id_kategori = ?

            AND deleted_at IS NULL

        "

    );

    mysqli_stmt_bind_param(

        $stmt,

        "i",

        $id_kategori

    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {

        $category = mysqli_fetch_assoc($result);
    } else {

        $_SESSION['error'] = 'Kategori tidak ditemukan.';

        redirect('admin/kategori/');
    }

    mysqli_stmt_close($stmt);
}

// ==========================================================
// PAGE
// ==========================================================

$page_title =

    $is_edit

    ? 'Edit Kategori'

    : 'Tambah Kategori';

$active_menu = 'kategori';

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

                <div class="page-header mb-4 py-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>

                            <h2 class="fw-bold mb-2">

                                <?= $is_edit ? 'Edit Kategori' : 'Tambah Kategori'; ?>

                            </h2>

                            <p class="text-muted mb-0">

                                Kelola kategori produk AVOLICIUS.

                            </p>

                        </div>

                        <a

                            href="index.php"

                            class="btn btn-outline-secondary">

                            <i class="bi bi-arrow-left me-2"></i>

                            Kembali

                        </a>

                    </div>

                </div>

                <!-- ==========================================
     FORM
========================================== -->

                <form

                    action="process.php"

                    method="POST"

                    autocomplete="off">

                    <input

                        type="hidden"

                        name="id_kategori"

                        value="<?= (int) $category['id_kategori']; ?>">

                    <div class="row g-4">

                        <!-- ======================================
             INFORMASI KATEGORI
        ======================================= -->

                        <div class="col-lg-8">

                            <div class="card shadow-sm border-0">

                                <div class="card-header bg-white">

                                    <h5 class="mb-0">

                                        Informasi Kategori

                                    </h5>

                                </div>

                                <div class="card-body">
                                    <!-- Kode -->

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Kode Kategori

                                            <span class="text-danger">*</span>

                                        </label>

                                        <input

                                            type="text"

                                            name="kode_kategori"

                                            class="form-control"

                                            maxlength="30"

                                            required

                                            value="<?= e($category['kode_kategori'] ?? ''); ?>">

                                    </div>

                                    <!-- Nama -->

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Nama Kategori

                                            <span class="text-danger">*</span>

                                        </label>

                                        <input

                                            type="text"

                                            name="nama_kategori"

                                            id="nama_kategori"

                                            class="form-control"

                                            maxlength="100"

                                            required

                                            value="<?= e($category['nama_kategori'] ?? ''); ?>">

                                    </div>

                                    <!-- Slug -->

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Slug

                                        </label>

                                        <input

                                            type="text"

                                            name="slug"

                                            id="slug"

                                            class="form-control"

                                            maxlength="150"

                                            required

                                            value="<?= e($category['slug'] ?? ''); ?>">

                                        <div class="form-text">

                                            Slug otomatis dibuat dari nama kategori.

                                        </div>

                                    </div>

                                    <!-- Deskripsi -->

                                    <div class="mb-0">

                                        <label class="form-label">

                                            Deskripsi

                                        </label>

                                        <textarea

                                            name="deskripsi"

                                            rows="6"

                                            class="form-control"><?= e($category['deskripsi'] ?? ''); ?></textarea>

                                    </div>

                                </div>

                            </div>

                        </div>
                        <!-- ======================================
             SIDEBAR FORM
        ======================================= -->

                        <div class="col-lg-4">

                            <!-- STATUS -->

                            <div class="card shadow-sm border-0 mb-4">

                                <div class="card-header bg-white">

                                    <h5 class="mb-0">

                                        Pengaturan

                                    </h5>

                                </div>

                                <div class="card-body">

                                    <!-- Status -->

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Status

                                        </label>

                                        <select

                                            name="status"

                                            class="form-select">

                                            <option
                                                value="aktif"
                                                <?= $category['status'] === 'aktif' ? 'selected' : ''; ?>>

                                                Aktif

                                            </option>

                                            <option
                                                value="nonaktif"
                                                <?= $category['status'] === 'nonaktif' ? 'selected' : ''; ?>>

                                                Nonaktif

                                            </option>

                                        </select>

                                    </div>

                                    <!-- Urutan -->

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Urutan

                                        </label>

                                        <input

                                            type="number"

                                            name="urutan"

                                            class="form-control"

                                            min="1"

                                            value="<?= (int) $category['urutan']; ?>">

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Warna

                                        </label>

                                        <input

                                            type="color"

                                            name="warna"

                                            class="form-control form-control-color"

                                            value="<?= e($category['warna'] ?? '#198754'); ?>">

                                    </div>

                                    <!-- Icon -->

                                    <div class="mb-0">

                                        <label class="form-label">

                                            Bootstrap Icon

                                        </label>

                                        <input

                                            type="text"

                                            name="icon"

                                            class="form-control"

                                            placeholder="bi-grid"

                                            value="<?= e($category['icon'] ?? 'bi-grid'); ?>">

                                        <div class="form-text">

                                            Contoh:
                                            bi-grid,
                                            bi-basket,
                                            bi-box-seam,
                                            bi-tags

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- SUBMIT -->

                            <div class="card shadow-sm border-0">

                                <div class="card-body d-grid gap-2">

                                    <button

                                        type="submit"

                                        class="btn btn-success">

                                        <i class="bi bi-check-circle me-2"></i>

                                        <?= $is_edit ? 'Update Kategori' : 'Simpan Kategori'; ?>

                                    </button>

                                    <a

                                        href="index.php"

                                        class="btn btn-outline-secondary">

                                        Batal

                                    </a>

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

<!-- ==========================================
     AUTO SLUG
========================================== -->

<script>
    document.addEventListener(

        'DOMContentLoaded',

        function() {

            const nama = document.getElementById(

                'nama_kategori'

            );

            const slug = document.getElementById(

                'slug'

            );

            if (!nama || !slug) {

                return;

            }

            nama.addEventListener(

                'input',

                function() {

                    slug.value = this.value

                        .toLowerCase()

                        .trim()

                        .replace(/[^a-z0-9]+/g, '-')

                        .replace(/^-+|-+$/g, '');

                }

            );

        }

    );
</script>