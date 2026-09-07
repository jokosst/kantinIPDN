# Kasir API Documentation

## Base URL
```
http://kasir.test/api
```

## Authentication

### Login
**POST** `/login`

Request body:
```json
{
  "username": "admin",
  "password": "password"
}
```

Response:
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "nama": "Admin User",
      "username": "admin",
      "level": "admin"
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz..."
  }
}
```

### Logout
**POST** `/logout`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "success": true,
  "message": "Logout berhasil"
}
```

### Get Current User
**GET** `/me`

Headers:
```
Authorization: Bearer {token}
```

Response:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nama": "Admin User",
    "username": "admin",
    "level": "admin"
  }
}
```

---

## Items Management

### Get All Items
**GET** `/items`

Query parameters:
- `kategori_id` (optional) - Filter by category
- `nama` (optional) - Search by name
- `per_page` (optional) - Items per page (default: 15)

Example:
```
GET /items?kategori_id=1&per_page=20
```

Response:
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id_item": 1,
        "nama": "Kopi Arabika",
        "id_kategori": 1,
        "id_satuan": 1,
        "id_supplier": 1,
        "harga_beli": 10000,
        "harga_jual": 15000,
        "stok": 50
      }
    ],
    "current_page": 1,
    "per_page": 15,
    "total": 25
  }
}
```

### Get Single Item
**GET** `/items/{id}`

Response:
```json
{
  "success": true,
  "data": {
    "id_item": 1,
    "nama": "Kopi Arabika",
    "id_kategori": 1,
    "id_satuan": 1,
    "id_supplier": 1,
    "harga_beli": 10000,
    "harga_jual": 15000,
    "stok": 50
  }
}
```

### Create Item
**POST** `/items`

Request body:
```json
{
  "nama": "Kopi Arabika",
  "id_kategori": 1,
  "id_satuan": 1,
  "id_supplier": 1,
  "harga_beli": 10000,
  "harga_jual": 15000,
  "stok": 50
}
```

Response (201 Created):
```json
{
  "success": true,
  "message": "Item berhasil ditambahkan",
  "data": {
    "id_item": 1,
    "nama": "Kopi Arabika",
    "id_kategori": 1,
    "id_satuan": 1,
    "id_supplier": 1,
    "harga_beli": 10000,
    "harga_jual": 15000,
    "stok": 50
  }
}
```

### Update Item
**PUT** `/items/{id}`

Request body (all fields optional):
```json
{
  "nama": "Kopi Arabika Premium",
  "harga_jual": 20000,
  "stok": 60
}
```

Response:
```json
{
  "success": true,
  "message": "Item berhasil diupdate",
  "data": { ... }
}
```

### Delete Item
**DELETE** `/items/{id}`

Response:
```json
{
  "success": true,
  "message": "Item berhasil dihapus"
}
```

### Get Low Stock Items
**GET** `/items/low-stock`

Query parameters:
- `threshold` (optional) - Stock threshold (default: 10)

Response:
```json
{
  "success": true,
  "data": {
    "data": [ ... ],
    "current_page": 1,
    "per_page": 15,
    "total": 5
  }
}
```

---

## Categories Management

### Get All Categories
**GET** `/kategoris`

Response:
```json
{
  "success": true,
  "data": [
    {
      "id_kategori": 1,
      "nama": "Minuman"
    }
  ]
}
```

### Get Single Category
**GET** `/kategoris/{id}`

### Create Category
**POST** `/kategoris`

Request body:
```json
{
  "nama": "Minuman"
}
```

### Update Category
**PUT** `/kategoris/{id}`

Request body:
```json
{
  "nama": "Minuman Panas"
}
```

### Delete Category
**DELETE** `/kategoris/{id}`

---

## Suppliers Management

### Get All Suppliers
**GET** `/suppliers`

Query parameters:
- `nama` (optional) - Search by name
- `per_page` (optional) - Items per page (default: 15)

### Get Single Supplier
**GET** `/suppliers/{id}`

### Create Supplier
**POST** `/suppliers`

