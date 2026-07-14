-- ==========================================================
-- FILE        : 10_views.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Database Views
-- VERSION     : 1.0.0
-- DBMS        : MySQL 8+
-- ==========================================================

-- ==========================================================
-- VIEW : Dashboard Summary
-- ==========================================================

CREATE OR REPLACE VIEW vw_dashboard_summary AS

SELECT

    (SELECT COUNT(*) FROM produk
        WHERE deleted_at IS NULL) AS total_produk,

    (SELECT COUNT(*) FROM pelanggan
        WHERE deleted_at IS NULL) AS total_customer,

    (SELECT COUNT(*) FROM pesanan
        WHERE deleted_at IS NULL) AS total_pesanan,

    (SELECT COUNT(*) FROM kategori
        WHERE deleted_at IS NULL) AS total_kategori,

    (SELECT IFNULL(SUM(total),0)
        FROM transaksi
        WHERE status='berhasil') AS total_pendapatan;

-- ==========================================================
-- VIEW : Produk Terlaris
-- ==========================================================

CREATE OR REPLACE VIEW vw_produk_terlaris AS

SELECT

    id_produk,
    kode_produk,
    nama_produk,
    sold,
    views,
    harga,
    diskon,
    stok

FROM produk

WHERE deleted_at IS NULL

ORDER BY sold DESC;



-- ==========================================================
-- VIEW : Produk Aktif
-- ==========================================================

CREATE OR REPLACE VIEW vw_produk_aktif AS

SELECT *

FROM produk

WHERE status='aktif'
AND deleted_at IS NULL;



-- ==========================================================
-- VIEW : Customer Aktif
-- ==========================================================

CREATE OR REPLACE VIEW vw_customer_aktif AS

SELECT

id_pelanggan,
kode_customer,
nama,
email,
telepon,
last_login

FROM pelanggan

WHERE status='aktif'
AND deleted_at IS NULL;



-- ==========================================================
-- VIEW : Voucher Aktif
-- ==========================================================

CREATE OR REPLACE VIEW vw_voucher_aktif AS

SELECT *

FROM voucher

WHERE status='aktif';



-- ==========================================================
-- VIEW : Pesanan Terbaru
-- ==========================================================

CREATE OR REPLACE VIEW vw_pesanan_terbaru AS

SELECT

invoice,

nama_penerima,

total,

status,

created_at

FROM pesanan

ORDER BY created_at DESC;



-- ==========================================================
-- VIEW : Pembayaran Lunas
-- ==========================================================

CREATE OR REPLACE VIEW vw_pembayaran_lunas AS

SELECT

id_pembayaran,

id_pesanan,

metode,

nominal,

tanggal_bayar

FROM pembayaran

WHERE status='lunas';



-- ==========================================================
-- VIEW : Pengiriman Aktif
-- ==========================================================

CREATE OR REPLACE VIEW vw_pengiriman_aktif AS

SELECT

id_pengiriman,

id_pesanan,

kurir,

layanan,

nomor_resi,

status

FROM pengiriman

WHERE status IN
('dikemas','dikirim');



-- ==========================================================
-- VIEW : Produk Stok Menipis
-- ==========================================================

CREATE OR REPLACE VIEW vw_produk_stok_minimum AS

SELECT

id_produk,

kode_produk,

nama_produk,

stok,

minimum_stok

FROM produk

WHERE stok<=minimum_stok
AND deleted_at IS NULL;



-- ==========================================================
-- VIEW : Riwayat Login Admin
-- ==========================================================

CREATE OR REPLACE VIEW vw_log_login_admin AS

SELECT

a.nama,

l.aktivitas,

l.ip_address,

l.created_at

FROM log_aktivitas l

INNER JOIN admin a
ON a.id_admin=l.id_admin

ORDER BY l.created_at DESC;