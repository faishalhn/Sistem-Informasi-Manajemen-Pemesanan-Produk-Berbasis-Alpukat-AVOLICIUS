-- ==========================================================
-- FILE        : 08_indexes.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Database Performance Index
-- VERSION     : 1.0.0
-- DBMS        : MySQL 8+
-- ==========================================================

-- ==========================================================
-- ADMIN
-- ==========================================================

CREATE INDEX idx_admin_status
ON admin(status);

CREATE INDEX idx_admin_last_login
ON admin(last_login);



-- ==========================================================
-- KATEGORI
-- ==========================================================

CREATE INDEX idx_kategori_status
ON kategori(status);

CREATE INDEX idx_kategori_urutan
ON kategori(urutan);



-- ==========================================================
-- TESTIMONIAL
-- ==========================================================

CREATE INDEX idx_testimonial_status
ON testimonial(status);

CREATE INDEX idx_testimonial_featured
ON testimonial(is_featured);

CREATE INDEX idx_testimonial_urutan
ON testimonial(urutan);



-- ==========================================================
-- PRODUK
-- ==========================================================

CREATE INDEX idx_produk_kategori
ON produk(id_kategori);

CREATE INDEX idx_produk_status
ON produk(status);

CREATE INDEX idx_produk_featured
ON produk(is_featured);

CREATE INDEX idx_produk_views
ON produk(views);

CREATE INDEX idx_produk_sold
ON produk(sold);

CREATE INDEX idx_produk_created_at
ON produk(created_at);



-- ==========================================================
-- PRODUK GAMBAR
-- ==========================================================

CREATE INDEX idx_produk_gambar_produk
ON produk_gambar(id_produk);



-- ==========================================================
-- PELANGGAN
-- ==========================================================

CREATE INDEX idx_pelanggan_status
ON pelanggan(status);

CREATE INDEX idx_pelanggan_last_login
ON pelanggan(last_login);



-- ==========================================================
-- KERANJANG
-- ==========================================================

CREATE INDEX idx_keranjang_pelanggan
ON keranjang(id_pelanggan);



-- ==========================================================
-- KERANJANG DETAIL
-- ==========================================================

CREATE INDEX idx_keranjang_detail_keranjang
ON keranjang_detail(id_keranjang);

CREATE INDEX idx_keranjang_detail_produk
ON keranjang_detail(id_produk);



-- ==========================================================
-- PESANAN
-- ==========================================================

CREATE INDEX idx_pesanan_customer
ON pesanan(id_pelanggan);

CREATE INDEX idx_pesanan_status
ON pesanan(status);

CREATE INDEX idx_pesanan_created
ON pesanan(created_at);

CREATE INDEX idx_pesanan_voucher
ON pesanan(id_voucher);



-- ==========================================================
-- PESANAN DETAIL
-- ==========================================================

CREATE INDEX idx_pesanan_detail_pesanan
ON pesanan_detail(id_pesanan);

CREATE INDEX idx_pesanan_detail_produk
ON pesanan_detail(id_produk);



-- ==========================================================
-- VOUCHER
-- ==========================================================

CREATE INDEX idx_voucher_status
ON voucher(status);

CREATE INDEX idx_voucher_tanggal
ON voucher(tanggal_mulai, tanggal_selesai);



-- ==========================================================
-- PEMBAYARAN
-- ==========================================================

CREATE INDEX idx_pembayaran_pesanan
ON pembayaran(id_pesanan);

CREATE INDEX idx_pembayaran_status
ON pembayaran(status);

CREATE INDEX idx_pembayaran_tanggal
ON pembayaran(tanggal_bayar);

CREATE INDEX idx_pembayaran_verified
ON pembayaran(verified_by);



-- ==========================================================
-- PENGIRIMAN
-- ==========================================================

CREATE INDEX idx_pengiriman_pesanan
ON pengiriman(id_pesanan);

CREATE INDEX idx_pengiriman_status
ON pengiriman(status);

CREATE INDEX idx_pengiriman_resi
ON pengiriman(nomor_resi);



-- ==========================================================
-- TRANSAKSI
-- ==========================================================

CREATE INDEX idx_transaksi_pembayaran
ON transaksi(id_pembayaran);

CREATE INDEX idx_transaksi_invoice
ON transaksi(invoice);



-- ==========================================================
-- LOG AKTIVITAS
-- ==========================================================

CREATE INDEX idx_log_admin
ON log_aktivitas(id_admin);

CREATE INDEX idx_log_created
ON log_aktivitas(created_at);
