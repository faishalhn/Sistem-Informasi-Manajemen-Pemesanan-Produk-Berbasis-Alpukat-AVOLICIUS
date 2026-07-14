<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : gallery_process.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Gallery Product Process
 * VERSION     : 2.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';

require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// VALIDASI PRODUK
// ==========================================================

$id_produk = (int) ($_REQUEST['produk'] ?? $_POST['id_produk'] ?? 0);

if ($id_produk <= 0) {

    $_SESSION['error'] = 'Produk tidak ditemukan.';

    redirect('admin/produk/');
}

// ==========================================================
// ACTION
// ==========================================================

$action = $_GET['action'] ?? '';

// ==========================================================
// DELETE GALLERY
// ==========================================================

if ($action === 'delete') {

    $id_gambar = (int) ($_GET['id'] ?? 0);

    if ($id_gambar <= 0) {

        $_SESSION['error'] = 'Gallery tidak ditemukan.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    $stmt = mysqli_prepare(

        $db,

        "

        SELECT

            nama_file,

            is_thumbnail

        FROM produk_gambar

        WHERE

            id_gambar = ?

            AND id_produk = ?

        LIMIT 1

        "

    );

    mysqli_stmt_bind_param(

        $stmt,

        "ii",

        $id_gambar,

        $id_produk

    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $gambar = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$gambar) {

        $_SESSION['error'] = 'Gallery tidak ditemukan.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    mysqli_begin_transaction($db);

    $stmt = mysqli_prepare(

        $db,

        "

        DELETE

        FROM produk_gambar

        WHERE

            id_gambar = ?

            AND id_produk = ?

        "

    );

    mysqli_stmt_bind_param(

        $stmt,

        "ii",

        $id_gambar,

        $id_produk

    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if (!$success) {

        mysqli_rollback($db);

        $_SESSION['error'] = 'Gallery gagal dihapus.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    // ------------------------------------------------------
    // Jika thumbnail dihapus,
    // pilih thumbnail baru
    // ------------------------------------------------------

    if ((int) $gambar['is_thumbnail'] === 1) {

        $stmt = mysqli_prepare(

            $db,

            "

            SELECT

                id_gambar

            FROM produk_gambar

            WHERE

                id_produk = ?

            ORDER BY

                urutan ASC,

                id_gambar ASC

            LIMIT 1

            "

        );

        mysqli_stmt_bind_param(

            $stmt,

            "i",

            $id_produk

        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $next = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        if ($next) {

            $stmt = mysqli_prepare(

                $db,

                "

                UPDATE produk_gambar

                SET

                    is_thumbnail = 1

                WHERE

                    id_gambar = ?

                "

            );

            mysqli_stmt_bind_param(

                $stmt,

                "i",

                $next['id_gambar']

            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_commit($db);

    $file =

        upload_path('produk/gallery')

        . '/'

        . $gambar['nama_file'];

    if (file_exists($file)) {

        if (!unlink($file)) {

            error_log(

                'Gagal menghapus file gallery : ' . $file

            );
        }
    }

    $_SESSION['success'] = 'Gallery berhasil dihapus.';

    redirect("admin/produk/detail.php?id={$id_produk}");
}

// ==========================================================
// SET THUMBNAIL
// ==========================================================

if ($action === 'thumbnail') {

    $id_gambar = (int) ($_GET['id'] ?? 0);

    if ($id_gambar <= 0) {

        $_SESSION['error'] = 'Gallery tidak ditemukan.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    mysqli_begin_transaction($db);

    $stmt = mysqli_prepare(

        $db,

        "

        UPDATE produk_gambar

        SET

            is_thumbnail = 0

        WHERE

            id_produk = ?

        "

    );

    mysqli_stmt_bind_param(

        $stmt,

        "i",

        $id_produk

    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare(

        $db,

        "

        UPDATE produk_gambar

        SET

            is_thumbnail = 1

        WHERE

            id_gambar = ?

            AND id_produk = ?

        "

    );

    mysqli_stmt_bind_param(

        $stmt,

        "ii",

        $id_gambar,

        $id_produk

    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if (!$success) {

        mysqli_rollback($db);

        $_SESSION['error'] = 'Thumbnail gagal diperbarui.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    mysqli_commit($db);

    $_SESSION['success'] = 'Thumbnail berhasil diperbarui.';

    redirect("admin/produk/detail.php?id={$id_produk}");
}
// ==========================================================
// HANYA POST
// ==========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect("admin/produk/detail.php?id={$id_produk}");
}

$action = $_POST['action'] ?? '';

// ==========================================================
// UPLOAD GALLERY
// ==========================================================

if ($action === 'upload') {

    $alt_text = trim($_POST['alt_text'] ?? '');

    $urutan = (int) ($_POST['urutan'] ?? 1);

    if ($urutan < 1) {

        $urutan = 1;
    }

    $is_thumbnail = isset($_POST['is_thumbnail']) ? 1 : 0;

    // ------------------------------------------------------
    // CEK GALLERY PERTAMA
    // ------------------------------------------------------

    $stmt = mysqli_prepare(

        $db,

        "

    SELECT

        COUNT(*)

    FROM produk_gambar

    WHERE

        id_produk = ?

    "

    );

    mysqli_stmt_bind_param(

        $stmt,

        "i",

        $id_produk

    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(

        $stmt,

        $jumlah_gallery

    );

    mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);

    if ($jumlah_gallery === 0) {

        $is_thumbnail = 1;
    }

    // ------------------------------------------------------
    // VALIDASI FILE
    // ------------------------------------------------------

    if (

        !isset($_FILES['gallery']) ||

        $_FILES['gallery']['error'] !== UPLOAD_ERR_OK

    ) {

        $_SESSION['error'] = 'Silakan pilih gambar.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    if ($_FILES['gallery']['size'] > (5 * 1024 * 1024)) {

        $_SESSION['error'] = 'Ukuran gambar maksimal 5 MB.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    $extension = strtolower(

        pathinfo(

            $_FILES['gallery']['name'],

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

        $_SESSION['error'] =

            'Format gambar harus JPG, PNG atau WEBP.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    // ------------------------------------------------------
    // VALIDASI MIME TYPE
    // ------------------------------------------------------

    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    $mime = finfo_file(

        $finfo,

        $_FILES['gallery']['tmp_name']

    );

    finfo_close($finfo);

    $allowedMime = [

        'image/jpeg',

        'image/png',

        'image/webp'

    ];

    if (!in_array($mime, $allowedMime, true)) {

        $_SESSION['error'] =

            'File yang diupload bukan gambar yang valid.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    // ------------------------------------------------------
    // GENERATE FILE
    // ------------------------------------------------------

    $filename =

        date('YmdHis')

        . '_'

        . bin2hex(random_bytes(5))

        . '.'

        . $extension;

    $upload_path = upload_path('produk/gallery');

    if (!is_dir($upload_path)) {

        mkdir(

            $upload_path,

            0777,

            true

        );
    }

    // ------------------------------------------------------
    // UPLOAD FILE
    // ------------------------------------------------------

    if (

        !move_uploaded_file(

            $_FILES['gallery']['tmp_name'],

            $upload_path . '/' . $filename

        )

    ) {

        $_SESSION['error'] =

            'Upload gambar gagal.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    mysqli_begin_transaction($db);

    // ------------------------------------------------------
    // RESET THUMBNAIL
    // ------------------------------------------------------

    if ($is_thumbnail === 1) {

        $stmt = mysqli_prepare(

            $db,

            "

            UPDATE produk_gambar

            SET

                is_thumbnail = 0

            WHERE

                id_produk = ?

            "

        );

        mysqli_stmt_bind_param(

            $stmt,

            "i",

            $id_produk

        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }

    // ------------------------------------------------------
    // INSERT GALLERY
    // ------------------------------------------------------

    $stmt = mysqli_prepare(

        $db,

        "

        INSERT INTO produk_gambar

        (

            id_produk,

            nama_file,

            alt_text,

            urutan,

            is_thumbnail

        )

        VALUES

        (?, ?, ?, ?, ?)

        "

    );

    mysqli_stmt_bind_param(

        $stmt,

        "issii",

        $id_produk,

        $filename,

        $alt_text,

        $urutan,

        $is_thumbnail

    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if (!$success) {

        mysqli_rollback($db);

        $file = $upload_path . '/' . $filename;

        if (file_exists($file)) {

            unlink($file);
        }

        $_SESSION['error'] =

            'Gallery gagal disimpan.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    if (!mysqli_commit($db)) {

        $file = $upload_path . '/' . $filename;

        if (file_exists($file)) {

            unlink($file);
        }

        $_SESSION['error'] = 'Commit database gagal.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    $_SESSION['success'] = 'Gallery berhasil ditambahkan.';

    redirect("admin/produk/detail.php?id={$id_produk}");
}
// ==========================================================
// UPDATE ALT TEXT
// ==========================================================

if ($action === 'update_alt') {

    $id_gambar = (int) ($_POST['id_gambar'] ?? 0);

    $alt_text = trim($_POST['alt_text'] ?? '');

    if ($id_gambar <= 0) {

        $_SESSION['error'] = 'Gallery tidak ditemukan.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    $stmt = mysqli_prepare(

        $db,

        "

        UPDATE produk_gambar

        SET

            alt_text = ?

        WHERE

            id_gambar = ?

            AND id_produk = ?

        "

    );

    mysqli_stmt_bind_param(

        $stmt,

        "sii",

        $alt_text,

        $id_gambar,

        $id_produk

    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if (!$success) {

        $_SESSION['error'] =

            'Alt Text gagal diperbarui.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    $_SESSION['success'] =

        'Alt Text berhasil diperbarui.';

    redirect("admin/produk/detail.php?id={$id_produk}");
}

// ==========================================================
// UPDATE URUTAN
// ==========================================================

if ($action === 'update_order') {

    $id_gambar = (int) ($_POST['id_gambar'] ?? 0);

    $urutan = (int) ($_POST['urutan'] ?? 1);

    if ($id_gambar <= 0) {

        $_SESSION['error'] = 'Gallery tidak ditemukan.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    if ($urutan < 1) {

        $urutan = 1;
    }

    $stmt = mysqli_prepare(

        $db,

        "

        UPDATE produk_gambar

        SET

            urutan = ?

        WHERE

            id_gambar = ?

            AND id_produk = ?

        "

    );

    mysqli_stmt_bind_param(

        $stmt,

        "iii",

        $urutan,

        $id_gambar,

        $id_produk

    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if (!$success) {

        $_SESSION['error'] =

            'Urutan gallery gagal diperbarui.';

        redirect("admin/produk/detail.php?id={$id_produk}");
    }

    $_SESSION['success'] =

        'Urutan gallery berhasil diperbarui.';

    redirect("admin/produk/detail.php?id={$id_produk}");
}

// ==========================================================
// ACTION TIDAK DIKENAL
// ==========================================================

$_SESSION['error'] = 'Aksi tidak valid.';

redirect("admin/produk/detail.php?id={$id_produk}");