Request body:
```json
{
  "nama": "PT Kopi Nusantara",
  "kontak": "Budi",
  "alamat": "Jl. Merdeka No. 123",
  "no_telepon": "081234567890",
  "email": "info@kofinusantara.com"
}
```

### Update Supplier
**PUT** `/suppliers/{id}`

Request body (all fields optional):
```json
{
  "nama": "PT Kopi Nusantara Premium",
  "no_telepon": "081234567891"
}
```

### Delete Supplier
**DELETE** `/suppliers/{id}`

---

## Transactions Management

### Get All Transactions
**GET** `/transaksis`

Query parameters:
- `start_date` (optional) - Start date (YYYY-MM-DD)
- `end_date` (optional) - End date (YYYY-MM-DD)
- `id_user` (optional) - Filter by user
- `per_page` (optional) - Items per page (default: 15)

Example:
```
GET /transaksis?start_date=2025-01-01&end_date=2025-01-31
```

### Get Single Transaction
**GET** `/transaksis/{id}`

### Create Transaction
**POST** `/transaksis`

Request body:
```json
{
  "total": 150000,
  "id_user": 1,
  "tanggal": "2025-01-20"
}
```

### Update Transaction
**PUT** `/transaksis/{id}`

Request body:
```json
{
  "total": 160000
}
```

### Delete Transaction
**DELETE** `/transaksis/{id}`

### Get Transaction Summary
**GET** `/transaksis/summary?start_date=2025-01-01&end_date=2025-01-31`

Response:
```json
{
  "success": true,
  "data": [
    {
      "tanggal": "2025-01-20",
      "jumlah_transaksi": 15,
      "total_penjualan": 2250000
    }
  ]
}
```

### Get Daily Report
**GET** `/transaksis/report/daily?date=2025-01-20`

Query parameters:
- `date` (optional) - Date to report (default: today, format: YYYY-MM-DD)

Response:
```json
{
  "success": true,
  "data": {
    "tanggal": "2025-01-20",
    "jumlah_transaksi": 15,
    "total_penjualan": 2250000
  }
}
```

---

## Users Management

### Get All Users
**GET** `/users`

Response:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nama": "Admin User",
      "username": "admin",
      "level": "admin"
    }
  ]
}
```

### Get Single User
**GET** `/users/{id}`

### Create User
**POST** `/users`

Request body:
```json
{
  "nama": "Kasir Baru",
  "username": "kasir_baru",
  "password": "password123",
  "password_confirmation": "password123",
  "level": "cashier"
}
```

Available levels: `admin`, `cashier`, `manager`

### Update User
**PUT** `/users/{id}`

Request body (all fields optional):
```json
{
  "nama": "Kasir Baru Updated",
  "password": "newpassword123",
  "password_confirmation": "newpassword123",
  "level": "manager"
}
```

### Delete User
**DELETE** `/users/{id}`

Note: Cannot delete the last admin user.

---

## Error Handling

### Validation Error
Status: 422

```json
{
  "message": "Validation error",
  "errors": {
    "nama": ["The nama field is required."],
    "harga_jual": ["The harga jual must be a number."]
  }
}
```

### Authentication Error
Status: 401

```json
{
  "success": false,
  "message": "Username atau password salah"
}
```

### Not Found Error
Status: 404

```json
{
  "message": "Not found"
}
```

---

## Important Notes

1. All protected endpoints require `Authorization: Bearer {token}` header
2. All dates should be in format `YYYY-MM-DD`
3. All monetary values are in Rupiah (IDR)
4. Pagination uses `per_page` query parameter (default: 15)
5. Token is obtained from login endpoint and valid for session duration

---

## Testing with cURL

### Login
```bash
curl -X POST http://kasir.test/api/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
```

### Get Items
```bash
curl -X GET http://kasir.test/api/items \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Create Item
```bash
curl -X POST http://kasir.test/api/items \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Kopi Arabika",
    "id_kategori": 1,
    "id_satuan": 1,
    "id_supplier": 1,
    "harga_beli": 10000,
    "harga_jual": 15000,
    "stok": 50
  }'
```

---

## API Version
v1.0

Last Updated: January 20, 2025
