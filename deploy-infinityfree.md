# Deploy ke InfinityFree - Panduan

## Langkah 1: Daftar & Setup di InfinityFree

1. Daftar di https://www.infinityfree.com
2. Buat akun hosting baru (dapat subdomain gratis, misal: `petainteraktif.epizy.com`)
3. Dari **Control Panel**, buat database MySQL:
   - Catat: **DB Name**, **DB Username**, **DB Password**, **DB Host** (biasanya `sql123.epizy.com`)

## Langkah 2: Upload File

### Struktur di InfinityFree:
```
htdocs/                    ← Document root (upload isi folder public/ ke sini)
├── index.php              ← YANG SUDAH DIMODIFIKASI (lihat bawah)
├── css/
├── images/
├── build/
├── .htaccess
├── favicon.ico
└── robots.txt

laravel/                   ← Buat folder ini, upload SEMUA file Laravel KECUALI folder public/
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env                   ← EDIT credentials DB di sini
├── artisan
├── composer.json
└── ...
```

### Cara upload:
- Gunakan **File Manager** di Control Panel InfinityFree
- Atau gunakan **FTP** (FileZilla) dengan credentials dari panel

## Langkah 3: Edit .env di Server

Ganti bagian database:
```
DB_CONNECTION=mysql
DB_HOST=sql123.epizy.com        ← dari panel InfinityFree
DB_PORT=3306
DB_DATABASE=epiz_XXXXX_petainteraktif  ← dari panel
DB_USERNAME=epiz_XXXXX          ← dari panel
DB_PASSWORD=password_kamu       ← dari panel
APP_ENV=production
APP_DEBUG=false
APP_URL=https://petainteraktif.epizy.com
```

## Langkah 4: Jalankan Migration

Buka di browser:
```
https://subdomain-kamu.epizy.com/setup/migrate
```

## Langkah 5: Buat Storage Link

Buka di browser:
```
https://subdomain-kamu.epizy.com/setup/storage-link
```

## Langkah 6: Hapus Route Setup

Setelah migration & storage link berhasil, HAPUS route setup dari `routes/web.php` 
(atau biarkan, karena sudah dilindungi APP_ENV check).

## Catatan Penting

- InfinityFree TIDAK support SSH, jadi semua artisan command via route
- Max upload file: 10MB
- PHP version: 7.4 / 8.x (pilih dari panel)
- Pastikan pilih PHP 8.1+ dari panel
- Free SSL tersedia (aktifkan dari panel)
