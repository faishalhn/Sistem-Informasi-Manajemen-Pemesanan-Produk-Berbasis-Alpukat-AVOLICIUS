<?php

declare(strict_types=1);

/**
 * ==========================================================
 * FILE        : detail.php
 * PROJECT     : AVOLICIUS
 * DESCRIPTION : Detail Pesanan Administrator
 * VERSION     : 2.0.0
 * AUTHOR      : Kelompok AVOLICIUS
 * ==========================================================
 */

require_once '../../includes/auth_admin.php';
require_once '../../config/init.php';

/** @var mysqli $db */

// ==========================================================
// VALIDASI
// ==========================================================

$id_pesanan = (int) ($_GET['id'] ?? 0);

if ($id_pesanan <= 0) {

    $_SESSION['error'] = 'Pesanan tidak ditemukan.';

    redirect('admin/pesanan/');
}

// ==========================================================
// LOAD PESANAN
// ==========================================================

$sql = "

SELECT

    p.*,

    pl.nama,

    pl.email,

    pl.telepon,

    pb.status AS status_pembayaran

FROM pesanan p

LEFT JOIN pelanggan pl

ON pl.id_pelanggan = p.id_pelanggan

LEFT JOIN pembayaran pb

ON pb.id_pesanan = p.id_pesanan

WHERE

    p.id_pesanan = ?

    AND p.deleted_at IS NULL

LIMIT 1

";

