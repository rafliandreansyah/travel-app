# Travel App Admin (Laravel)

## 📌 Overview
Travel App Admin adalah panel administrasi berbasis Laravel yang dirancang untuk mengelola data perjalanan dan pengguna aplikasi Travel App. Proyek ini dibangun dengan **Filament**, menggunakan **PostgreSQL** sebagai database utama, dan **Supabase** sebagai penyimpanan serta backend tambahan.

## ✨ Features
- **Filament Admin Panel**: UI Admin yang modern dan interaktif.
- **PostgreSQL**: Database relasional yang scalable dan powerful.
- **Supabase**:
  - Penyimpanan media dan file.
  - Autentikasi pengguna jika diperlukan.
- **Manajemen Pengguna** (CRUD User Management).
- **Manajemen Mobil** (CRUD Destination Management).
- **Manajemen Pemesanan dan Transaksi**.

---

## 🚀 Installation
### Prerequisites
- PHP `^8.1`
- Composer
- PostgreSQL
- Node.js & npm (opsional, untuk asset build jika diperlukan)

### 1️⃣ Clone Repository
```bash
git clone https://github.com/your-username/travel-app-admin.git
cd travel-app-admin
```

### 2️⃣ Install Dependencies
```bash
composer install
npm install && npm run dev # Jika menggunakan frontend assets
```

### 3️⃣ Konfigurasi Environment
Duplikasi file `.env.example` menjadi `.env` lalu sesuaikan konfigurasi:

```ini
APP_NAME="Travel App Admin"
APP_ENV=local
APP_KEY=your-app-key
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=travel_db
DB_USERNAME=your_pgsql_user
DB_PASSWORD=your_pgsql_password

SUPABASE_URL=https://your-supabase-url.supabase.co
SUPABASE_KEY=your-supabase-api-key
```

### 4️⃣ Generate App Key & Migrate Database
```bash
php artisan key:generate
php artisan migrate --seed
```

### 5️⃣ Jalankan Server
```bash
php artisan serve
```
Aplikasi sekarang dapat diakses di `http://localhost:8000`.

---

## 🎛️ Filament Admin Panel
Setelah instalasi berhasil, akses panel admin Filament di:
```
http://localhost:8000/admin
```
Gunakan kredensial admin default atau buat akun admin baru:
```bash
php artisan make:filament-user
```

---

## 📦 Deployment
Gunakan **Laravel Forge, Vapor, atau Docker** untuk deployment.

### Database Migration (Production)
```bash
php artisan migrate --force
```

---

## 📜 License
Proyek ini menggunakan lisensi **MIT**.

---

## 📬 Contact
Jika ada pertanyaan atau kontribusi, silakan buat issue atau pull request di repository ini.

### Kerjasama
email: rafliandreansyah957@gmail.com

Happy coding! 🚀

