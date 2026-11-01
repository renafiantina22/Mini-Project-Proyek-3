# Sistem Pelaporan dan Pemantauan Karhutla

Aplikasi berbasis web untuk membantu proses pelaporan, peninjauan, pemantauan, dan tindak lanjut kejadian Kebakaran Hutan dan Lahan (Karhutla).

## 1. Identitas Mahasiswa

- **Nama:** Rena Fiantina
- **NIM:** 251211028
- **Kelas:** 2A
- **Mata Kuliah:** Proyek 3

## 2. Kebutuhan Environment

Sebelum menjalankan aplikasi, pastikan perangkat telah memiliki perangkat lunak berikut:

| Kebutuhan | Versi yang digunakan                              |
| --------- | ------------------------------------------------- |
| PHP       | 8.3 atau lebih baru, kompatibel dengan Laravel 13 |
| Laravel   | 13                                                |
| Composer  | 2.x                                               |
| MySQL     | 8.x atau versi kompatibel                         |
| Node.js   | Sesuai kebutuhan Vite                             |
| NPM       | Sesuai versi Node.js                              |
| Git       | Versi terbaru                                     |

Perangkat lunak pendukung:

- XAMPP untuk menjalankan Apache dan MySQL.
- Visual Studio Code sebagai code editor.
- Web browser untuk mengakses aplikasi.

## 3. Langkah Instalasi

### 3.1 Clone Repository

Unduh repository melalui terminal menggunakan perintah:

```bash
git clone https://github.com/renafiantina22/Mini-Project-Proyek-3.git
```

Masuk ke direktori aplikasi Laravel:

```bash
cd Mini-Project-Proyek-3/sistem-pelaporan-karhutla
```

Pastikan terminal berada di dalam folder yang memiliki file `artisan`.

### 3.2 Install Dependency

Jalankan perintah berikut untuk memasang dependency PHP dan JavaScript:

```bash
composer install
npm install
```

### 3.3 Konfigurasi Environment

Buat file `.env` berdasarkan file `.env.example`.

Jika menggunakan Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Kemudian buat application key:

```bash
php artisan key:generate
```

### 3.4 Konfigurasi Database

Buka XAMPP Control Panel, kemudian aktifkan MySQL.

Buat database baru melalui phpMyAdmin dengan nama:

```text
karhutla_db
```

Sesuaikan konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=karhutla_db
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan username dan password dengan konfigurasi MySQL pada perangkat masing-masing.

### 3.5 Konfigurasi Storage

Jalankan perintah berikut untuk menghubungkan direktori penyimpanan file agar bukti laporan dapat diakses melalui aplikasi:

```bash
php artisan storage:link
```

## 4. Konfigurasi Database

Aplikasi menggunakan MySQL sebagai sistem manajemen basis data.

Nama database yang digunakan:

```text
karhutla_db
```

Konfigurasi koneksi database dilakukan melalui file `.env` pada direktori utama proyek.

Pastikan:

- Service MySQL sudah berjalan.
- Database `karhutla_db` sudah dibuat.
- Username dan password database sudah sesuai.
- Konfigurasi pada `.env` sudah benar.

## 5. Migration dan Seeding

Setelah konfigurasi database selesai, jalankan perintah berikut untuk membuat tabel-tabel yang dibutuhkan aplikasi:

```bash
php artisan migrate
```

Untuk memasukkan data awal menggunakan seeder:

```bash
php artisan db:seed
```

Seeder digunakan untuk menyiapkan data awal aplikasi, seperti data wilayah dan akun pengguna apabila sudah dikonfigurasikan pada project.

Tabel utama yang digunakan dalam aplikasi:

| Nama Tabel    | Keterangan                                  |
| ------------- | ------------------------------------------- |
| `users`       | Menyimpan data pengguna dan role.           |
| `areas`       | Menyimpan data wilayah pemantauan.          |
| `reports`     | Menyimpan data laporan Karhutla.            |
| `reviews`     | Menyimpan data hasil peninjauan laporan.    |
| `follow_ups`  | Menyimpan data tindak lanjut laporan.       |
| `report_logs` | Menyimpan riwayat perubahan status laporan. |

## 6. Akun Demo Setiap Role

Berikut akun demo yang dapat digunakan untuk mencoba aplikasi berdasarkan masing-masing role:

| Role        | Email                | Password          |
| ----------- | -------------------- | ----------------- |
| Pelapor     | `andi@karhutla.test` | `Pelapor123!`     |
| Relawan     | `sari@karhutla.test` | `Relawan123!`     |
| Koordinator | `budi@karhutla.test` | `Koordinator123!` |

**Hak akses setiap role:**

- **Pelapor:** Membuat, melihat, dan mengubah laporan miliknya selama belum ditinjau.
- **Relawan:** Melihat dan meninjau laporan, memberikan catatan, serta menentukan hasil peninjauan.
- **Koordinator:** Meneruskan laporan kepada pihak terkait, menyelesaikan tindak lanjut, dan melihat rekapitulasi laporan.

Catatan: Akun demo harus tersedia di database sebelum digunakan untuk login. Pada instalasi baru, akun perlu dimasukkan melalui seeder yang sesuai.

## 7. Cara Menjalankan Aplikasi

Setelah seluruh proses instalasi dan konfigurasi selesai, ikuti langkah berikut untuk menjalankan aplikasi.

### 7.1 Jalankan XAMPP

Buka XAMPP Control Panel, kemudian aktifkan MySQL.

### 7.2 Jalankan Server Laravel

Buka terminal pada direktori proyek, kemudian jalankan:

```bash
php artisan serve
```

### 7.3 Jalankan Vite

Buka terminal kedua pada direktori proyek, kemudian jalankan:

```bash
npm run dev
```

Pastikan kedua proses tersebut tetap berjalan selama aplikasi digunakan.

### 7.4 Akses Aplikasi

Buka browser dan masukkan alamat berikut:

```text
http://127.0.0.1:8000
```

### 7.5 Login ke Aplikasi

Gunakan salah satu akun demo yang tersedia pada bagian **Akun Demo Setiap Role** untuk mencoba fitur aplikasi sesuai dengan hak akses masing-masing pengguna.

---

**Repository GitHub:**

[https://github.com/renafiantina22/Mini-Project-Proyek-3](https://github.com/renafiantina22/Mini-Project-Proyek-3)
