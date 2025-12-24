## LAPORAN PRAKTIKUM 13
Nama : Ali Gunawan | Kelas : TI.24.A.3 | NIM : 312410400
## TUJUAN PRAKTIKUM
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

## Struktur Database
Database yang digunakan bernama lab12_php_oop dengan tabel artikel.
```php
CREATE TABLE artikel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL
);
```

## Konfigurasi Database
config.php
```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'lab12_php_oop');
```

## Class Database (OOP)
class/Database.php
```php
<?php
class Database {
    public $conn;

    public function __construct() {
        $this->conn = new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASS,
            DB_NAME
        );

        if ($this->conn->connect_error) {
            die("Koneksi database gagal");
        }
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }
}
```

## Routing Aplikasi
Routing dilakukan melalui file index.php menggunakan parameter mod dan page.
### index.php
```php
<?php
session_start();
include "config.php";
include "class/Database.php";

$mod  = $_GET['mod']  ?? 'home';
$page = $_GET['page'] ?? 'index';

$file = "module/$mod/$page.php";

if (file_exists($file)) {
    include "template/header.php";
    include $file;
    include "template/footer.php";
} else {
    echo "<h3>Halaman tidak ditemukan</h3>";
}
```

## Implementasi CRUD Artikel
### a. Menampilkan Data Artikel
File: module/artikel/index.php
```php
<?php
$db = new Database();
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $db->query("DELETE FROM artikel WHERE id=$id");
    header("Location: index.php?mod=artikel&page=index");
    exit;
}

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

$limit = 5;
$hal   = (isset($_GET['hal']) && $_GET['hal'] > 0) ? (int)$_GET['hal'] : 1;
$start = ($hal - 1) * $limit;

$countSql = "SELECT COUNT(*) as total FROM artikel";
if ($keyword != '') {
    $countSql .= " WHERE judul LIKE '%$keyword%' OR isi LIKE '%$keyword%'";
}

$resultCount = $db->query($countSql);

$totalData = 0;
if ($resultCount) {
    $rowCount = $resultCount->fetch_assoc();
    $totalData = $rowCount['total'];
}

$totalPage = ($totalData > 0) ? ceil($totalData / $limit) : 1;

$sql = "SELECT * FROM artikel";
if ($keyword != '') {
    $sql .= " WHERE judul LIKE '%$keyword%' OR isi LIKE '%$keyword%'";
}
$sql .= " LIMIT $start, $limit";

$data = $db->query($sql);
?>

<h3>Data Artikel</h3>

<form method="get" style="margin-bottom:15px;">
    <input type="hidden" name="mod" value="artikel">
    <input type="hidden" name="page" value="index">

    <input
        type="text"
        name="keyword"
        placeholder="Cari artikel..."
        value="<?= htmlspecialchars($keyword) ?>"
    >
    <button type="submit">Cari</button>
</form>

<a href="index.php?mod=artikel&page=tambah"
   style="padding:6px 10px; background:#6c757d; color:white; text-decoration:none; border-radius:4px;">
    Tambah Artikel
</a>

<br><br>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr style="background:#f2f2f2;">
        <th width="5%">No</th>
        <th>Judul</th>
        <th width="20%">Aksi</th>
    </tr>

    <?php if ($data && $data->num_rows > 0): ?>
        <?php $no = $start + 1; ?>
        <?php while ($row = $data->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td>
                    <a href="index.php?mod=artikel&page=ubah&id=<?= $row['id'] ?>">Edit</a> |
                    <a href="index.php?mod=artikel&page=index&hapus=<?= $row['id'] ?>&hal=<?= $hal ?>&keyword=<?= urlencode($keyword) ?>"
                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                        Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="3" align="center">Data tidak ditemukan</td>
        </tr>
    <?php endif; ?>
</table>

<br>

<div>
    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
        <a
            href="index.php?mod=artikel&page=index&hal=<?= $i ?>&keyword=<?= urlencode($keyword) ?>"
            style="
                padding:6px 10px;
                margin-right:4px;
                text-decoration:none;
                background:<?= ($i == $hal) ? '#6c757d' : '#e0e0e0' ?>;
                color:<?= ($i == $hal) ? 'white' : 'black' ?>;
                border-radius:4px;
            "
        >
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>
```
Fitur:
- Menampilkan data artikel
- Pencarian data
- Pagination
- Hapus dan Edit data
