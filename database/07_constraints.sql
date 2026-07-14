-- ==========================================================
-- FILE        : 07_constraints.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Foreign Key Constraints
-- VERSION     : 1.0.0
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ==========================================================
-- PRODUK
-- ==========================================================

ALTER TABLE produk
ADD CONSTRAINT fk_produk_kategori
FOREIGN KEY (id_kategori)
REFERENCES kategori(id_kategori)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE produk
ADD CONSTRAINT fk_produk_created_by
FOREIGN KEY (created_by)
REFERENCES admin(id_admin)
ON UPDATE CASCADE
ON DELETE SET NULL;

ALTER TABLE produk
ADD CONSTRAINT fk_produk_updated_by
FOREIGN KEY (updated_by)
REFERENCES admin(id_admin)
ON UPDATE CASCADE
ON DELETE SET NULL;

-- ==========================================================
-- PRODUK GAMBAR
-- ==========================================================

ALTER TABLE produk_gambar
ADD CONSTRAINT fk_produk_gambar_produk
FOREIGN KEY (id_produk)
REFERENCES produk(id_produk)
ON UPDATE CASCADE
ON DELETE CASCADE;

-- ==========================================================
-- KERANJANG
-- ==========================================================

ALTER TABLE keranjang
ADD CONSTRAINT fk_keranjang_pelanggan
FOREIGN KEY (id_pelanggan)
REFERENCES pelanggan(id_pelanggan)
ON UPDATE CASCADE
ON DELETE CASCADE;

-- ==========================================================
-- KERANJANG DETAIL
-- ==========================================================

ALTER TABLE keranjang_detail
ADD CONSTRAINT fk_keranjang_detail_keranjang
FOREIGN KEY (id_keranjang)
REFERENCES keranjang(id_keranjang)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE keranjang_detail
ADD CONSTRAINT fk_keranjang_detail_produk
FOREIGN KEY (id_produk)
REFERENCES produk(id_produk)
ON UPDATE CASCADE
ON DELETE RESTRICT;

-- ==========================================================
-- PESANAN
-- ==========================================================

ALTER TABLE pesanan
ADD CONSTRAINT fk_pesanan_pelanggan
FOREIGN KEY (id_pelanggan)
REFERENCES pelanggan(id_pelanggan)
ON UPDATE CASCADE
ON DELETE RESTRICT;

ALTER TABLE pesanan
ADD CONSTRAINT fk_pesanan_voucher
FOREIGN KEY (id_voucher)
REFERENCES voucher(id_voucher)
ON UPDATE CASCADE
ON DELETE SET NULL;

-- ==========================================================
-- PESANAN DETAIL
-- ==========================================================

ALTER TABLE pesanan_detail
ADD CONSTRAINT fk_pesanan_detail_pesanan
FOREIGN KEY (id_pesanan)
REFERENCES pesanan(id_pesanan)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE pesanan_detail
ADD CONSTRAINT fk_pesanan_detail_produk
FOREIGN KEY (id_produk)
REFERENCES produk(id_produk)
ON UPDATE CASCADE
ON DELETE RESTRICT;

-- ==========================================================
-- PEMBAYARAN
-- ==========================================================

ALTER TABLE pembayaran
ADD CONSTRAINT fk_pembayaran_pesanan
FOREIGN KEY (id_pesanan)
REFERENCES pesanan(id_pesanan)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE pembayaran
ADD CONSTRAINT fk_pembayaran_verified_by
FOREIGN KEY (verified_by)
REFERENCES admin(id_admin)
ON UPDATE CASCADE
ON DELETE SET NULL;

-- ==========================================================
-- PENGIRIMAN
-- ==========================================================

ALTER TABLE pengiriman
ADD CONSTRAINT fk_pengiriman_pesanan
FOREIGN KEY (id_pesanan)
REFERENCES pesanan(id_pesanan)
ON UPDATE CASCADE
ON DELETE CASCADE;

-- ==========================================================
-- TRANSAKSI
-- ==========================================================

ALTER TABLE transaksi
ADD CONSTRAINT fk_transaksi_pembayaran
FOREIGN KEY (id_pembayaran)
REFERENCES pembayaran(id_pembayaran)
ON UPDATE CASCADE
ON DELETE CASCADE;

-- ==========================================================
-- LOG AKTIVITAS
-- ==========================================================

ALTER TABLE log_aktivitas
ADD CONSTRAINT fk_log_admin
FOREIGN KEY (id_admin)
REFERENCES admin(id_admin)
ON UPDATE CASCADE
ON DELETE RESTRICT;

SET FOREIGN_KEY_CHECKS = 1;
