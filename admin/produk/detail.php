<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : detail.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Detail Produk
 * VERSION     : 3.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';

require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// VALIDASI ID
// ==========================================================

$id_produk = (int) ($_GET['id'] ?? 0);

if ($id_produk <= 0) {

    $_SESSION['error'] = 'Produk tidak ditemukan.';

    redirect('admin/produk/');
}

$page_title = 'Detail Produk';

$active_menu = 'produk';

// ==========================================================
// LOAD DATA
// ==========================================================

$sql = "

SELECT

    p.*,

    k.nama_kategori

FROM produk p

LEFT JOIN kategori k

ON k.id_kategori = p.id_kategori

WHERE

    p.id_produk = ?

    AND p.deleted_at IS NULL

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

$product = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// ==========================================================
// LOAD GALLERY
// ==========================================================

$gallery = [];

$sql = "

SELECT

    id_gambar,

    nama_file,

    alt_text,

    urutan,

    is_thumbnail,

    created_at

FROM produk_gambar

WHERE

    id_produk = ?

ORDER BY

    urutan ASC,

    id_gambar ASC

";

$stmt = mysqli_prepare($db, $sql);

mysqli_stmt_bind_param(

    $stmt,

    "i",

    $id_produk

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {

    $gallery[] = $row;
}

mysqli_stmt_close($stmt);

// ==========================================================
// THUMBNAIL GALLERY
// ==========================================================

$thumbnail = '';

foreach ($gallery as $image) {

    if ((int) $image['is_thumbnail'] === 1) {

        $thumbnail = $image['nama_file'];

        break;
    }
}

// ==========================================================
// NEXT ORDER GALLERY
// ==========================================================

$next_order = 1;

if (!empty($gallery)) {

    $orders = array_column($gallery, 'urutan');

    $next_order = max($orders) + 1;
}

if (!$product) {

    $_SESSION['error'] = 'Produk tidak ditemukan.';

    redirect('admin/produk/');
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

                <!-- ======================================================
PAGE HEADER
====================================================== -->

                <div class="page-header mb-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h2>

                                Detail Produk

                            </h2>

                            <p class="text-muted mb-0">

                                Informasi lengkap produk AVOLICIUS

                            </p>

                        </div>

                        <div>

                            <a

                                href="form.php?id=<?= $product['id_produk']; ?>"

                                class="btn btn-success">

                                <i class="bi bi-pencil-square me-2"></i>

                                Edit Produk

                            </a>

                            <a

                                href="index.php"

                                class="btn btn-outline-secondary ms-2">

                                <i class="bi bi-arrow-left me-2"></i>

                                Kembali

                            </a>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <!-- ======================================================
THUMBNAIL
====================================================== -->

                    <div class="col-lg-4">

                        <div class="card shadow-sm border-0 mb-4">

                            <div class="card-body text-center">

                                <img

                                    src="<?=
                                            !empty($thumbnail)

                                                ? base_url('assets/uploads/produk/gallery/' . $thumbnail)

                                                : (

                                                    !empty($product['thumbnail'])

                                                    ? base_url('assets/uploads/produk/' . $product['thumbnail'])

                                                    : base_url('assets/images/no-image.png')

                                                );

                                            ?>"

                                    class="img-fluid rounded"

                                    style="max-height:320px;object-fit:cover;">
                                <h4 class="mt-4">

                                    <?= e($product['nama_produk']); ?>

                                </h4>

                                <p class="text-muted mb-0">

                                    <?= e($product['nama_kategori']); ?>

                                </p>

                                <?php

                                $status_badge = [

                                    'aktif' => 'success',

                                    'draft' => 'secondary',

                                    'nonaktif' => 'danger',

                                    'habis' => 'warning'

                                ];

                                ?>

                                <span class="badge bg-<?= $status_badge[$product['status']] ?? 'secondary'; ?> mt-3">

                                    <?= ucfirst($product['status']); ?>

                                </span>

                                <?php if ((int)$product['is_featured'] === 1) : ?>

                                    <div class="mt-3">

                                        <span class="badge bg-warning text-dark">

                                            <i class="bi bi-star-fill me-1"></i>

                                            Featured Product

                                        </span>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                    <!-- ======================================================
INFORMASI PRODUK
====================================================== -->

                    <div class="col-lg-8">

                        <div class="card shadow-sm border-0 mb-4">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    Informasi Produk

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <strong>Kode Produk</strong>

                                        <br>

                                        <?= e($product['kode_produk']); ?>

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <strong>SKU</strong>

                                        <br>

                                        <?= e($product['sku']); ?>

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <strong>Barcode</strong>

                                        <br>

                                        <?= e($product['barcode'] ?: '-'); ?>

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <strong>Kategori</strong>

                                        <br>

                                        <?= e($product['nama_kategori']); ?>

                                    </div>
                                    <div class="col-md-6 mb-3">

                                        <strong>Harga</strong>

                                        <br>

                                        <?= rupiah((float) $product['harga']); ?>

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <strong>Diskon</strong>

                                        <br>

                                        <?= (float) $product['diskon']; ?> %

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <strong>Stok</strong>

                                        <br>

                                        <?= (int) $product['stok']; ?>

                                        <?= e($product['satuan']); ?>

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <strong>Minimum Stok</strong>

                                        <br>

                                        <?= (int) $product['minimum_stok']; ?>

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <strong>Berat</strong>

                                        <br>

                                        <?= number_format((float) $product['berat'], 0, ',', '.'); ?>

                                        gram

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <strong>Slug</strong>

                                        <br>

                                        <?= e($product['slug']); ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- ======================================================
STATISTIK
====================================================== -->

                        <div class="row">

                            <div class="col-md-6">

                                <div class="card shadow-sm border-0 mb-4">

                                    <div class="card-body text-center">

                                        <i class="bi bi-eye fs-1 text-success"></i>

                                        <h3 class="mt-3">

                                            <?= number_format((int) $product['views']); ?>

                                        </h3>

                                        <p class="text-muted mb-0">

                                            Total Views

                                        </p>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="card shadow-sm border-0 mb-4">

                                    <div class="card-body text-center">

                                        <i class="bi bi-bag-check fs-1 text-primary"></i>

                                        <h3 class="mt-3">

                                            <?= number_format((int) $product['sold']); ?>

                                        </h3>

                                        <p class="text-muted mb-0">

                                            Total Terjual

                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- ======================================================
DESKRIPSI
====================================================== -->

                        <div class="card shadow-sm border-0 mb-4">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    Deskripsi Produk

                                </h5>

                            </div>

                            <div class="card-body">

                                <?= !empty($product['deskripsi'])

                                    ? nl2br(e($product['deskripsi']))

                                    : '<span class="text-muted">Belum ada deskripsi.</span>'; ?>

                            </div>

                        </div>

                        <!-- ======================================================
GALLERY
====================================================== -->

                        <div class="card shadow-sm border-0 mb-4">

                            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                                <h5 class="mb-0">

                                    Gallery Produk

                                </h5>

                                <button

                                    class="btn btn-success"

                                    data-bs-toggle="modal"

                                    data-bs-target="#galleryModal">

                                    <i class="bi bi-plus-circle me-2"></i>

                                    Tambah Gambar

                                </button>

                            </div>

                            <div class="card-body">

                                <?php if (!empty($gallery)) : ?>

                                    <div class="row">

                                        <?php foreach ($gallery as $image) : ?>

                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

                                                <div class="card h-100 shadow-sm border-0">

                                                    <img

                                                        src="<?= base_url('assets/uploads/produk/gallery/' . $image['nama_file']); ?>"

                                                        onerror="this.src='<?= base_url('assets/images/no-image.png'); ?>';"

                                                        class="card-img-top"

                                                        style="height:220px;object-fit:cover;">

                                                    <div class="card-body">

                                                        <?php if ((int)$image['is_thumbnail'] === 1) : ?>

                                                            <span class="badge bg-warning text-dark mb-2">

                                                                <i class="bi bi-star-fill me-1"></i>

                                                                Thumbnail

                                                            </span>

                                                        <?php endif; ?>

                                                        <form
                                                            action="gallery_process.php"
                                                            method="POST"
                                                            class="mb-2">

                                                            <input
                                                                type="hidden"
                                                                name="action"
                                                                value="update_alt">

                                                            <input
                                                                type="hidden"
                                                                name="id_produk"
                                                                value="<?= $id_produk; ?>">

                                                            <input
                                                                type="hidden"
                                                                name="id_gambar"
                                                                value="<?= $image['id_gambar']; ?>">

                                                            <input
                                                                type="text"
                                                                name="alt_text"
                                                                class="form-control form-control-sm"
                                                                value="<?= e($image['alt_text']); ?>"
                                                                placeholder="Alt Text">

                                                            <button
                                                                type="submit"
                                                                class="btn btn-outline-primary btn-sm w-100 mt-2">

                                                                <i class="bi bi-check-lg me-1"></i>

                                                                Simpan Alt

                                                            </button>

                                                        </form>

                                                        <form

                                                            action="gallery_process.php"

                                                            method="POST"

                                                            class="mb-3">

                                                            <input

                                                                type="hidden"

                                                                name="action"

                                                                value="update_order">

                                                            <input

                                                                type="hidden"

                                                                name="id_produk"

                                                                value="<?= $id_produk; ?>">

                                                            <input

                                                                type="hidden"

                                                                name="id_gambar"

                                                                value="<?= $image['id_gambar']; ?>">

                                                            <label class="small">

                                                                Urutan

                                                            </label>

                                                            <div class="input-group input-group-sm">

                                                                <input

                                                                    type="number"

                                                                    name="urutan"

                                                                    value="<?= $image['urutan']; ?>"

                                                                    class="form-control"

                                                                    min="1">

                                                                <button

                                                                    class="btn btn-outline-success"

                                                                    type="submit">

                                                                    <i class="bi bi-check-lg"></i>

                                                                </button>

                                                            </div>

                                                        </form>
                                                        <p class="small text-muted mb-2">

                                                            ID Gambar :

                                                            <strong>

                                                                #<?= $image['id_gambar']; ?>

                                                            </strong>

                                                        </p>

                                                        <div class="d-grid gap-2">

                                                            <?php if ((int) $image['is_thumbnail'] === 1) : ?>

                                                                <button

                                                                    type="button"

                                                                    class="btn btn-success btn-sm"

                                                                    disabled>

                                                                    <i class="bi bi-star-fill me-1"></i>

                                                                    Thumbnail Utama

                                                                </button>

                                                            <?php else : ?>

                                                                <a

                                                                    href="gallery_process.php?action=thumbnail&id=<?= $image['id_gambar']; ?>&produk=<?= $id_produk; ?>"

                                                                    class="btn btn-outline-success btn-sm">

                                                                    <i class="bi bi-star me-1"></i>

                                                                    Jadikan Thumbnail

                                                                </a>

                                                            <?php endif; ?>

                                                            <a

                                                                href="gallery_process.php?action=delete&id=<?= $image['id_gambar']; ?>&produk=<?= $id_produk; ?>"

                                                                class="btn btn-outline-danger btn-sm"

                                                                onclick="return confirm('Hapus gambar ini?')">

                                                                <i class="bi bi-trash me-1"></i>

                                                                Hapus

                                                            </a>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        <?php endforeach; ?>

                                    </div>

                                <?php else : ?>

                                    <div class="text-center py-5">

                                        <i class="bi bi-images fs-1 text-secondary d-block mb-3"></i>

                                        <h5 class="text-muted">

                                            Belum ada gallery produk

                                        </h5>

                                        <p class="text-muted mb-0">

                                            Silakan upload gambar produk.

                                        </p>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>
                        <!-- ======================================================
                            SEO
                        ====================================================== -->

                        <div class="card shadow-sm border-0 mb-4">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    SEO

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="mb-3">

                                    <strong>Meta Title</strong>

                                    <br>

                                    <?= e($product['meta_title'] ?: '-'); ?>

                                </div>

                                <div>

                                    <strong>Meta Description</strong>

                                    <br>

                                    <?= e($product['meta_description'] ?: '-'); ?>

                                </div>

                            </div>

                        </div>

                        <!-- ======================================================
AUDIT
====================================================== -->

                        <div class="card shadow-sm border-0 mb-4">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    Informasi Data

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <strong>Created By</strong>

                                        <br>

                                        <?= e((string) ($product['updated_by'] ?? '-')); ?>

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <strong>Updated By</strong>

                                        <br>

                                        <?= e((string) ($product['updated_by'] ?? '-')); ?>

                                    </div>

                                    <div class="col-md-6">

                                        <strong>Dibuat Pada</strong>

                                        <br>

                                        <?= !empty($product['created_at'])

                                            ? tanggal_indonesia($product['created_at'])

                                            : '-'; ?>

                                    </div>

                                    <div class="col-md-6">

                                        <strong>Diubah Pada</strong>

                                        <br>

                                        <?= !empty($product['updated_at'])

                                            ? tanggal_indonesia($product['updated_at'])

                                            : '-'; ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <!-- ======================================================
MODAL UPLOAD GALLERY
====================================================== -->

            <div

                class="modal fade"

                id="galleryModal"

                tabindex="-1"

                aria-hidden="true">

                <div class="modal-dialog modal-lg">

                    <div class="modal-content">

                        <form

                            action="gallery_process.php"
                            method="POST"
                            enctype="multipart/form-data">

                            <div class="modal-header">

                                <h5 class="modal-title">

                                    Tambah Gallery Produk

                                </h5>

                                <button

                                    type="button"

                                    class="btn-close"

                                    data-bs-dismiss="modal">

                                </button>

                            </div>

                            <div class="modal-body">

                                <input
                                    type="hidden"
                                    name="action"
                                    value="upload">

                                <input

                                    type="hidden"

                                    name="id_produk"

                                    value="<?= $product['id_produk']; ?>">

                                <!-- Upload -->

                                <div class="mb-4">

                                    <label class="form-label">

                                        Pilih Gambar

                                    </label>

                                    <input

                                        type="file"

                                        id="galleryFile"

                                        name="gallery"

                                        accept="image/*"

                                        class="form-control"

                                        required>

                                </div>

                                <!-- Preview -->

                                <div class="text-center mb-4">

                                    <img

                                        id="galleryPreview"

                                        src="<?= base_url('assets/images/no-image.png'); ?>"

                                        class="img-fluid rounded border"

                                        style="max-height:250px;object-fit:cover;">
                                    <div

                                        id="galleryFileInfo"

                                        class="small text-muted mt-3">

                                        Belum ada file dipilih.

                                    </div>

                                </div>

                                <!-- Alt -->

                                <div class="mb-3">

                                    <label class="form-label">

                                        Alt Text

                                    </label>

                                    <input

                                        type="text"

                                        name="alt_text"

                                        class="form-control"

                                        placeholder="Contoh : Alpukat Mentega Premium">

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

                                        value="<?= $next_order; ?>"

                                        min="1">

                                    <small class="text-muted">

                                        Urutan otomatis mengikuti gambar terakhir.

                                    </small>

                                </div>

                                <!-- Thumbnail -->

                                <div class="form-check form-switch">

                                    <input

                                        class="form-check-input"

                                        type="checkbox"

                                        name="is_thumbnail"

                                        value="1"

                                        id="is_thumbnail">

                                    <label

                                        class="form-check-label"

                                        for="is_thumbnail">

                                        Jadikan Thumbnail Utama

                                    </label>

                                </div>

                            </div>

                            <div class="modal-footer">

                                <button

                                    type="button"

                                    class="btn btn-secondary"

                                    data-bs-dismiss="modal">

                                    Batal

                                </button>

                                <button

                                    type="submit"

                                    class="btn btn-success">

                                    <i class="bi bi-upload me-2"></i>

                                    Upload

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>
            <?php require_once '../../includes/footer.php'; ?>
            <script>
                const galleryInput = document.getElementById('galleryFile');

                const galleryPreview = document.getElementById('galleryPreview');

                const fileInfo = document.getElementById('galleryFileInfo');

                if (galleryInput && galleryPreview) {

                    galleryInput.addEventListener('change', function() {

                        if (this.files.length === 0) {

                            return;

                        }

                        const file = this.files[0];

                        galleryPreview.src = URL.createObjectURL(file);

                        if (fileInfo) {

                            fileInfo.innerHTML =

                                "<strong>Nama File:</strong> " + file.name +

                                "<br><strong>Ukuran:</strong> " +

                                (file.size / 1024).toFixed(2) + " KB";

                        }

                    });

                }
            </script>
        </main>

    </div>

</div>