## LAPORAN PRAKTIKUM 13
Nama : Ali Gunawan | Kelas : TI.24.A.3 | NIM : 312410400
### TUJUAN PRAKTIKUM
1. Menerapkan konsep Object Oriented Programming (OOP) pada PHP.

2. Mengimplementasikan koneksi database MySQL menggunakan class.

3. Membuat fitur CRUD (Create, Read, Update, Delete) pada data artikel.

4. Menerapkan sistem routing sederhana menggunakan parameter mod dan page.

## Struktur folder project
```
lab12_php_oop/
├── index.php
├── config.php
├── class/
│   └── Database.php
├── template/
│   ├── header.php
│   └── footer.php
└── module/
    └── artikel/
        ├── index.php
        ├── tambah.php
        └── ubah.php
```
Keterangan:
- config.php : menyimpan konfigurasi database
- Database.php : class untuk koneksi dan query database
- index.php : router utama aplikasi
- module/artikel/ : modul pengelolaan data artikel
- template/ : template tampilan header dan footer
