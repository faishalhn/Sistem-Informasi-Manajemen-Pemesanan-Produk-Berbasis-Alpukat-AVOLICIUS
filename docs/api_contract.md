# API Contract Documentation

## AVOLICIUS

### Sistem Informasi Manajemen Pemesanan Produk Berbasis Alpukat

---

# Informasi API

| Keterangan     | Isi                    |
| -------------- | ---------------------- |
| API Type       | REST API               |
| Format Data    | JSON                   |
| Authentication | Session Authentication |
| Base URL       | `/api`                 |

---

# Deskripsi

Dokumen ini berisi kontrak API (API Contract) yang menjadi acuan komunikasi antara Frontend dan Backend pada aplikasi AVOLICIUS.

Seluruh endpoint disusun berdasarkan kebutuhan sistem yang telah ditentukan pada Sprint Planning serta menyesuaikan desain antarmuka (UI), struktur database, dan fitur utama aplikasi.

---

# Endpoint API

## 1. Login Administrator

**Endpoint**

```http
POST /api/login
```

### Request

```json
{
  "email": "admin@avolicius.com",
  "password": "password"
}
```

### Response

```json
{
  "success": true,
  "message": "Login berhasil",
  "role": "admin"
}
```

---

## 2. Menampilkan Daftar Produk

**Endpoint**

```http
GET /api/products
```

### Response

```json
[
  {
    "id": 1,
    "product_name": "Alpukat Premium",
    "price": 25000,
    "stock": 50,
    "image": "alpukat.jpg"
  }
]
```

---

## 3. Menambahkan Produk

**Endpoint**

```http
POST /api/products
```

### Request

```json
{
  "category_id": 1,
  "product_name": "Jus Alpukat",
  "description": "Jus Alpukat Segar",
  "price": 18000,
  "stock": 25
}
```

### Response

```json
{
  "success": true,
  "message": "Produk berhasil ditambahkan"
}
```

---

## 4. Membuat Pesanan

**Endpoint**

```http
POST /api/orders
```

### Request

```json
{
  "user_id": 2,
  "products": [
    {
      "product_id": 1,
      "quantity": 2
    }
  ]
}
```

### Response

```json
{
  "success": true,
  "order_id": 15,
  "status": "Pending"
}
```

---

## 5. Menampilkan Detail Pesanan

**Endpoint**

```http
GET /api/orders/{id}
```

### Response

```json
{
  "order_id": 15,
  "customer": "Faishal Hakim",
  "status": "Pending",
  "total_price": 50000
}
```

---

## 6. Proses Pembayaran

**Endpoint**

```http
POST /api/payments
```

### Request

```json
{
  "order_id": 15,
  "payment_method": "Transfer Bank",
  "amount": 50000
}
```

### Response

```json
{
  "success": true,
  "payment_status": "Paid"
}
```

---

# HTTP Status Code

| Status | Keterangan            |
| ------ | --------------------- |
| 200    | OK                    |
| 201    | Created               |
| 400    | Bad Request           |
| 401    | Unauthorized          |
| 404    | Not Found             |
| 500    | Internal Server Error |

---

# Autentikasi

Autentikasi dilakukan menggunakan Session Authentication. Pengguna yang berhasil login akan memperoleh sesi aktif sehingga dapat mengakses fitur sesuai hak aksesnya.

Hak akses yang tersedia:

- Administrator
- Customer

---

# Kesesuaian dengan Sprint Planning

Endpoint API dirancang untuk mendukung fitur-fitur yang telah ditetapkan pada Sprint 1, yaitu:

- Login Administrator
- Dashboard Admin
- Manajemen Produk
- Manajemen Pesanan
- Pembayaran
- Laporan Penjualan

---

# Kesesuaian dengan Database Schema

Kontrak API ini menggunakan struktur tabel yang telah dirancang pada `database_schema.md`, yaitu:

- users
- categories
- products
- orders
- order_details
- payments

---

# Kesesuaian dengan UI Components

Endpoint API digunakan oleh komponen antarmuka yang telah didokumentasikan pada `ui_components.md`, meliputi:

- Login Page
- Dashboard Admin
- Product Management
- Order Management
- Payment Management

---

# Kesimpulan

Dokumen API Contract ini menjadi acuan komunikasi antara Frontend Developer dan Backend Developer selama proses implementasi aplikasi AVOLICIUS. Seluruh endpoint disusun berdasarkan kebutuhan sistem pada Sprint 1 sehingga dapat mendukung proses pengembangan secara konsisten dan terintegrasi.
