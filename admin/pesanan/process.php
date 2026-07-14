<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : process.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Process Pesanan
 * VERSION     : 1.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';
require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// ONLY POST
// ==========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect('admin/pesanan/');
}

// ==========================================================
// ACTION
// ==========================================================

$action = trim($_POST['action'] ?? '');

switch ($action) {

    case 'update_status':

        updateStatusPesanan($db);

        break;

    default:

        $_SESSION['error'] = 'Aksi tidak dikenali.';

        redirect('admin/pesanan/');
}
// ==========================================================
// UPDATE STATUS
// ==========================================================

function updateStatusPesanan(mysqli $db): void
{

    $id_pesanan = (int) ($_POST['id_pesanan'] ?? 0);

    $status = trim($_POST['status'] ?? '');

    $status_valid = [

        'menunggu_pembayaran',

        'diproses',

        'dikirim',

        'selesai',

        'dibatalkan'

    ];

    // ======================================================
    // VALIDASI
    // ======================================================

    if ($id_pesanan <= 0) {

        $_SESSION['error'] = 'Pesanan tidak ditemukan.';

        redirect('admin/pesanan/');
    }

    if (!in_array($status, $status_valid, true)) {

        $_SESSION['error'] = 'Status tidak valid.';

        redirect(

            'admin/pesanan/detail.php?id=' . $id_pesanan

        );
    }

    // ======================================================
    // UPDATE
    // ======================================================

    $sql = "

        UPDATE pesanan

        SET

            status = ?,

            updated_at = NOW()

        WHERE

            id_pesanan = ?

            AND deleted_at IS NULL

    ";

    $stmt = mysqli_prepare(

        $db,

        $sql

    );

    if (!$stmt) {

        $_SESSION['error'] = 'Database error.';

        redirect(

            'admin/pesanan/detail.php?id=' . $id_pesanan

        );
    }

    mysqli_stmt_bind_param(

        $stmt,

        "si",

        $status,

        $id_pesanan

    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    // ======================================================
    // RESULT
    // ======================================================

    if ($success) {

        $_SESSION['success'] =

            'Status pesanan berhasil diperbarui.';
    } else {

        $_SESSION['error'] =

            'Gagal memperbarui status pesanan.';
    }

    redirect(

        'admin/pesanan/detail.php?id=' . $id_pesanan

    );
}
