<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : form.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Form Tambah & Edit Produk
 * VERSION     : 2.1.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';

require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// MODE
// ==========================================================

$id_produk = (int) ($_GET['id'] ?? 0);

$is_edit = $id_produk > 0;

$page_title = $is_edit
    ? 'Edit Produk'
    : 'Tambah Produk';

$active_menu = 'produk';

// ==========================================================
// DEFAULT DATA
// ==========================================================

$product = [

    'id_produk' => 0,

    'kode_produk' => '',

    'sku' => '',

    'barcode' => '',

    'id_kategori' => 0,

    'nama_produk' => '',

    'slug' => '',

    'deskripsi' => '',

    'harga' => 0,

    'diskon' => 0,

    'stok' => 0,

    'minimum_stok' => 5,

    'satuan' => 'Kg',

    'berat' => 0,

    'thumbnail' => '',

    'meta_title' => '',

    'meta_description' => '',

    'is_featured' => 0,

    'status' => 'draft',

    'created_at' => null,

    'updated_at' => null,

    'created_by' => null,

    'updated_by' => null

];

// ==========================================================
// LOAD KATEGORI
// ==========================================================

$kategori_list = [];

$sql = "

SELECT

    id_kategori,

    nama_kategori

FROM kategori

WHERE deleted_at IS NULL

ORDER BY urutan ASC,nama_kategori ASC

";

$result = mysqli_query($db, $sql);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $kategori_list[] = $row;
    }
}

// ==========================================================
// EDIT MODE
// ==========================================================

