# AVOLICIUS Architecture Documentation

## Project Name

Sistem Informasi Manajemen Pemesanan Produk F&B Berbasis Alpukat (AVOLICIUS)

## Architecture Pattern

Project AVOLICIUS menggunakan pola arsitektur MVC (Model View Controller).

### Model

Folder:

models/

Tugas:

- Mengelola data aplikasi
- Berinteraksi dengan database
- Menyimpan logika bisnis

### View

Folder:

views/

Tugas:

- Menampilkan antarmuka pengguna
- Dashboard Admin
- Halaman Produk
- Halaman Pemesanan

### Controller

Folder:

controllers/

Tugas:

- Menghubungkan Model dan View
- Mengelola request pengguna
- Menjalankan proses bisnis aplikasi

## Development Workflow

1. Product Lead membuat Issue.
2. Backend Developer bekerja pada branch feat-be-\*.
3. Frontend Developer bekerja pada branch feat-fe-\*.
4. Pull Request dilakukan ke branch development.
5. Product Lead melakukan review dan merge.
6. Branch master hanya digunakan untuk versi stabil.

## Branch Strategy

- master : Production
- development : Integration
- feat-be-\* : Backend Feature
- feat-fe-\* : Frontend Feature

## Team Members

Product Lead:

- faishalhn

Backend Developer:

- rizzp11

Frontend Developer:

- YERRY888
