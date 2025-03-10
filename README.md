<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Instalasi

Untuk memulai proyek ini, ikuti langkah-langkah berikut:
### Instal Dependensi
```bash
composer install --ignore-platform-req=ext-zip
```

### Update Dependensi (Opsional)
```bash
composer update --ignore-platform-req=ext-zip
```
### Migrasi Database
```bash
php artisan migrate
```
atau Import <b>testing_laravel.sql</b>

### Generate App key
```bash
php artisan key:generate
```
