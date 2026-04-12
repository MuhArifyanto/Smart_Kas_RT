# Smart Kas RT 🏘️

Smart Kas RT adalah sebuah sistem informasi berbasis web yang dirancang khusus untuk mempermudah pengelolaan keuangan dan iuran di tingkat Rukun Tetangga (RT). Aplikasi ini menyediakan transparansi keuangan kepada warga sekaligus mempermudah pengurus RT dalam mencatat pemasukan, pengeluaran, serta memberikan pengumuman dan pesan secara terpusat.

## ✨ Fitur Utama

Aplikasi ini memiliki 2 hak akses atau peran (Role), yaitu **Admin (Pengurus RT)** dan **Warga**:

### 👨‍💼 Admin (Pengurus RT)
* **Dashboard Kas:** Melihat ringkasan total saldo kas, pemasukan, dan pengeluaran.
* **Manajemen Warga:** Menambah, mengedit, dan menghapus data warga.
* **Manajemen Iuran & Pemasukan:** Mencatat, memvalidasi bukti pembayaran, dan mencetak kwitansi/laporan keuangan bulanan.
* **Manajemen Pengeluaran:** Mencatat setiap pengeluaran atau alokasi dana operasional RT.
* **Sistem Chat/Broadcast:** Berkomunikasi secara real-time dengan seluruh warga atau memberikan pengumuman penting (Notifikasi/Reminder).

### 👥 Warga
* **Dashboard Personal:** Laporan histori pembayaran sendiri dan transparansi total Kas RT.
* **Pembayaran Iuran:** Mengunggah bukti pembayaran iuran bulanan untuk divalidasi oleh pengurus.
* **Fitur Chat:** Menghubungi pengurus RT melalui fitur pesan (chatting) langsung dari dalam aplikasi.
* **Profil Diri:** Manajemen data diri (Profil & Avatar/Foto Warga).

---

## 🛠️ Teknologi yang Digunakan
* **Framework:** [Laravel](https://laravel.com/) (PHP)
* **Frontend:** Blade, CSS (TailwindCSS/Bootstrap), JavaScript (Ajax Ppolling)
* **Database:** MySQL
* **Lainnya:** File Storage untuk manajemen foto profil & bukti pembayaran iuran.

---

## 🏗️ Cara Instalasi (Local Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di lingkungan server lokal Anda.

### 1. Prasyarat Sistem
Pastikan komputer/server Anda telah meng-install:
* PHP (Versi 8.1 ke atas disarankan)
* Composer
* Node.js & npm
* MySQL / XAMPP / Laragon

### 2. Langkah-langkah Instalasi

1. **Clone repository ini**
   ```bash
   git clone https://github.com/UsernameAnda/smart-kas-rt.git
   cd smart-kas-rt
   ```

2. **Install komponen PHP via Composer**
   ```bash
   composer install
   ```

3. **Install komponen Frontend (Tailwind/Vite)**
   ```bash
   npm install
   npm run build
   ```

4. **Kopi & Atur Konfigurasi Environment**
   ```bash
   cp .env.example .env
   ```
   *Buka file `.env` lalu sesuaikan konfigurasi database Anda:*
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database & Seeding (Data Contoh)**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Tautkan Storage (Untuk Upload Gambar)**
   ```bash
   php artisan storage:link
   ```

8. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Buka browser Anda dan akses: `http://localhost:8000`

---

## 📸 Tampilan Aplikasi (Screenshots)
*(Tambahkan gambar screenshot aplikasi Anda di sini setelah di-upload ke GitHub)*

| Dashboard Admin | Halaman Pembayaran Warga |
| :---: | :---: |
| `<img width="1366" height="598" alt="{B7D6E94B-D5DC-4970-BF09-F01E153C5BD9}" src="https://github.com/user-attachments/assets/a2780b02-c86b-4c4d-aff0-515c94296ed5" />
` | `<img width="1366" height="599" alt="{7E3DF00D-3918-49D3-A19A-7D16F5C505BE}" src="https://github.com/user-attachments/assets/8a5c00cf-0dc0-427a-acf6-0b4d21c75316" />
` |

---

## 📄 Lisensi
Project ini bersifat *open-source* dan didistribusikan di bawah Lisensi [MIT](https://opensource.org/licenses/MIT).
