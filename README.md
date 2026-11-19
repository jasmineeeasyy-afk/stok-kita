# STOK KITA — PHP Native + MySQL

## Persyaratan
- XAMPP (Apache dan MySQL)
- PHP 8.0+ (disarankan PHP 8.1+)
- Browser modern

## Instalasi
1. Ekstrak folder `stok-kita` ke `C:\xampp\htdocs\`.
2. Jalankan Apache dan MySQL dari XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih menu **Import**, lalu impor `database/stok_kita.sql`.
   - File SQL juga membuat database `stok_kita` jika belum tersedia.
5. Periksa konfigurasi koneksi di `config/database.php`. Default XAMPP: user `root`, password kosong.
6. Buka `http://localhost/stok-kita/`.
7. Pilih **Daftar sekarang** untuk membuat akun sendiri. Tidak ada akun demo atau akun bawaan.

## Fitur
- Registrasi dan login mandiri.
- Password disimpan dengan `password_hash()` dan diverifikasi dengan `password_verify()`.
- Session login, regenerasi session ID, logout, dan token CSRF.
- Data multi-user: kategori, barang, transaksi hanya dapat diakses oleh pemiliknya.
- CRUD katalog barang.
- Transaksi barang masuk dan keluar dengan transaksi database dan penguncian stok.
- Validasi stok agar tidak menjadi negatif.
- Riwayat mutasi dan peringatan stok minimum.
- Pengaturan nama, username, dan password.

## Catatan
- Kategori sudah disiapkan pada skema database. Versi awal ini menyediakan relasi kategori pada barang, tetapi pengelolaan kategori khusus belum dibuat sebagai halaman terpisah.
- Stok awal pada formulir barang digunakan untuk memasukkan saldo awal. Perubahan stok berikutnya sebaiknya dilakukan melalui menu transaksi.
- Untuk penggunaan produksi, aktifkan HTTPS, atur kredensial database yang kuat, nonaktifkan error display, dan lakukan backup database secara berkala.
"# stok-kita" 
