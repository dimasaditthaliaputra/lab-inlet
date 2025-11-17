# InletLab - Installation Guide

## 🛠️ Kebutuhan Sistem

Pastikan Kamu telah menginstal perangkat lunak berikut sebelum memulai:

* **Laragon** (sudah termasuk Apache, PHP, dan Git)
* **PostgreSQL** (Database server)
* **Composer** (Dependency manager untuk PHP)

---

## 🚀 Instalasi & Konfigurasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek di lingkungan lokal Kamu menggunakan Laragon.

### 1. Clone Repository

Buka terminal Kamu (bisa menggunakan terminal bawaan Laragon) dan arahkan ke direktori **www** Laragon, kemudian clone project ini.

```bash
# Arahkan ke direktori root Laragon
cd C:\laragon\www

# Clone repository
git clone https://github.com/dimasaditthaliaputra/lab-inlet.git

# Masuk ke direktori proyek
cd InletLab
```

---

### 2. Setup Database

Impor database yang disediakan di dalam folder **/db** ke PostgreSQL Kamu. Kamu bisa menggunakan **pgAdmin** atau **DBeaver**.

Pastikan Kamu membuat database baru dengan nama **db_lab_inlet** (atau nama lain yang akan Kamu gunakan di langkah berikutnya).

---

### 3. Konfigurasi Environment (.env)

Salin file `.env.example` menjadi `.env` baru.

```bash
cp .env.example .env
```

Kemudian buka file `.env` dan sesuaikan konfigurasi PostgreSQL.

```toml
APP_NAME=InletLab
APP_ENV=local
APP_DEBUG=true

...

DB_HOST=localhost
DB_PORT=5432
DB_USER=postgres
DB_PASS=[PASSWORD_POSTGRES_ANDA]
DB_NAME=db_lab_inlet
```

> **Penting:** Ganti `[PASSWORD_POSTGRES_ANDA]` dengan password user postgres Kamu yang sebenarnya.

---

### 4. Install Dependencies

Jalankan perintah berikut untuk mengunduh semua package PHP yang dibutuhkan proyek.

```bash
composer install
```

---

### 5. Konfigurasi Laragon (Virtual Host)

Agar proyek bisa diakses dengan URL mudah seperti `inletlab.test`, aktifkan virtual host otomatis.

1. Buka **Laragon Dashboard**.
2. Klik ikon **⚙️ Preferences**.
3. Centang **Auto create virtual hosts**.
4. Klik **Close**.

---

## 🏃 Menjalankan Proyek

1. Di dashboard Laragon, klik **Start All** atau pastikan Apache berjalan.
2. Laragon akan membuatkan pretty URL otomatis (contoh: `inletlab.test`).
3. Klik kanan ikon Laragon → menu **www** → pilih **InletLab**.
4. Browser akan terbuka dan menampilkan proyek Kamu.

---

Selamat mengerjakan awokwok! 🎉
