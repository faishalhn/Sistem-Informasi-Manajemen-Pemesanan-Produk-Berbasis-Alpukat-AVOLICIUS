-- ==========================================================
-- FILE        : 09_seed.sql
-- PROJECT     : AVOLICIUS
-- DESCRIPTION : Seed Data (Starter Demo)
-- VERSION     : 1.0.0
-- ==========================================================

-- ==========================================================
-- ADMIN
-- Password hash harus disesuaikan dengan password_hash() aplikasi.
-- ==========================================================

INSERT INTO admin
(kode_admin,nama,username,email,password,foto,role,status)
VALUES
('ADM001','Faishal Hakim Nurrahman','faishal','admin@avolicius.id','CHANGE_WITH_PASSWORD_HASH',NULL,'super_admin','aktif'),
('ADM002','Admin Operasional','admin','operator@avolicius.id','CHANGE_WITH_PASSWORD_HASH',NULL,'admin','aktif');

-- ==========================================================
-- SETTING
-- ==========================================================

INSERT INTO setting(setting_key,setting_value,setting_group) VALUES
('website_name','AVOLICIUS','general'),
('tagline','Fresh Avocado Everyday','general'),
('email','admin@avolicius.id','general'),
('phone','081234567890','general'),
('address','Jl. Raya Kediri No.100','general'),
('logo','logo.png','appearance'),
('favicon','favicon.ico','appearance'),
('instagram','https://instagram.com/avolicius','social'),
('facebook','https://facebook.com/avolicius','social'),
('whatsapp','6281234567890','social');

-- ==========================================================
-- KATEGORI
-- ==========================================================

INSERT INTO kategori
(kode_kategori,nama_kategori,slug,warna,urutan,status)
VALUES
('KT001','Alpukat Mentega','alpukat-mentega','#4CAF50',1,'aktif'),
('KT002','Alpukat Hass','alpukat-hass','#8BC34A',2,'aktif'),
('KT003','Alpukat Miki','alpukat-miki','#689F38',3,'aktif'),
('KT004','Alpukat Kendil','alpukat-kendil','#558B2F',4,'aktif'),
('KT005','Paket Hemat','paket-hemat','#33691E',5,'aktif');

-- ==========================================================
-- TESTIMONIAL
-- ==========================================================

INSERT INTO testimonial
(nama,pekerjaan,isi_testimoni,rating,is_featured,status)
VALUES
('Ahmad Fauzi','Guru','Alpukat sangat segar.',5,1,'tampil'),
('Dewi Lestari','Dokter','Pengiriman cepat.',5,1,'tampil'),
('Rina Putri','Karyawan','Packing rapi.',5,0,'tampil'),
('Yoga Pratama','Wiraswasta','Kualitas premium.',5,0,'tampil');

-- ==========================================================
-- CONTOH PRODUK (lanjutkan pola hingga 25 produk)
-- ==========================================================

INSERT INTO produk
(kode_produk,sku,id_kategori,nama_produk,slug,harga,diskon,stok,minimum_stok,satuan,berat,thumbnail,is_featured,status)
VALUES
('PRD001','SKU000001',1,'Alpukat Mentega Premium 1 Kg','alpukat-mentega-premium-1kg',45000,5000,120,5,'kg',1000,'produk1.jpg',1,'aktif'),
('PRD002','SKU000002',2,'Alpukat Hass Grade A','alpukat-hass-grade-a',65000,10000,80,5,'kg',1000,'produk2.jpg',1,'aktif'),
('PRD003','SKU000003',5,'Paket Keluarga','paket-keluarga',120000,15000,40,5,'paket',3000,'produk3.jpg',0,'aktif');

INSERT INTO produk_gambar(id_produk,nama_file,alt_text,urutan,is_thumbnail)
VALUES
(1,'produk1.jpg','Alpukat Mentega Premium',1,1),
(1,'produk1_2.jpg','Alpukat Mentega Premium',2,0),
(2,'produk2.jpg','Alpukat Hass Grade A',1,1),
(3,'produk3.jpg','Paket Keluarga',1,1);

-- ==========================================================
-- CUSTOMER
-- ==========================================================

INSERT INTO pelanggan
(kode_customer,nama,email,password,telepon,alamat,kota,provinsi,status)
VALUES
('CUS001','Ahmad Fauzi','ahmad@mail.com','CHANGE_WITH_PASSWORD_HASH','081111111111','Jl. Mawar 1','Kediri','Jawa Timur','aktif'),
('CUS002','Dewi Lestari','dewi@mail.com','CHANGE_WITH_PASSWORD_HASH','082222222222','Jl. Melati 2','Nganjuk','Jawa Timur','aktif');

-- ==========================================================
-- VOUCHER
-- ==========================================================

INSERT INTO voucher
(kode,nama,tipe,nilai,minimal_belanja,kuota,digunakan,tanggal_mulai,tanggal_selesai,status)
VALUES
('WELCOME10','Voucher Member Baru','persen',10,100000,100,5,'2026-01-01','2026-12-31','aktif'),
('HEMAT20','Voucher Hemat','nominal',20000,150000,50,8,'2026-01-01','2026-12-31','aktif');

-- ==========================================================
-- CONTOH ORDER
-- ==========================================================

INSERT INTO pesanan
(invoice,id_pelanggan,id_voucher,nama_penerima,telepon_penerima,alamat_pengiriman,kota_pengiriman,provinsi_pengiriman,kode_pos_pengiriman,subtotal,ongkir,diskon,total,status)
VALUES
('INV-20260701-000001',1,1,'Ahmad Fauzi','081111111111','Jl. Mawar 1','Kediri','Jawa Timur','64111',90000,10000,9000,91000,'selesai');

INSERT INTO pesanan_detail
(id_pesanan,id_produk,kode_produk,nama_produk,harga,diskon,qty,berat)
VALUES
(1,1,'PRD001','Alpukat Mentega Premium 1 Kg',45000,5000,2,1000);

INSERT INTO pembayaran
(id_pesanan,metode,bank,atas_nama,nomor_referensi,nominal,tanggal_bayar,status)
VALUES
(1,'transfer','BCA','Ahmad Fauzi','TRX000001',91000,'2026-07-01 10:00:00','lunas');

INSERT INTO pengiriman
(id_pesanan,kurir,layanan,nomor_resi,ongkir,tanggal_kirim,tanggal_terima,status)
VALUES
(1,'JNE','REG','JNE123456789',10000,'2026-07-01 15:00:00','2026-07-03 10:00:00','diterima');

INSERT INTO transaksi
(id_pembayaran,kode_transaksi,invoice,total,status)
VALUES
(1,'TRX000001','INV-20260701-000001',91000,'berhasil');

INSERT INTO log_aktivitas
(id_admin,aktivitas)
VALUES
(1,'Login'),
(1,'Menambah Produk'),
(2,'Verifikasi Pembayaran'),
(1,'Logout');

-- CATATAN:
-- Perbanyak pola INSERT di atas hingga mencapai target:
-- 25 produk, 12 pelanggan, 20 pesanan, 15 pembayaran,
-- 15 pengiriman, 15 transaksi, dan 50 log aktivitas.
