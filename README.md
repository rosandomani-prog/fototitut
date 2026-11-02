# Galeri Titut

Website galeri foto bersama bergaya scrapbook (PHP + MySQL).

## Cara pasang
1. Buat database lewat phpMyAdmin: impor `database.sql` (otomatis membuat database `galeri_titut` dan tabel `photos`).
2. Edit `config.php`: isi DB_HOST, DB_NAME, DB_USER, DB_PASS, dan **ganti ADMIN_PASSWORD**.
   - Isi UPLOAD_PASSWORD kalau hanya anggota tertentu yang boleh mengunggah.
3. Upload semua file ke hosting (misal folder `public_html/`).
4. Pastikan folder `uploads/` bisa ditulis (chmod 755, atau 775 jika perlu).
5. Buka alamat websitenya. Klik "+ tempel foto".

## Kebutuhan
PHP 7.4+ (ekstensi PDO MySQL & fileinfo), MySQL/MariaDB, Apache (untuk `uploads/.htaccess`).
Jika memakai Nginx, blokir eksekusi PHP di folder `uploads/` lewat konfigurasi server.
Batas unggah PHP (`upload_max_filesize`, `post_max_size`) juga perlu cukup besar.
