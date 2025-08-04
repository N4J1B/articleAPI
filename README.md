# Article API

API RESTful yang komprehensif dibangun dengan Laravel 12 untuk mengelola artikel dengan autentikasi JWT.

## 🚀 Features

- **JWT Authentication** - Registrasi pengguna yang aman, login, dan logout
- **User Management** - Manajemen profil pengguna dengan kemampuan update
- **Article CRUD** - Operasi Create, Read, Update, Delete yang lengkap untuk artikel
- **Authorization** - Pengguna hanya dapat memodifikasi artikel mereka sendiri
- **Pagination** - Daftar artikel yang efisien dengan pagination
- **Author Relations** - Artikel termasuk informasi penulis
- **Token Refresh** - Fungsi refresh token JWT
- **Comprehensive Testing** - Test suite lengkap untuk semua endpoint

## 📋 Requirements

- PHP 8.2+
- Laravel 12
- MySQL/SQLite
- Composer
- JWT-Auth Package (tymon/jwt-auth)

## ⚙️ Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/N4J1B/articleAPI
   cd articleAPI
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   # Konfigurasi database Anda di file .env
   php artisan migrate
   ```

5. **JWT setup**
   ```bash
   php artisan jwt:secret
   ```

6. **Start the server**
   ```bash
   php artisan serve
   ```

## 🔧 Configuration

### Database Configuration
Konfigurasi database di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=article_api
DB_USERNAME=username_anda
DB_PASSWORD=password_anda
```

## 🏗️ Project Structure

```
articleAPI/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php      # Endpoint autentikasi
│   │   └── ArticleController.php   # Operasi CRUD artikel
│   └── Models/
│       ├── User.php               # Model user dengan JWT
│       └── Article.php            # Model artikel
├── routes/
│   └── api.php                    # Definisi route API
├── database/migrations/           # Migrasi database
├── tests/                        # File test
├── run_tests.sh                  # Script testing
├── TESTING_GUIDE.md             # Panduan testing manual
└── ARTICLE_API_DOCS.md          # Dokumentasi API detail
```

## 🛣️ API Endpoints

### Authentication Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/` | Pengecekan kesehatan sistem | ❌ |
| POST | `/api/register` | Registrasi pengguna | ❌ |
| POST | `/api/login` | Login pengguna | ❌ |
| POST | `/api/logout` | Logout pengguna | ✅ |

### User Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/user` | Dapatkan profil pengguna saat ini | ✅ |
| PUT | `/api/user` | Update profil pengguna | ✅ |

### Article Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/articles` | Dapatkan semua artikel (dengan pagination) | ✅ |
| POST | `/api/articles` | Buat artikel baru | ✅ |
| GET | `/api/articles/{id}` | Dapatkan artikel tertentu | ✅ |
| PUT | `/api/articles/{id}` | Update artikel (hanya penulis) | ✅ |
| DELETE | `/api/articles/{id}` | Hapus artikel (hanya penulis) | ✅ |
| GET | `/api/my-articles` | Dapatkan artikel pengguna saat ini | ✅ |

## 📝 API Usage Examples

### 1. Health Check
```bash
curl -X GET http://localhost:8000/api/
```

**Response:**
```json
{
  "success": true,
  "message": "Article API is running",
}
```

### 2. User Registration
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Response:**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-08-04T12:00:00.000000Z",
    "updated_at": "2025-08-04T12:00:00.000000Z"
  }
}
```

### 3. User Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

**Response:**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-08-04T12:00:00.000000Z",
    "updated_at": "2025-08-04T12:00:00.000000Z"
  }
}
```

### 4. User Logout
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "success": true,
  "message": "Successfully logged out"
}
```

### 5. Get User Profile
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-08-04T12:00:00.000000Z",
    "updated_at": "2025-08-04T12:00:00.000000Z"
  }
}
```

