# 👟 Shoegaze - Laravel E-Commerce (910 Nineten Catalog)

Website e-commerce khusus produk sepatu **910 Nineten** yang dibangun menggunakan Framework Laravel 10. Proyek ini mencakup fitur manajemen stok, keranjang belanja, integrasi carousel banner, dan sistem checkout.

---

## 🔑 Akun Demo (Credentials)

Gunakan akun di bawah ini untuk menguji coba berbagai level akses pada sistem:

### 👤 Admin
* **Username/Email:** `admin/admin@gmail.com`
* **Password:** `password123`
* **Akses:** Kelola produk, stok, kelola pengguna, dan validasi pembayaran.

### 👮 Petugas / Staff
* **Username/Email:** `petugas/petugas@gmail.com`
* **Password:** `password123`
* **Akses:** Memantau pesanan masuk dan update status pengiriman.

### 🛍️ User / Pembeli
* **Username/Email:** `uje@gmail.com`
* **Password:** `password123`
* **Akses:** Jelajah produk, keranjang belanja, checkout, dan upload bukti bayar.

---

## 🛠️ Fitur Utama
- **Banner Slider:** Carousel interaktif di dashboard menggunakan Swiper.js.
- **Stock Guard:** Validasi otomatis jumlah pesanan agar tidak melebihi stok di keranjang & detail produk.
- **AJAX Checkout:** Penambahan alamat tanpa reload halaman untuk pengalaman user yang lebih mulus.
- **Order Locking:** Fitur upload bukti bayar otomatis terkunci jika pesanan sudah dibatalkan.
- **Auto-Update User Status:** Middleware untuk memantau aktivitas terakhir user (`last_seen`).

---

## 🚀 Cara Instalasi

1.  **Clone Repository:**
    ```bash
    git clone [https://github.com/username/website-laravel-shoegaze.git](https://github.com/username/website-laravel-shoegaze.git)
    ```
2.  **Install Dependencies:**
    ```bash
    composer install
    npm install && npm run dev
    ```
3.  **Setup Environment:**
    * Copy `.env.example` menjadi `.env`
    * Sesuaikan konfigurasi DB_DATABASE di `.env`.
4.  **Migrate & Link Storage:**
    ```bash
    php artisan key:generate
    php artisan migrate
    php artisan storage:link
    ```
5.  **Run Server:**
    ```bash
    php artisan serve
    ```

---

## 📂 Lokasi Aset Penting
- **Foto Produk:** `public/img/banners/product/`
- **Banner Iklan:** `public/img/banners/`
- **Bukti Pembayaran:** `storage/app/public/payment_proofs/`

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
