# DESIGN DOCUMENT

## AVOLICIUS

### Sistem Informasi Manajemen Pemesanan Produk Berbasis Alpukat

---

## Informasi Proyek

| Keterangan           | Isi                                                                                                 |
| -------------------- | --------------------------------------------------------------------------------------------------- |
| Nama Project         | AVOLICIUS                                                                                           |
| Jenis Aplikasi       | Sistem Informasi Manajemen Pemesanan Produk Berbasis Alpukat                                        |
| Sprint               | Sprint 1                                                                                            |
| Product Lead         | FAISHAL HAKIM NURRAHMAN                                                                             |
| Frontend Development | WAHYU RIZKY PRADIPTA                                                                                |
| Backend Development  | MOHAMMAD YERRY INDRA SETIAWAN                                                                       |
| Repository           | https://github.com/faishalhn/Sistem-Informasi-Manajemen-Pemesanan-Produk-Berbasis-Alpukat-AVOLICIUS |

---

# Deskripsi Aplikasi

AVOLICIUS merupakan aplikasi berbasis web yang digunakan untuk mengelola proses pemesanan produk makanan dan minuman berbahan dasar alpukat secara digital. Sistem dirancang agar pelanggan dapat melakukan pemesanan dengan mudah, sedangkan admin dapat mengelola seluruh aktivitas operasional melalui dashboard.

Aplikasi menerapkan konsep pemisahan Frontend dan Backend sehingga proses pengembangan dapat dilakukan secara kolaboratif menggunakan GitHub.

---

# Tujuan Pengembangan

Tujuan utama pembangunan sistem ini adalah:

- Mempermudah proses pemesanan produk.
- Mengurangi pencatatan manual.
- Mempercepat pelayanan kepada pelanggan.
- Membantu admin mengelola data produk.
- Membantu admin mengelola pesanan pelanggan.
- Menyediakan laporan transaksi yang lebih terstruktur.
- Menjadi media pembelajaran kolaborasi Software Development menggunakan Git dan GitHub.

---

# Pengguna Sistem

## Customer

Customer dapat:

- Melihat Landing Page
- Melihat daftar produk
- Melakukan Login
- Melakukan pemesanan
- Melihat status pesanan

---

## Administrator

Administrator dapat:

- Login ke Dashboard
- Mengelola data produk
- Mengelola kategori produk
- Mengelola pesanan
- Melihat dashboard penjualan
- Mengelola pengguna

---

# Fitur Utama

## Landing Page

- Hero Section
- Tentang AVOLICIUS
- Daftar Produk
- Promo
- Testimoni
- Contact
- Footer

---

## Authentication

- Login Admin
- Logout

---

## Dashboard Admin

- Dashboard
- Statistik Penjualan
- Total Produk
- Total Pesanan
- Total Customer

---

## Product Management

Admin dapat:

- Menambah produk
- Mengubah produk
- Menghapus produk
- Melihat daftar produk

---

## Order Management

Admin dapat:

- Melihat pesanan masuk
- Mengubah status pesanan
- Menyelesaikan transaksi

---

# Struktur Halaman

Landing Page

↓

Login Admin

↓

Dashboard Admin

├── Dashboard

├── Product Management

├── Order Management

├── User Management

└── Logout

---

# Mockup UI

Mockup antarmuka aplikasi dibuat menggunakan Figma.

Link Mockup:

https://www.figma.com/design/Nfllfq7vpvqkjcaiPHwJQH/Untitled

Halaman yang telah dirancang:

- Landing Page
- Login
- Dashboard Admin
- Product Management
- Order Management
- Responsive Mobile Layout

---

# Status Desain Sprint 1

| Halaman            | Status     |
| ------------------ | ---------- |
| Landing Page       | ✅ Selesai |
| Login              | ✅ Selesai |
| Dashboard          | ✅ Selesai |
| Product Management | ✅ Selesai |
| Order Management   | ✅ Selesai |
| Responsive Mobile  | ✅ Selesai |

---

# GitHub Project

Seluruh aktivitas desain telah didaftarkan ke GitHub Project sebagai Issue sehingga setiap anggota tim dapat memonitor progres pengembangan Sprint 1.

Beberapa Issue yang telah dibuat antara lain:

- Frontend UI Dashboard Admin
- Halaman Product Management
- Halaman Web Pemesanan
- Responsive UI Mobile & Tablet
- Setup Database
- API Login
- CRUD Produk
- Order Management
- Payment Gateway

---

# Kolaborasi Tim

## Product Lead

- Menentukan kebutuhan sistem
- Menyusun Sprint Planning
- Mengelola GitHub Project
- Mengoordinasikan Frontend dan Backend

## Frontend Developer

- Mendesain antarmuka
- Membuat Mockup
- Menyiapkan komponen UI

## Backend Developer

- Mendesain database
- Menyiapkan API
- Mengembangkan logika sistem

---

# Catatan Sprint 1

Sprint pertama difokuskan pada penyusunan blueprint sistem, dokumentasi teknis, perancangan database, mockup antarmuka, serta pembagian tugas pengembangan melalui GitHub Project agar proses implementasi pada sprint berikutnya dapat berjalan lebih terstruktur.

---

**Dokumen ini merupakan acuan desain awal (Blueprint Project) AVOLICIUS yang akan digunakan selama proses pengembangan aplikasi.**
