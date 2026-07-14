<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : process.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Process Product CRUD
 * VERSION     : 2.1.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';

require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// DELETE (SOFT DELETE)
// ==========================================================

$action = $_GET['action'] ?? '';

if ($action === 'delete') {

    $id_produk = (int) ($_GET['id'] ?? 0);

    if ($id_produk <= 0) {

        $_SESSION['error'] = 'Produk tidak ditemukan.';

        redirect('admin/produk/');
    }

    $updated_by = (int) $_SESSION[SESSION_ADMIN]['id_admin'];

    $sql = "

        UPDATE produk

        SET

            deleted_at = NOW(),

            updated_at = NOW(),

            updated_by = ?

        WHERE

            id_produk = ?

            AND deleted_at IS NULL

    ";

    $stmt = mysqli_prepare($db, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "ii",

        $updated_by,

        $id_produk

    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    $_SESSION['success'] = 'Produk berhasil dihapus.';

    redirect('admin/produk/');
}
// ==========================================================
// HANYA POST
// ==========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect('admin/produk/');
}

// ==========================================================
// DATA
// ==========================================================

$id_produk = (int) ($_POST['id_produk'] ?? 0);

$is_edit = $id_produk > 0;

$nama_produk = trim($_POST['nama_produk'] ?? '');

$slug = trim($_POST['slug'] ?? '');

$kode_produk = trim($_POST['kode_produk'] ?? '');

$sku = trim($_POST['sku'] ?? '');

$barcode = trim($_POST['barcode'] ?? '');

$id_kategori = (int) ($_POST['id_kategori'] ?? 0);

$deskripsi = trim($_POST['deskripsi'] ?? '');

$harga = (float) ($_POST['harga'] ?? 0);

$diskon = (float) ($_POST['diskon'] ?? 0);

$stok = (int) ($_POST['stok'] ?? 0);

$minimum_stok = (int) ($_POST['minimum_stok'] ?? 0);

$satuan = trim($_POST['satuan'] ?? '');

$berat = (float) ($_POST['berat'] ?? 0);

$meta_title = trim($_POST['meta_title'] ?? '');

$meta_description = trim($_POST['meta_description'] ?? '');

$status = trim($_POST['status'] ?? 'draft');

$is_featured = isset($_POST['is_featured']) ? 1 : 0;

// ==========================================================
// VALIDASI
// ==========================================================

$errors = [];

if ($nama_produk === '') {

    $errors[] = 'Nama produk wajib diisi';
}

if ($id_kategori <= 0) {

    $errors[] = 'Kategori belum dipilih';
}

if ($harga < 0) {

    $errors[] = 'Harga tidak valid';
}

if ($stok < 0) {

    $errors[] = 'Stok tidak valid';
}

if (!empty($errors)) {

    $_SESSION['error'] = implode('<br>', $errors);

    redirect(

        $is_edit

            ? "admin/produk/form.php?id={$id_produk}"

            : "admin/produk/form.php"

    );
}

// ==========================================================
// AUTO GENERATE
// ==========================================================

if ($kode_produk === '') {

    $kode_produk = 'PRD-' . date('YmdHis');
}

if ($sku === '') {

    $sku = 'SKU-' . date('YmdHis');
}

if ($slug === '') {

    $slug = strtolower($nama_produk);

    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

    $slug = trim($slug, '-');
}

// ==========================================================
// THUMBNAIL
// ==========================================================

$thumbnail = '';

$thumbnail_lama = '';

// ----------------------------------------------------------
// Ambil thumbnail lama (khusus edit)
// ----------------------------------------------------------

if ($is_edit) {

    $sql = "

        SELECT thumbnail

        FROM produk

        WHERE id_produk = ?

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

    if ($row = mysqli_fetch_assoc($result)) {

        $thumbnail_lama = $row['thumbnail'] ?? '';
    }

    mysqli_stmt_close($stmt);
}

// ----------------------------------------------------------
// Default gunakan thumbnail lama
// ----------------------------------------------------------

$thumbnail = $thumbnail_lama;

// ==========================================================
// DUPLICATE CHECK
// ==========================================================

// ----------------------------------------------------------
// KODE PRODUK (WAJIB UNIK)
// ----------------------------------------------------------

$sql = "

    SELECT id_produk

    FROM produk

    WHERE

        kode_produk = ?

        AND deleted_at IS NULL

";

if ($is_edit) {

    $sql .= " AND id_produk <> ?";
}

$stmt = mysqli_prepare($db, $sql);

