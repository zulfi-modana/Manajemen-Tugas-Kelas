# Manajemen Tugas Kelas (Class Task Tracker)

Aplikasi web **Manajemen Tugas Kelas** merupakan aplikasi berbasis Laravel dan MySQL yang dirancang untuk membantu guru mengelola tugas kelas serta memudahkan siswa dalam melihat dan memantau penyelesaian tugas.

Aplikasi ini memiliki dua jenis pengguna, yaitu guru (*teacher*) dan siswa (*student*), dengan hak akses yang berbeda sesuai perannya.

## Fitur Utama

### 1. Autentikasi dan Hak Akses
- Registrasi dan login pengguna.
- Penyimpanan password menggunakan hashing.
- Dua jenis role pengguna: `teacher` dan `student`.
- Pembatasan akses berdasarkan role pengguna.

### 2. Manajemen Kelas
- Guru dapat membuat dan mengelola kelas.
- Siswa dapat bergabung ke kelas menggunakan ID kelas.
- Pengelolaan keanggotaan kelas menggunakan relasi many-to-many.

### 3. Manajemen Tugas
- Guru dapat membuat, mengubah, dan menghapus tugas.
- Setiap tugas memiliki judul, deskripsi, tenggat waktu (*deadline*), dan kelas terkait.
- Siswa dapat melihat tugas dari kelas yang diikuti.
- Siswa dapat menandai tugas sebagai selesai.
- Siswa tidak memiliki akses untuk mengubah atau menghapus tugas.

### 4. Pencarian dan Penyaringan Tugas
- Pencarian tugas berdasarkan judul.
- Filter tugas berdasarkan status penyelesaian (selesai atau belum selesai).
- Pagination untuk membatasi jumlah tugas yang ditampilkan dalam satu halaman.

## Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| PHP 8.2+ | Bahasa pemrograman backend |
| Laravel 12 | Framework backend |
| MySQL | Sistem manajemen basis data |
| Blade | Template engine untuk antarmuka |
| Bootstrap 5 | Framework styling frontend |
| Eloquent ORM | Pengelolaan interaksi dengan database |

## Persyaratan Instalasi

Pastikan perangkat telah memiliki:
- PHP 8.2 atau lebih baru.
- Composer.
- MySQL.
- Node.js dan npm (jika diperlukan untuk kompilasi aset frontend).
- Git.

## Panduan Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/zulfi-modana/Manajemen-Tugas-Kelas.git
cd Manajemen-Tugas-Kelas
```

### 2. Instal Dependensi

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
copy .env.example .env
```

Kemudian buat application key:

```bash
php artisan key:generate
```

### 4. Konfigurasi Database

Buat database MySQL dengan nama:

```sql
CREATE DATABASE class_task_tracker;
```

Sesuaikan konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=class_task_tracker
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan username dan password dengan konfigurasi MySQL pada perangkat masing-masing.

### 5. Jalankan Database Migration

```bash
php artisan migrate
```

### 6. Jalankan Aplikasi

```bash
php artisan serve
```

Aplikasi dapat diakses melalui browser pada alamat:

http://127.0.0.1:8000

## Akun Pengujian

Akun pengujian dapat dibuat langsung melalui halaman registrasi aplikasi.

| Pengguna | Role | Email |
|---|---|---|
| Guru | teacher | guru@test.com |
| Siswa | student | siswa@test.com |

Password ditentukan saat registrasi dan disimpan dalam bentuk hash. Jika akun tersebut belum tersedia di database, silakan buat melalui halaman registrasi.

## Dokumentasi REST API

Aplikasi menyediakan endpoint REST API untuk autentikasi dan pengelolaan tugas.

### Autentikasi

| Method | Endpoint | Deskripsi |
|---|---|---|
| POST | `/api/register` | Registrasi pengguna baru |
| POST | `/api/login` | Login pengguna |
| POST | `/api/logout` | Logout pengguna yang sedang terautentikasi |

### Manajemen Tugas

| Method | Endpoint | Hak Akses | Deskripsi |
|---|---|---|---|
| GET | `/api/tasks` | Pengguna terautentikasi | Mengambil daftar tugas |
| POST | `/api/tasks` | Guru | Membuat tugas baru |
| PUT | `/api/tasks/{task}` | Guru | Mengubah tugas |
| DELETE | `/api/tasks/{task}` | Guru | Menghapus tugas |
| POST | `/api/tasks/{task}/complete` | Siswa | Menandai tugas sebagai selesai |

**Catatan:** Endpoint API menggunakan autentikasi berbasis session. Endpoint yang dilindungi memerlukan sesi pengguna yang telah terautentikasi.

## Struktur Database

Aplikasi menggunakan basis data relasional dengan tabel utama berikut:

| Tabel | Kegunaan |
|---|---|
| `users` | Menyimpan informasi pengguna dan role |
| `class_rooms` | Menyimpan data kelas dan kepemilikan guru |
| `class_user` | Menghubungkan siswa dengan kelas yang diikuti |
| `tasks` | Menyimpan data tugas yang dibuat guru |
| `task_completions` | Mencatat status penyelesaian tugas oleh siswa |

### Relasi Database

- Satu guru dapat mengelola beberapa kelas.
- Satu kelas dapat memiliki beberapa siswa.
- Satu kelas dapat memiliki beberapa tugas.
- Satu siswa dapat menyelesaikan beberapa tugas.
- Satu tugas dapat memiliki catatan penyelesaian dari beberapa siswa.

## Keputusan Desain

- **Role-based authorization:** Memisahkan hak akses guru dan siswa untuk mencegah perubahan tugas oleh pengguna yang tidak berwenang.
- **Relasi many-to-many:** Memungkinkan siswa mengikuti beberapa kelas dan satu kelas memiliki banyak siswa.
- **Tabel penyelesaian terpisah:** Menggunakan `task_completions` agar setiap siswa memiliki status penyelesaian masing-masing pada setiap tugas.
- **Database migration:** Mengelola struktur database melalui migration agar dapat direproduksi pada lingkungan pengembangan lain.
- **Password hashing:** Melindungi kredensial pengguna dengan tidak menyimpan password dalam bentuk teks biasa.

## Keterbatasan dan Pengembangan Selanjutnya

- Bergabung ke kelas saat ini menggunakan ID kelas, belum menggunakan kode undangan khusus.
- Autentikasi API masih menggunakan session, belum menggunakan token seperti Laravel Sanctum.
- Pengujian otomatis dapat ditambahkan untuk meningkatkan keandalan aplikasi.
- Fitur notifikasi tenggat waktu dan pengelolaan tugas lanjutan dapat dikembangkan berikutnya.

## Lisensi

Proyek ini dikembangkan untuk keperluan pembelajaran dan pemenuhan technical assessment.