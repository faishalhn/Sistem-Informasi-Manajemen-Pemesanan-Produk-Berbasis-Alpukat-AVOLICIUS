<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : process.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Category CRUD Process
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';

require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// DELETE
// ==========================================================

$action = $_GET['action'] ?? '';

if ($action === 'delete') {

    $id_kategori = (int) ($_GET['id'] ?? 0);

    if ($id_kategori <= 0) {

        $_SESSION['error'] = 'Kategori tidak ditemukan.';

        redirect('admin/kategori/');
    }

    mysqli_begin_transaction($db);

    $stmt = mysqli_prepare(

        $db,

        "

        UPDATE kategori

        SET

            deleted_at = NOW()

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

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if (!$success) {

        mysqli_rollback($db);

        $_SESSION['error'] =

            'Gagal menghapus kategori.';

        redirect('admin/kategori/');
    }

    mysqli_commit($db);

    $_SESSION['success'] =

        'Kategori berhasil dihapus.';

    redirect('admin/kategori/');
}

// ==========================================================
// HANYA POST
// ==========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect('admin/kategori/');
}
// ==========================================================
// DATA
// ==========================================================

$id_kategori = (int) ($_POST['id_kategori'] ?? 0);

$is_edit = $id_kategori > 0;

$nama_kategori = trim($_POST['nama_kategori'] ?? '');

$slug = trim($_POST['slug'] ?? '');

$deskripsi = trim($_POST['deskripsi'] ?? '');

$kode_kategori = trim($_POST['kode_kategori'] ?? '');

$icon = trim($_POST['icon'] ?? '');

$warna = trim($_POST['warna'] ?? '#198754');

$urutan = (int) ($_POST['urutan'] ?? 1);

$status = trim($_POST['status'] ?? 'aktif');

$user_id = (int) $_SESSION[SESSION_ADMIN]['id_admin'];

// ==========================================================
// VALIDATION
// ==========================================================

if ($nama_kategori === '') {

    $_SESSION['error'] =

        'Nama kategori wajib diisi.';

    redirect(

        $is_edit

            ? "admin/kategori/form.php?id={$id_kategori}"

            : 'admin/kategori/form.php'

    );
}

if ($kode_kategori === '') {

    $_SESSION['error'] =

        'Kode kategori wajib diisi.';

    redirect(

        $is_edit

            ? "admin/kategori/form.php?id={$id_kategori}"

            : 'admin/kategori/form.php'

    );
}

if ($slug === '') {

    $_SESSION['error'] =

        'Slug wajib diisi.';

    redirect(

        $is_edit

            ? "admin/kategori/form.php?id={$id_kategori}"

            : 'admin/kategori/form.php'

    );
}

if ($urutan < 1) {

    $urutan = 1;
}

$allowed_status = [

    'aktif',

    'nonaktif'

];

if (!in_array($status, $allowed_status, true)) {

    $status = 'draft';
}

if ($icon === '') {

    $icon = 'bi-grid';
}

// ==========================================================
// DUPLICATE CHECK
// ==========================================================

$sql = "

SELECT

    id_kategori

FROM kategori

WHERE

    slug = ?

    AND deleted_at IS NULL

";

if ($is_edit) {

    $sql .= "

    AND id_kategori <> ?

    ";
}

$stmt = mysqli_prepare($db, $sql);

if (!$stmt) {

    $_SESSION['error'] =

        'Terjadi kesalahan database.';

    redirect('admin/kategori/');
}

if ($is_edit) {

    mysqli_stmt_bind_param(

        $stmt,

        "si",

        $slug,

        $id_kategori

    );
} else {

    mysqli_stmt_bind_param(

        $stmt,

        "s",

        $slug

    );
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$duplicate = mysqli_num_rows($result) > 0;

mysqli_stmt_close($stmt);

if ($duplicate) {

    $_SESSION['error'] =

        'Slug kategori sudah digunakan.';

    redirect(

        $is_edit

            ? "admin/kategori/form.php?id={$id_kategori}"

            : 'admin/kategori/form.php'

    );
}
// ==========================================================
// SAVE DATA
// ==========================================================

mysqli_begin_transaction($db);

try {

    // ======================================================
    // UPDATE
    // ======================================================

    if ($is_edit) {

        $sql = "

        UPDATE kategori

        SET

        kode_kategori = ?,

        nama_kategori = ?,

        slug = ?,

        warna = ?,

        deskripsi = ?,

        icon = ?,

        urutan = ?,

        status = ?,

        updated_at = NOW()

        WHERE

            id_kategori = ?

        ";

        $stmt = mysqli_prepare($db, $sql);

        if (!$stmt) {

            throw new Exception('Gagal menyiapkan query update.');
        }

        mysqli_stmt_bind_param(

            $stmt,

            "ssssssis",

            $kode_kategori,

            $nama_kategori,

            $slug,

            $warna,

            $deskripsi,

            $icon,

            $urutan,

            $status,

            $id_kategori

        );
    }

    // ======================================================
    // INSERT
    // ======================================================

    else {

        $sql = "

        INSERT INTO kategori
        (
        kode_kategori,
        nama_kategori,
        slug,
        warna,
        deskripsi,
        icon,
        urutan,
        status,
        created_at
        )

       VALUES
        (
        ?, ?, ?, ?, ?, ?, ?, ?, NOW()
        )

        ";

        $stmt = mysqli_prepare($db, $sql);

        if (!$stmt) {

            throw new Exception('Gagal menyiapkan query insert.');
        }

        mysqli_stmt_bind_param(

            $stmt,

            "ssssssis",

            $kode_kategori,

            $nama_kategori,

            $slug,

            $warna,

            $deskripsi,

            $icon,

            $urutan,

            $status

        );
    }

    // ======================================================
    // EXECUTE
    // ======================================================

    if (!mysqli_stmt_execute($stmt)) {

        throw new Exception(

            mysqli_stmt_error($stmt)

        );
    }

    mysqli_stmt_close($stmt);

    mysqli_commit($db);

    $_SESSION['success'] =

        $is_edit

        ? 'Kategori berhasil diperbarui.'

        : 'Kategori berhasil ditambahkan.';

    redirect('admin/kategori/');
} catch (Throwable $e) {

    mysqli_rollback($db);

    if (isset($stmt) && $stmt instanceof mysqli_stmt) {

        mysqli_stmt_close($stmt);
    }

    $_SESSION['error'] =

        'Gagal menyimpan kategori.';

    redirect(

        $is_edit

            ? "admin/kategori/form.php?id={$id_kategori}"

            : 'admin/kategori/form.php'

    );
}
// ==========================================================
// END OF FILE
// ==========================================================

exit;
