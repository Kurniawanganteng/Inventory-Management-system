# Inventory Management System — Modern

Project ini disusun mengikuti materi:
- Web 4 / Pertemuan 4: PHP Native, relasi database, transaksi stok, keamanan, dashboard.
- Web 5 / Pertemuan 5: migrasi konsep project yang sama ke Laravel (MVC, Migration, Model, Controller, Blade, Eloquent).

## Struktur
- `php-native/` — aplikasi PHP Native yang dapat dijalankan dengan PHP + MySQL.
- `laravel-source/` — file inti untuk dimasukkan ke project Laravel baru. Folder `vendor/` tidak disertakan; install Laravel dengan Composer terlebih dahulu.


Fitur: login/logout, role admin/staff, CRUD kategori/produk/supplier, relasi produk-supplier, transaksi barang masuk/keluar dengan transaksi database, histori stok, pencarian/filter/sorting/pagination produk, dashboard dan laporan stok.

## Menjalankan bagian Laravel
1. Buat project: `composer create-project laravel/laravel inventory-laravel`
2. Salin isi `laravel-source/` ke project Laravel sesuai struktur foldernya.
3. Buat database MySQL `inventory_db`, lalu sesuaikan `.env`.
4. Jalankan `php artisan migrate --seed`.
5. Jalankan `php artisan serve`.
6. Catatan: file Laravel yang disediakan adalah implementasi inti/kerangka migrasi. Autentikasi Laravel perlu dilengkapi middleware/login sesuai versi Laravel yang dipakai; versi ini menyediakan model, migration, seeder, route, controller, dan Blade untuk modul inventory.

## Catatan keamanan
Ini proyek pembelajaran. Sebelum dipakai publik, tambahkan CSRF token untuk semua form, rate limiting login, pengelolaan akun/password yang lebih kuat, dan audit pengujian keamanan.