if ($is_edit) {

    mysqli_stmt_bind_param(

        $stmt,

        "si",

        $kode_produk,

        $id_produk

    );
} else {

    mysqli_stmt_bind_param(

        $stmt,

        "s",

        $kode_produk

    );
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    mysqli_stmt_close($stmt);

    $_SESSION['error'] = 'Kode Produk sudah digunakan.';

    redirect(

        $is_edit

            ? "admin/produk/form.php?id={$id_produk}"

            : "admin/produk/form.php"

    );
}

mysqli_stmt_close($stmt);

// ----------------------------------------------------------
// SKU (BOLEH KOSONG, JIKA DIISI HARUS UNIK)
// ----------------------------------------------------------

if ($sku !== '') {

    $sql = "

        SELECT id_produk

        FROM produk

        WHERE

            sku = ?

            AND deleted_at IS NULL

    ";

    if ($is_edit) {

        $sql .= " AND id_produk <> ?";
    }

    $stmt = mysqli_prepare($db, $sql);

    if ($is_edit) {

        mysqli_stmt_bind_param(

            $stmt,

            "si",

            $sku,

            $id_produk

        );
    } else {

        mysqli_stmt_bind_param(

            $stmt,

            "s",

            $sku

        );
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {

        mysqli_stmt_close($stmt);

        $_SESSION['error'] = 'SKU sudah digunakan.';

        redirect(

            $is_edit

                ? "admin/produk/form.php?id={$id_produk}"

                : "admin/produk/form.php"

        );
    }

    mysqli_stmt_close($stmt);
}

// ----------------------------------------------------------
// BARCODE (BOLEH KOSONG, JIKA DIISI HARUS UNIK)
// ----------------------------------------------------------

if ($barcode !== '') {

    $sql = "

        SELECT id_produk

        FROM produk

        WHERE

            barcode = ?

            AND deleted_at IS NULL

    ";

    if ($is_edit) {

        $sql .= " AND id_produk <> ?";
    }

    $stmt = mysqli_prepare($db, $sql);

    if ($is_edit) {

        mysqli_stmt_bind_param(

            $stmt,

            "si",

            $barcode,

            $id_produk

        );
    } else {

        mysqli_stmt_bind_param(

            $stmt,

            "s",

            $barcode

        );
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {

        mysqli_stmt_close($stmt);

        $_SESSION['error'] = 'Barcode sudah digunakan.';

        redirect(

            $is_edit

                ? "admin/produk/form.php?id={$id_produk}"

                : "admin/produk/form.php"

        );
    }

    mysqli_stmt_close($stmt);
}

// ==========================================================
// START TRANSACTION
// ==========================================================

mysqli_begin_transaction($db);

// ==========================================================
// UPLOAD THUMBNAIL
// ==========================================================

if (

    isset($_FILES['thumbnail']) &&

    $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE

) {

    // ------------------------------------------
    // Upload Error
    // ------------------------------------------

    if ($_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK) {

        $_SESSION['error'] = 'Terjadi kesalahan saat upload thumbnail.';

        redirect(

            $is_edit

                ? "admin/produk/form.php?id={$id_produk}"

                : "admin/produk/form.php"

        );
    }

    // ------------------------------------------
    // Maksimal 5 MB
    // ------------------------------------------

    if ($_FILES['thumbnail']['size'] > (5 * 1024 * 1024)) {

        $_SESSION['error'] = 'Ukuran thumbnail maksimal 5 MB.';

        redirect(

            $is_edit

                ? "admin/produk/form.php?id={$id_produk}"

                : "admin/produk/form.php"

        );
    }

    // ------------------------------------------
    // Extension
    // ------------------------------------------

    $extension = strtolower(

        pathinfo(

            $_FILES['thumbnail']['name'],

            PATHINFO_EXTENSION

        )

    );

    $allowed = [

        'jpg',

        'jpeg',

        'png',

        'webp'

    ];

    if (!in_array($extension, $allowed, true)) {

        $_SESSION['error'] = 'Format thumbnail harus JPG, PNG atau WEBP.';

        redirect(

            $is_edit

                ? "admin/produk/form.php?id={$id_produk}"

                : "admin/produk/form.php"

        );
    }

    // ------------------------------------------
    // Generate Nama File
    // ------------------------------------------

    $thumbnail =

        date('YmdHis')

        . '_'

        . bin2hex(random_bytes(5))

        . '.'

        . $extension;

    $upload_path = upload_path('produk');

    if (!is_dir($upload_path)) {

        mkdir(

            $upload_path,

            0777,

            true

        );
    }

    // ------------------------------------------
    // Upload File
    // ------------------------------------------

    if (

        move_uploaded_file(

            $_FILES['thumbnail']['tmp_name'],

            $upload_path . '/' . $thumbnail

        )

    ) {
    } else {

        $_SESSION['error'] = 'Gagal mengupload thumbnail.';

        redirect(

            $is_edit

                ? "admin/produk/form.php?id={$id_produk}"

                : "admin/produk/form.php"

        );
    }
}

// ==========================================================
// UPDATE
// ==========================================================

if ($is_edit) {

    $sql = "

    UPDATE produk SET

        kode_produk=?,

        sku=?,

        barcode=?,

        id_kategori=?,

        nama_produk=?,

        slug=?,

        deskripsi=?,

        harga=?,

        diskon=?,

        stok=?,

        minimum_stok=?,

        satuan=?,

        berat=?,

        thumbnail=?,

        meta_title=?,

        meta_description=?,

        is_featured=?,

        status=?,

        updated_by=?,

        updated_at=NOW()

    WHERE

        id_produk=?

    ";

    $stmt = mysqli_prepare($db, $sql);

    $updated_by = $_SESSION[SESSION_ADMIN]['id_admin'];

    mysqli_stmt_bind_param(

        $stmt,

        "sssisssddiisdsssissi",

        $kode_produk,

        $sku,

        $barcode,

        $id_kategori,

        $nama_produk,

        $slug,

        $deskripsi,

        $harga,

        $diskon,

        $stok,

        $minimum_stok,

        $satuan,

        $berat,

        $thumbnail,

        $meta_title,

        $meta_description,

        $is_featured,

        $status,

        $updated_by,

        $id_produk

    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if (!$success) {

        mysqli_rollback($db);

        if (

            !empty($thumbnail) &&

            file_exists(upload_path('produk') . '/' . $thumbnail)

        ) {

            unlink(upload_path('produk') . '/' . $thumbnail);
        }

        $_SESSION['error'] = 'Gagal memperbarui produk.';

        redirect("admin/produk/form.php?id={$id_produk}");
    }
}

// ==========================================================
// INSERT
// ==========================================================

else {

    $sql = "

    INSERT INTO produk(

        kode_produk,

        sku,

        barcode,

        id_kategori,

        nama_produk,

        slug,

        deskripsi,

        harga,

        diskon,

        stok,

        minimum_stok,

        satuan,

        berat,

        thumbnail,

        meta_title,

        meta_description,

        views,

        sold,

        is_featured,

        status,

        created_by,

        created_at

    )

    VALUES(

        ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,

        0,

        0,

        ?,?,?,

        NOW()

    )

    ";

    $stmt = mysqli_prepare($db, $sql);

    $created_by = $_SESSION[SESSION_ADMIN]['id_admin'];

    mysqli_stmt_bind_param(

        $stmt,

        "sssisssddiisdsssiss",

        $kode_produk,

        $sku,

        $barcode,

        $id_kategori,

        $nama_produk,

        $slug,

        $deskripsi,

        $harga,

        $diskon,

        $stok,

        $minimum_stok,

        $satuan,

        $berat,

        $thumbnail,

        $meta_title,

        $meta_description,

        $is_featured,

        $status,

        $created_by

    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if (!$success) {

        mysqli_rollback($db);

        if (

            !empty($thumbnail) &&

            file_exists(upload_path('produk') . '/' . $thumbnail)

        ) {

            unlink(upload_path('produk') . '/' . $thumbnail);
        }

        $_SESSION['error'] = 'Gagal menambahkan produk.';

        redirect("admin/produk/form.php");
    }
}


// ==========================================================
// COMMIT TRANSACTION
// ==========================================================

mysqli_commit($db);

// ==========================================================
// HAPUS THUMBNAIL LAMA
// ==========================================================

if (

    $is_edit &&

    !empty($thumbnail_lama) &&

    $thumbnail !== $thumbnail_lama &&

    !in_array(

        $thumbnail_lama,

        [

            'default-product.png',

            'no-image.png'

        ],

        true

    )

) {

    $old_file =

        upload_path('produk')

        . '/'

        . $thumbnail_lama;

    if (file_exists($old_file)) {

        unlink($old_file);
    }
}

// ==============================   ============================
// FLASH MESSAGE
// ==========================================================

$_SESSION['success'] =

    $is_edit

    ? 'Produk berhasil diperbarui.'

    : 'Produk berhasil ditambahkan.';

// ==========================================================
// REDIRECT
// ==========================================================

redirect('admin/produk/');
