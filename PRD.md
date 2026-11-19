# Product Requirements Document (PRD)
## Sistem Monitoring dan Pencatatan Stok Barang untuk UMKM Berbasis Web (**StokKita**)

**Versi Dokumen:** 1.0 (Tahap 1 – Inisiasi Proyek)  
**Technology Stack:** PHP Native (PHP 8.0+), MySQL / MariaDB, HTML5, CSS3, Vanilla JavaScript  
**Nama Kelompok:** YANG PENTING LIMA  
**Anggota Kelompok:**
1. M. Irfan Pasya (`24312188`)
2. Ajaya Haposan Firdaus (`24312099`)
3. Luthfia Jasmine Asy Syifa (`24312110`)
4. Zarir Azizah Munawaroh (`24312053`)
5. Alicea Calista (`24312136`)

---

### 1. Latar Belakang dan Permasalahan
Pelaku Usaha Mikro, Kecil, dan Menengah (UMKM) sering kali masih melakukan pencatatan keluar-masuk barang secara manual menggunakan buku catatan atau lembar kerja terpisah. Metode konvensional tersebut menimbulkan berbagai permasalahan operasional:
- **Ketidaksesuaian Saldo Stok:** Perubahan jumlah barang akibat transaksi harian tidak langsung memperbarui catatan stok sehingga sering terjadi selisih antara stok fisik dan pembukuan.
- **Risiko Stok Negatif & Kehabisan Barang:** Tidak adanya validasi otomatis saat pengeluaran barang serta ketiadaan peringatan dini ketika stok mencapai batas minimum.
- **Sulitnya Pelacakan Riwayat Mutasi:** Pengelola kesulitan menelusuri kronologi barang masuk dan keluar untuk kebutuhan evaluasi dan audit stok UMKM.

---

### 2. Tujuan dan Deskripsi Aplikasi
**StokKita** adalah sistem monitoring dan pencatatan stok barang berbasis web untuk UMKM dengan dukungan isolasi data *multi-user*. Setiap pengguna UMKM yang mendaftar dapat mengelola katalog barang, memproses transaksi stok masuk/keluar secara aman, memantau riwayat mutasi, serta melihat peringatan dini stok minimum secara mandiri.

**Tujuan Pengembangan:**
1. Menyediakan sistem pengelolaan katalog barang yang terstruktur beserta relasi kategori dan batas stok minimum.
2. Memastikan pembaruan saldo stok terjadi secara otomatis dan aman pada setiap transaksi barang masuk maupun keluar.
3. Menyediakan fitur peringatan dini (*alerts*) untuk barang yang mencapai atau berada di bawah batas stok minimum.
4. Menyediakan riwayat mutasi stok (*history*) dan ringkasan statistik inventaris pada halaman dashboard.

---

### 3. Target Pengguna Aplikasi
1. **Pemilik UMKM / Pengelola Usaha:** Mendaftarkan akun usaha, mengelola katalog barang, memantau ringkasan dashboard, mengecek peringatan stok minimum, meninjau riwayat mutasi, serta memperbarui informasi profil dan keamanan akun.
2. **Staf Operasional / Gudang:** Mencatat transaksi harian barang masuk dan barang keluar serta memeriksa ketersediaan stok barang.

---

### 4. Identifikasi Kebutuhan dan Fitur Utama
1. **Autentikasi & Pengaturan Akun (`auth/`, `login.php`, `register.php`, `settings.php`):**
   - Registrasi mandiri dan login pengguna tanpa akun demo bawaan.
   - Logout dan pengelolaan sesi aman beserta pengaturan nama, username, dan password pengguna.
2. **Dashboard Ringkasan (`dashboard.php`):**
   - Menampilkan ringkasan kondisi inventaris dan navigasi cepat menuju modul utama.
3. **CRUD Katalog Barang (`products/`):**
   - Penambahan, pembacaan, pengubahan, dan penghapusan data barang meliputi informasi produk, relasi kategori, saldo stok awal, dan batas stok minimum.
   - *(Catatan: Skema relasi kategori telah tersedia pada database; pengelolaan kategori khusus sebagai halaman terpisah direncanakan pada tahap pengembangan berikutnya).*
4. **Transaksi Barang Masuk & Keluar (`transactions/`):**
   - Pencatatan transaksi stok masuk dan keluar dengan pembaruan saldo otomatis menggunakan *database transaction* dan penguncian stok (*stock locking*).
5. **Riwayat Mutasi (`history/index.php`):**
   - Menampilkan catatan kronologis seluruh transaksi barang masuk dan keluar yang telah diproses.
6. **Peringatan Stok Minimum (`alerts/index.php`):**
   - Menampilkan daftar khusus barang yang jumlah stoknya berada pada atau di bawah batas minimum untuk segera dilakukan *restock*.

---

### 5. User Flow (Alur Penggunaan Aplikasi)

```text
[Akses index.php]
       │
       ├──(Belum Login)──► [login.php / register.php] ──► Validasi Sesi & CSRF
       │                                                          │
       └──(Sudah Login)───────────────────────────────────────────▼
                                                          [dashboard.php]
                                                                  │
       ┌──────────────────┬──────────────────┬────────────────────┼──────────────────┐
       ▼                  ▼                  ▼                    ▼                  ▼
[products/index.php] [transactions/     [history/index.php] [alerts/index.php] [settings.php]
 (CRUD Katalog)       create.php]        (Riwayat Mutasi)    (Stok Minimum)     (Pengaturan Akun)