$stmt = mysqli_prepare($db, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_pesanan
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (
    !$result ||
    mysqli_num_rows($result) === 0
) {

    $_SESSION['error'] = 'Pesanan tidak ditemukan.';

    redirect('admin/pesanan/');
}

$order = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// ==========================================================
// LOAD DETAIL PRODUK
// ==========================================================

$sql = "

SELECT

    id_detail,

    kode_produk,

    nama_produk,

    harga,

    diskon,

    qty,

    berat,

    (
        (harga-diskon)
        * qty
    ) AS subtotal

FROM pesanan_detail

WHERE

    id_pesanan = ?

ORDER BY

    id_detail ASC

";

$stmt = mysqli_prepare($db, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id_pesanan
);

mysqli_stmt_execute($stmt);

$productResult = mysqli_stmt_get_result($stmt);

// ==========================================================
// BADGE
// ==========================================================

$badgeStatus = match ($order['status']) {

    'menunggu_pembayaran' => 'warning',

    'diproses' => 'info',

    'dikirim' => 'primary',

    'selesai' => 'success',

    'dibatalkan' => 'danger',

    default => 'secondary'
};

$badgePembayaran = match ($order['status_pembayaran']) {

    'belum_bayar' => 'secondary',

    'menunggu_verifikasi' => 'warning',

    'lunas' => 'success',

    'ditolak' => 'danger',

    default => 'secondary'
};

// ==========================================================
// PAGE
// ==========================================================

$page_title = 'Detail Pesanan';

$active_menu = 'pesanan';

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

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-body">

                        <div class="row align-items-center">

                            <div class="col-lg">

                                <h2 class="fw-bold mb-2">

                                    Detail Pesanan

                                </h2>

                                <p class="text-muted mb-3">

                                    Informasi lengkap transaksi pelanggan.

                                </p>

                                <div class="d-flex flex-wrap gap-2 mb-3">

                                    <span class="badge bg-dark">

                                        <?= e($order['invoice']); ?>

                                    </span>

                                    <span class="badge bg-<?= $badgeStatus; ?>">

                                        <?= ucwords(str_replace('_', ' ', $order['status'])); ?>

                                    </span>

                                    <span class="badge bg-<?= $badgePembayaran; ?>">

                                        <?= ucwords(str_replace('_', ' ', $order['status_pembayaran'])); ?>

                                    </span>

                                </div>

                                <div class="text-muted small">

                                    <i class="bi bi-person me-2"></i>

                                    <?= e($order['nama']); ?>

                                </div>

                                <div class="text-muted small">

                                    <i class="bi bi-calendar me-2"></i>

                                    <?= tanggal_indonesia($order['created_at']); ?>

                                </div>

                            </div>

                            <div class="col-lg-auto text-lg-end">

                                <div class="text-muted">

                                    Total Pembayaran

                                </div>

                                <h2 class="text-success fw-bold mb-3">

                                    <?= rupiah((float)$order['total']); ?>

                                </h2>

                                <div class="d-flex gap-2 justify-content-lg-end">

                                    <a
                                        href="print.php?id=<?= $id_pesanan; ?>"
                                        class="btn btn-success">

                                        <i class="bi bi-printer"></i>

                                        Cetak

                                    </a>

                                    <a
                                        href="index.php"
                                        class="btn btn-outline-secondary">

                                        <i class="bi bi-arrow-left"></i>

                                        Kembali

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- ==========================================
     ORDER INFORMATION
========================================== -->

                <div class="row g-4 mb-4">

                    <!-- =====================================================
         INFORMASI PESANAN
    ====================================================== -->

                    <div class="col-lg-4">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    <i class="bi bi-receipt-cutoff text-success me-2"></i>

                                    Informasi Pesanan

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="mb-3">

                                    <small class="text-muted d-block">

                                        Invoice

                                    </small>

                                    <div class="fw-semibold">

                                        <?= e($order['invoice']); ?>

                                    </div>

                                </div>

                                <div class="mb-3">

                                    <small class="text-muted d-block">

                                        Tanggal Pesanan

                                    </small>

                                    <div>

                                        <?= tanggal_indonesia($order['created_at']); ?>

                                    </div>

                                </div>

                                <div class="mb-3">

                                    <small class="text-muted d-block">

                                        Status Pesanan

                                    </small>

                                    <span class="badge bg-<?= $badgeStatus; ?> px-3 py-2">

                                        <?= ucwords(str_replace('_', ' ', $order['status'])); ?>

                                    </span>

                                </div>

                                <div class="mb-3">

                                    <small class="text-muted d-block">

                                        Status Pembayaran

                                    </small>

                                    <span class="badge bg-<?= $badgePembayaran; ?> px-3 py-2">

                                        <?= ucwords(str_replace('_', ' ', $order['status_pembayaran'])); ?>

                                    </span>

                                </div>

                                <hr>

                                <small class="text-muted d-block">

                                    Total Pembayaran

                                </small>

                                <h4 class="fw-bold text-success mb-0">

                                    <?= rupiah((float)$order['total']); ?>

                                </h4>

                            </div>

                        </div>

                    </div>

                    <!-- =====================================================
         INFORMASI PELANGGAN
    ====================================================== -->

                    <div class="col-lg-8">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    <i class="bi bi-person-circle text-primary me-2"></i>

                                    Informasi Pelanggan

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row g-4">

                                    <div class="col-md-6">

                                        <small class="text-muted d-block">

                                            Nama Pelanggan

                                        </small>

                                        <div class="fw-semibold">

                                            <?= e($order['nama']); ?>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <small class="text-muted d-block">

                                            Email

                                        </small>

                                        <div>

                                            <?= e($order['email']); ?>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <small class="text-muted d-block">

                                            Nomor Telepon

                                        </small>

                                        <div>

                                            <?= e($order['telepon']); ?>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <small class="text-muted d-block">

                                            ID Pelanggan

                                        </small>

                                        <div>

                                            #<?= (int)$order['id_pelanggan']; ?>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <!-- ==========================================
     DETAIL PRODUK
========================================== -->

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">

                            <i class="bi bi-box-seam text-success me-2"></i>

                            Daftar Produk

                        </h5>

                        <span class="badge bg-success">

                            <?= $productResult ? mysqli_num_rows($productResult) : 0; ?>

                            Produk

                        </span>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th width="60">No</th>

                                    <th>Produk</th>

                                    <th class="text-end" width="140">

                                        Harga

                                    </th>

                                    <th class="text-center" width="120">

                                        Diskon

                                    </th>

                                    <th class="text-center" width="80">

                                        Qty

                                    </th>

                                    <th class="text-center" width="100">

                                        Berat

                                    </th>

                                    <th class="text-end" width="170">

                                        Subtotal

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $no = 1;

                                $totalQty = 0;

                                $totalBerat = 0;

                                $grandSubtotal = 0;

                                ?>

                                <?php if ($productResult && mysqli_num_rows($productResult) > 0) : ?>

                                    <?php while ($item = mysqli_fetch_assoc($productResult)) :

                                        $totalQty += (int) $item['qty'];

                                        $totalBerat += ((float) $item['berat']) * ((int) $item['qty']);

                                        $grandSubtotal += (float) $item['subtotal'];

                                    ?>

                                        <tr>

                                            <td>

                                                <?= $no++; ?>

                                            </td>

                                            <td>

                                                <div class="fw-semibold">

                                                    <?= e($item['nama_produk']); ?>

                                                </div>

                                                <small class="text-muted">

                                                    <?= e($item['kode_produk']); ?>

                                                </small>

                                            </td>

                                            <td class="text-end">

                                                <?= rupiah((float) $item['harga']); ?>

                                            </td>

                                            <td class="text-center">

                                                <?php if ((float)$item['diskon'] > 0) : ?>

                                                    <span class="badge bg-danger">

                                                        -<?= rupiah((float)$item['diskon']); ?>

                                                    </span>

                                                <?php else : ?>

                                                    -

                                                <?php endif; ?>

                                            </td>

                                            <td class="text-center">

                                                <span class="badge bg-success">

                                                    <?= number_format((int)$item['qty']); ?>

                                                </span>

                                            </td>

                                            <td class="text-center">

                                                <?= number_format((float)$item['berat'], 0); ?> gr

                                            </td>

                                            <td class="text-end">

                                                <strong class="text-success">

                                                    <?= rupiah((float)$item['subtotal']); ?>

                                                </strong>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                <?php else : ?>

                                    <tr>

                                        <td colspan="7" class="text-center py-5 text-muted">

                                            <i class="bi bi-box fs-1 d-block mb-3"></i>

                                            Tidak ada produk pada pesanan ini.

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                            <?php if ($totalQty > 0) : ?>

                                <tfoot class="table-light">

                                    <tr>

                                        <th colspan="4" class="text-end">

                                            Total

                                        </th>

                                        <th class="text-center">

                                            <?= number_format($totalQty); ?>

                                        </th>

                                        <th class="text-center">

                                            <?= number_format($totalBerat, 0); ?> gr

                                        </th>

                                        <th class="text-end text-success">

                                            <?= rupiah($grandSubtotal); ?>

                                        </th>

                                    </tr>

                                </tfoot>

                            <?php endif; ?>

                        </table>

                    </div>

                </div>

                <?php mysqli_stmt_close($stmt); ?>

                <!-- ==========================================
     PAYMENT SUMMARY & UPDATE STATUS
========================================== -->

                <div class="row g-4 mb-4">

                    <!-- ======================================
         RINGKASAN PEMBAYARAN
    ======================================= -->

                    <div class="col-lg-6">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                                <h5 class="mb-0">

                                    <i class="bi bi-wallet2 text-success me-2"></i>

                                    Ringkasan Pembayaran

                                </h5>

                                <span class="badge bg-<?= $badgePembayaran; ?>">

                                    <?= ucwords(str_replace('_', ' ', $order['status_pembayaran'])); ?>

                                </span>

                            </div>

                            <div class="card-body">

                                <table class="table table-borderless align-middle mb-0">

                                    <tbody>

                                        <tr>

                                            <td class="text-muted">

                                                <i class="bi bi-bag me-2"></i>

                                                Subtotal

                                            </td>

                                            <td class="text-end fw-semibold">

                                                <?= rupiah((float)$order['subtotal']); ?>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td class="text-muted">

                                                <i class="bi bi-truck me-2"></i>

                                                Ongkos Kirim

                                            </td>

                                            <td class="text-end">

                                                <?= rupiah((float)$order['ongkir']); ?>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td class="text-muted">

                                                <i class="bi bi-percent me-2"></i>

                                                Diskon

                                            </td>

                                            <td class="text-end text-danger">

                                                -<?= rupiah((float)$order['diskon']); ?>

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                                <hr>

                                <div class="bg-light rounded-3 p-3">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div>

                                            <small class="text-muted">

                                                Total Pembayaran

                                            </small>

                                            <div class="fw-semibold">

                                                Sudah termasuk ongkir & diskon

                                            </div>

                                        </div>

                                        <div class="text-end">

                                            <h3 class="text-success fw-bold mb-0">

                                                <?= rupiah((float)$order['total']); ?>

                                            </h3>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- ======================================
         UPDATE STATUS
    ======================================= -->

                    <div class="col-lg-6">

                        <div class="card shadow-sm border-0 h-100">

                            <div class="card-header bg-white">

                                <h5 class="mb-0">

                                    <i class="bi bi-arrow-repeat text-primary me-2"></i>

                                    Update Status Pesanan

                                </h5>

                            </div>

                            <div class="card-body">

                                <form
                                    action="process.php"
                                    method="POST">

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="update_status">

                                    <input
                                        type="hidden"
                                        name="id_pesanan"
                                        value="<?= (int)$id_pesanan; ?>">

                                    <div class="mb-3">

                                        <label class="form-label fw-semibold">

                                            Status Pesanan

                                        </label>

                                        <select
                                            name="status"
                                            class="form-select">

                                            <?php

                                            $statusList = [

                                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                                'diproses' => 'Diproses',
                                                'dikirim' => 'Dikirim',
                                                'selesai' => 'Selesai',
                                                'dibatalkan' => 'Dibatalkan'

                                            ];

                                            foreach ($statusList as $value => $label) :

                                            ?>

                                                <option
                                                    value="<?= $value; ?>"
                                                    <?= $order['status'] === $value ? 'selected' : ''; ?>>

                                                    <?= $label; ?>

                                                </option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>

                                    <div class="mb-4">

                                        <label class="form-label fw-semibold">

                                            Catatan Admin

                                        </label>

                                        <textarea
                                            name="catatan"
                                            rows="4"
                                            class="form-control"
                                            placeholder="Tambahkan catatan perubahan status (opsional)..."></textarea>

                                    </div>

                                    <div class="alert alert-light border mb-4">

                                        <i class="bi bi-info-circle text-primary me-2"></i>

                                        Perubahan status akan langsung diterapkan pada pesanan ini.

                                    </div>

                                    <div class="d-grid">

                                        <button
                                            type="submit"
                                            class="btn btn-success">

                                            <i class="bi bi-check-circle me-2"></i>

                                            Simpan Perubahan

                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ==========================================
     ADMIN ACTION
========================================== -->

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <h5 class="mb-0">

                                <i class="bi bi-tools text-primary me-2"></i>

                                Aksi Administrator

                            </h5>

                            <span class="badge bg-dark">

                                #<?= e($order['invoice']); ?>

                            </span>

                        </div>

                    </div>

                    <div class="card-body">

                        <div class="row g-3 mb-4">

                            <div class="col-md-3">

                                <div class="border rounded-3 p-3 h-100">

                                    <small class="text-muted d-block mb-2">

                                        Dibuat Pada

                                    </small>

                                    <strong>

                                        <?= tanggal_indonesia($order['created_at']); ?>

                                    </strong>

                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="border rounded-3 p-3 h-100">

                                    <small class="text-muted d-block mb-2">

                                        Status Pesanan

                                    </small>

                                    <span class="badge bg-<?= $badgeStatus; ?>">

                                        <?= ucwords(str_replace('_', ' ', $order['status'])); ?>

                                    </span>

                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="border rounded-3 p-3 h-100">

                                    <small class="text-muted d-block mb-2">

                                        Status Pembayaran

                                    </small>

                                    <span class="badge bg-<?= $badgePembayaran; ?>">

                                        <?= ucwords(str_replace('_', ' ', $order['status_pembayaran'])); ?>

                                    </span>

                                </div>

                            </div>

                            <div class="col-md-3">

                                <div class="border rounded-3 p-3 h-100">

                                    <small class="text-muted d-block mb-2">

                                        Total Pembayaran

                                    </small>

                                    <strong class="text-success">

                                        <?= rupiah((float)$order['total']); ?>

                                    </strong>

                                </div>

                            </div>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-between flex-wrap gap-2">

                            <div>

                                <a
                                    href="index.php"
                                    class="btn btn-outline-secondary">

                                    <i class="bi bi-arrow-left me-2"></i>

                                    Kembali

                                </a>

                            </div>

                            <div class="d-flex gap-2 flex-wrap">

                                <a
                                    href="print.php?id=<?= $id_pesanan; ?>"
                                    class="btn btn-outline-primary">

                                    <i class="bi bi-printer me-2"></i>

                                    Cetak Invoice

                                </a>

                                <a
                                    href="export.php?id=<?= $id_pesanan; ?>"
                                    class="btn btn-outline-success">

                                    <i class="bi bi-download me-2"></i>

                                    Export

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <?php

            require_once '../../includes/footer.php';

            ?>
        </main>

    </div>

</div>