if ($is_edit) {

    $sql = "

    SELECT *

    FROM produk

    WHERE

        id_produk=?

        AND deleted_at IS NULL

    LIMIT 1

    ";

    $stmt = mysqli_prepare($db, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "i",

        $id_produk

    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {

        $data = mysqli_fetch_assoc($result);

        if ($data) {

            $product = $data;
        }
    }

    mysqli_stmt_close($stmt);
}

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
                <!-- ======================================================
PAGE HEADER
======================================================= -->

                <div class="page-header mb-4 py-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h2>

                                <?= $is_edit ? 'Edit Produk' : 'Tambah Produk'; ?>

                            </h2>

                            <p class="text-muted mb-0">

                                Kelola data produk AVOLICIUS

                            </p>

                        </div>

                        <a

                            href="index.php"

                            class="btn btn-outline-secondary">

                            <i class="bi bi-arrow-left"></i>

                            Kembali

                        </a>

                    </div>

                </div>

                <!-- ======================================================
FORM
======================================================= -->

                <form

                    action="process.php"

                    method="POST"

                    enctype="multipart/form-data">

                    <input

                        type="hidden"

                        name="id_produk"

                        value="<?= $product['id_produk']; ?>">

                    <div class="card shadow-sm border-0 mb-4">

                        <div class="card-header bg-white">

                            <h5 class="mb-0">

                                Informasi Produk

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <!-- Nama -->

                                <div class="col-lg-6 mb-3">

                                    <label class="form-label">

                                        Nama Produk

                                        <span class="text-danger">*</span>

                                    </label>

                                    <input

                                        type="text"

                                        name="nama_produk"

                                        id="nama_produk"

                                        class="form-control"

                                        required

                                        value="<?= e($product['nama_produk']); ?>">

                                </div>

                                <!-- Slug -->

                                <div class="col-lg-6 mb-3">

                                    <label class="form-label">

                                        Slug

                                    </label>

                                    <input

                                        type="text"

                                        name="slug"

                                        id="slug"

                                        class="form-control"

                                        readonly

                                        value="<?= e($product['slug']); ?>">

                                </div>

                                <!-- Kode -->

                                <div class="col-lg-4 mb-3">

                                    <label class="form-label">

                                        Kode Produk

                                    </label>

                                    <input

                                        type="text"

                                        name="kode_produk"

                                        class="form-control"

                                        value="<?= e($product['kode_produk']); ?>">

                                </div>

                                <!-- SKU -->

                                <div class="col-lg-4 mb-3">

                                    <label class="form-label">

                                        SKU

                                    </label>

                                    <input

                                        type="text"

                                        name="sku"

                                        class="form-control"

                                        value="<?= e($product['sku']); ?>">

                                </div>

                                <!-- Barcode -->

                                <div class="col-lg-4 mb-3">

                                    <label class="form-label">

                                        Barcode

                                    </label>

                                    <input

                                        type="text"

                                        name="barcode"

                                        class="form-control"

                                        value="<?= e($product['barcode']); ?>">
                                </div>

                                <div class="row">

                                    <!-- ==========================================
         KATEGORI
    =========================================== -->

                                    <div class="col-lg-6 mb-3">

                                        <label class="form-label">

                                            Kategori

                                            <span class="text-danger">*</span>

                                        </label>

                                        <select

                                            name="id_kategori"

                                            class="form-select"

                                            required>

                                            <option value="">

                                                -- Pilih Kategori --

                                            </option>

                                            <?php foreach ($kategori_list as $kategori) : ?>

                                                <option

                                                    value="<?= $kategori['id_kategori']; ?>"

                                                    <?= $product['id_kategori'] == $kategori['id_kategori']
                                                        ? 'selected'
                                                        : ''; ?>>

                                                    <?= e($kategori['nama_kategori']); ?>

                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>

                                    <!-- ==========================================
         STATUS
    =========================================== -->

                                    <div class="col-lg-6 mb-3">

                                        <label class="form-label">

                                            Status

                                        </label>

                                        <select

                                            name="status"

                                            class="form-select">

                                            <option value="draft"
                                                <?= $product['status'] == 'draft' ? 'selected' : ''; ?>>

                                                Draft

                                            </option>

                                            <option value="aktif"
                                                <?= $product['status'] == 'aktif' ? 'selected' : ''; ?>>

                                                Aktif

                                            </option>

                                            <option value="nonaktif"
                                                <?= $product['status'] == 'nonaktif' ? 'selected' : ''; ?>>

                                                Nonaktif

                                            </option>

                                            <option value="habis"
                                                <?= $product['status'] == 'habis' ? 'selected' : ''; ?>>

                                                Habis

                                            </option>

                                        </select>

                                    </div>

                                </div>

                                <!-- ======================================================
HARGA
======================================================= -->

                                <div class="card shadow-sm border-0 mb-4">

                                    <div class="card-header bg-white">

                                        <h5 class="mb-0">

                                            Harga & Stok

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- Harga -->

                                            <div class="col-lg-3 mb-3">

                                                <label class="form-label">

                                                    Harga

                                                </label>

                                                <input

                                                    type="number"

                                                    name="harga"

                                                    min="0"

                                                    class="form-control"

                                                    value="<?= $product['harga']; ?>">

                                            </div>

                                            <!-- Diskon -->

                                            <div class="col-lg-3 mb-3">

                                                <label class="form-label">

                                                    Diskon (%)

                                                </label>

                                                <input

                                                    type="number"

                                                    min="0"

                                                    max="100"

                                                    name="diskon"

                                                    class="form-control"

                                                    value="<?= $product['diskon']; ?>">

                                            </div>

                                            <!-- Stok -->

                                            <div class="col-lg-2 mb-3">

                                                <label class="form-label">

                                                    Stok

                                                </label>

                                                <input

                                                    type="number"

                                                    min="0"

                                                    name="stok"

                                                    class="form-control"

                                                    value="<?= $product['stok']; ?>">

                                            </div>

                                            <!-- Minimum -->

                                            <div class="col-lg-2 mb-3">

                                                <label class="form-label">

                                                    Minimum

                                                </label>

                                                <input

                                                    type="number"

                                                    min="0"

                                                    name="minimum_stok"

                                                    class="form-control"

                                                    value="<?= $product['minimum_stok']; ?>">

                                            </div>

                                            <!-- Satuan -->

                                            <div class="col-lg-2 mb-3">

                                                <label class="form-label">

                                                    Satuan

                                                </label>

                                                <input

                                                    type="text"

                                                    name="satuan"

                                                    class="form-control"

                                                    value="<?= e($product['satuan']); ?>">

                                            </div>

                                        </div>

                                        <div class="row">

                                            <!-- Berat -->

                                            <div class="col-lg-3 mb-3">

                                                <label class="form-label">

                                                    Berat (gram)

                                                </label>

                                                <input

                                                    type="number"

                                                    min="0"

                                                    name="berat"

                                                    class="form-control"

                                                    value="<?= $product['berat']; ?>">

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <!-- ======================================================
THUMBNAIL
======================================================= -->

                                <div class="card shadow-sm border-0 mb-4">

                                    <div class="card-header bg-white">

                                        <h5 class="mb-0">

                                            Thumbnail Produk

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="row align-items-center">

                                            <div class="col-lg-4">

                                                <img

                                                    id="thumbnailPreview"

                                                    src="<?= !empty($product['thumbnail'])
                                                                ? base_url('assets/uploads/produk/' . $product['thumbnail'])
                                                                : base_url('assets/images/no-image.png'); ?>"

                                                    class="img-fluid rounded border"

                                                    style="max-height:220px;object-fit:cover;">

                                            </div>

                                            <div class="col-lg-8">

                                                <label class="form-label">

                                                    Upload Thumbnail

                                                </label>

                                                <input

                                                    type="file"

                                                    id="thumbnail"

                                                    name="thumbnail"

                                                    accept="image/*"

                                                    class="form-control">

                                                <small class="text-muted">

                                                    Format JPG, PNG atau WEBP.

                                                </small>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <!-- ======================================================
SEO
======================================================= -->

                                <div class="card shadow-sm border-0 mb-4">

                                    <div class="card-header bg-white">

                                        <h5 class="mb-0">

                                            SEO

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="mb-3">

                                            <label class="form-label">

                                                Meta Title

                                            </label>

                                            <input

                                                type="text"

                                                name="meta_title"

                                                class="form-control"

                                                maxlength="255"

                                                value="<?= e($product['meta_title']); ?>">

                                        </div>

                                        <div>

                                            <label class="form-label">

                                                Meta Description

                                            </label>

                                            <textarea

                                                name="meta_description"

                                                id="meta_description"

                                                rows="4"

                                                maxlength="160"

                                                class="form-control"><?= e($product['meta_description']); ?></textarea>

                                            <div

                                                class="text-end mt-2">

                                                <small>

                                                    <span id="seoCounter">

                                                        0

                                                    </span>

                                                    /160 karakter

                                                </small>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <!-- ======================================================
DESKRIPSI
======================================================= -->

                                <div class="card shadow-sm border-0 mb-4">

                                    <div class="card-header bg-white">

                                        <h5 class="mb-0">

                                            Deskripsi Produk

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <textarea

                                            name="deskripsi"

                                            rows="8"

                                            class="form-control"

                                            placeholder="Masukkan deskripsi lengkap produk..."><?= e($product['deskripsi']); ?></textarea>

                                    </div>

                                </div>

                                <!-- ======================================================
PENGATURAN
======================================================= -->

                                <div class="card shadow-sm border-0 mb-4">

                                    <div class="card-header bg-white">

                                        <h5 class="mb-0">

                                            Pengaturan Produk

                                        </h5>

                                    </div>

                                    <div class="card-body">

                                        <div class="form-check form-switch">

                                            <input

                                                class="form-check-input"

                                                type="checkbox"

                                                id="is_featured"

                                                name="is_featured"

                                                value="1"

                                                <?= (int)$product['is_featured'] === 1 ? 'checked' : ''; ?>>

                                            <label

                                                class="form-check-label"

                                                for="is_featured">

                                                Jadikan sebagai Produk Featured

                                            </label>

                                        </div>

                                    </div>

                                </div>

                                <?php if ($is_edit) : ?>

                                    <div class="card shadow-sm border-0 mb-4">

                                        <div class="card-header bg-white">

                                            <h5 class="mb-0">

                                                Informasi Data

                                            </h5>

                                        </div>

                                        <div class="card-body">

                                            <div class="row">

                                                <div class="col-md-6">

                                                    <strong>Created By</strong>

                                                    <br>

                                                    <span class="text-muted">

                                                        <?= e((string)$product['created_by']); ?>

                                                    </span>

                                                </div>

                                                <div class="col-md-6">

                                                    <strong>Updated By</strong>

                                                    <br>

                                                    <span class="text-muted">

                                                        <?= e((string)$product['updated_by']); ?>

                                                    </span>

                                                </div>

                                            </div>

                                            <hr>

                                            <div class="row">

                                                <div class="col-md-6">

                                                    <strong>Dibuat</strong>

                                                    <br>

                                                    <span class="text-muted">

                                                        <?= tanggal_indonesia($product['created_at']); ?>

                                                    </span>

                                                </div>

                                                <div class="col-md-6">

                                                    <strong>Diubah</strong>

                                                    <br>

                                                    <span class="text-muted">

                                                        <?= tanggal_indonesia($product['updated_at']); ?>

                                                    </span>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                <?php endif; ?>

                                <!-- ======================================================
BUTTON
======================================================= -->

                                <div class="d-flex justify-content-end gap-2 mb-5">

                                    <a

                                        href="index.php"

                                        class="btn btn-outline-secondary">

                                        <i class="bi bi-x-circle me-2"></i>

                                        Batal

                                    </a>

                                    <button

                                        type="submit"

                                        class="btn btn-success">

                                        <i class="bi bi-check-circle me-2"></i>

                                        <?= $is_edit ? 'Update Produk' : 'Simpan Produk'; ?>

                                    </button>

                                </div>

                </form>

            </div>
            <?php require_once '../../includes/footer.php'; ?>

        </main>


    </div>

</div>



<script>
    /* ==========================================================
AUTO SLUG
========================================================== */

    const namaProduk = document.getElementById("nama_produk");

    const slug = document.getElementById("slug");

    if (namaProduk && slug) {

        namaProduk.addEventListener("keyup", function() {

            slug.value = this.value

                .toLowerCase()

                .trim()

                .replace(/[^\w\s-]/g, "")

                .replace(/\s+/g, "-")

                .replace(/-+/g, "-");

        });

    }

    /* ==========================================================
    THUMBNAIL PREVIEW
    ========================================================== */

    const thumbnail = document.getElementById("thumbnail");

    const preview = document.getElementById("thumbnailPreview");

    if (thumbnail && preview) {

        thumbnail.addEventListener("change", function(e) {

            if (e.target.files.length > 0) {

                preview.src = URL.createObjectURL(e.target.files[0]);

            }

        });

    }

    /* ==========================================================
    SEO COUNTER
    ========================================================== */

    const meta = document.getElementById("meta_description");

    const counter = document.getElementById("seoCounter");

    if (meta && counter) {

        counter.textContent = meta.value.length;

        meta.addEventListener("input", function() {

            counter.textContent = this.value.length;

        });

    }
</script>