### 6. Update User Profile
```bash
curl -X PUT http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe Updated",
    "email": "johnupdated@example.com"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "User updated successfully",
  "data": {
    "id": 1,
    "name": "John Doe Updated",
    "email": "johnupdated@example.com",
    "created_at": "2025-08-04T12:00:00.000000Z",
    "updated_at": "2025-08-04T12:05:00.000000Z"
  }
}
```

### 7. Create Article
```bash
curl -X POST http://localhost:8000/api/articles \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Artikel Pertama Saya",
    "content": "Ini adalah konten artikel saya..."
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "Article created successfully",
  "data": {
    "id": 1,
    "title": "Artikel Pertama Saya",
    "content": "Ini adalah konten artikel saya...",
    "author_id": 1,
    "created_at": "2025-08-04T12:00:00.000000Z",
    "updated_at": "2025-08-04T12:00:00.000000Z",
    "author": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

### 8. Get All Articles
```bash
curl -X GET http://localhost:8000/api/articles \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Artikel Pertama Saya",
        "content": "Ini adalah konten artikel saya...",
        "author_id": 1,
        "created_at": "2025-08-04T12:00:00.000000Z",
        "updated_at": "2025-08-04T12:00:00.000000Z",
        "author": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com"
        }
      }
    ],
    "first_page_url": "http://localhost:8000/api/articles?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/articles?page=1",
    "next_page_url": null,
    "path": "http://localhost:8000/api/articles",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
  }
}
```

### 9. Get Specific Article
```bash
curl -X GET http://localhost:8000/api/articles/1 \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Artikel Pertama Saya",
    "content": "Ini adalah konten artikel saya...",
    "author_id": 1,
    "created_at": "2025-08-04T12:00:00.000000Z",
    "updated_at": "2025-08-04T12:00:00.000000Z",
    "author": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

### 10. Update Article
```bash
curl -X PUT http://localhost:8000/api/articles/1 \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Judul Artikel yang Diperbarui",
    "content": "Konten yang diperbarui..."
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "Article updated successfully",
  "data": {
    "id": 1,
    "title": "Judul Artikel yang Diperbarui",
    "content": "Konten yang diperbarui...",
    "author_id": 1,
    "created_at": "2025-08-04T12:00:00.000000Z",
    "updated_at": "2025-08-04T12:10:00.000000Z",
    "author": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

### 11. Delete Article
```bash
curl -X DELETE http://localhost:8000/api/articles/1 \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "success": true,
  "message": "Article deleted successfully"
}
```

### 12. Get My Articles
```bash
curl -X GET http://localhost:8000/api/my-articles \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Artikel Pertama Saya",
        "content": "Ini adalah konten artikel saya...",
        "author_id": 1,
        "created_at": "2025-08-04T12:00:00.000000Z",
        "updated_at": "2025-08-04T12:00:00.000000Z",
        "author": {
          "id": 1,
          "name": "John Doe",
          "email": "john@example.com"
        }
      }
    ],
    "first_page_url": "http://localhost:8000/api/my-articles?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/my-articles?page=1",
    "next_page_url": null,
    "path": "http://localhost:8000/api/my-articles",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
  }
}
```

## 🔐 Authentication

API ini menggunakan JWT (JSON Web Tokens) untuk autentikasi. Sertakan token dalam header Authorization:

```
Authorization: Bearer YOUR_JWT_TOKEN
```

Token dikembalikan dari:
- Registrasi (`/api/register`)
- Login (`/api/login`)

## 📊 Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operasi berhasil",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "error": "Pesan error"
}
```

### Validation Error (422)
```json
{
  "success": false,
  "error": "Field title wajib diisi."
}
```

### Unauthorized (401)
```json
{
  "success" : false,
  "error": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "success": false,
  "error": "Tidak diizinkan. Anda hanya dapat mengupdate artikel Anda sendiri."
}
```

## 📚 Additional Documentation

- [Dokumentasi Laravel](https://laravel.com/docs)
- [Dokumentasi JWT-Auth](https://jwt-auth.readthedocs.io)

---
