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

### b. Menambah Artikel
File: module/artikel/tambah.php
```php
<?php
$db = new Database();

if (isset($_POST['submit'])) {
    $judul = trim($_POST['judul']);
    $isi   = trim($_POST['isi']);

    if ($judul != '' && $isi != '') {

        $sql = "INSERT INTO artikel (judul, isi) VALUES ('$judul', '$isi')";
        $result = $db->query($sql);

        if ($result) {
            header("Location: index.php?mod=artikel&page=index");
            exit;
        } else {
            echo "<p style='color:red;'>Gagal menyimpan data</p>";
        }

    } else {
        echo "<p style='color:red;'>Judul dan isi wajib diisi</p>";
    }
}
?>

<h3>Tambah Artikel</h3>

<form method="post" action="index.php?mod=artikel&page=tambah">
    <table cellpadding="8" style="width:70%;">
        <tr>
            <td width="15%">Judul</td>
            <td>
                <input type="text" name="judul" required
                       style="width:100%; padding:8px;">
            </td>
        </tr>
        <tr>
            <td>Isi</td>
            <td>
                <textarea name="isi" rows="10" required
                          style="width:100%; padding:8px;"></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit" name="submit">Simpan</button>
                <a href="index.php?mod=artikel&page=index" style="margin-left:10px;">
                    Kembali
                </a>
            </td>
        </tr>
    </table>
</form>
```

Fitur:
- Input judul dan isi artikel
- Menyimpan data ke database
- Redirect ke halaman index setelah berhasil

### c. Mengubah Artikel
File: module/artikel/ubah.php
```php
<?php
$db = new Database();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data lama
$data = $db->query("SELECT * FROM artikel WHERE id=$id")->fetch_assoc();

if (isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    $isi   = $_POST['isi'];

    $sql = "UPDATE artikel SET judul='$judul', isi='$isi' WHERE id=$id";
    $db->query($sql);

    header("Location: index.php?mod=artikel&page=index");
    exit;

}
?>

<h3>Ubah Artikel</h3>

<form method="post">
    <table cellpadding="6">
        <tr>
            <td>Judul</td>
            <td>
                <input
                    type="text"
                    name="judul"
                    value="<?= htmlspecialchars($data['judul']) ?>"
                    required
                    style="width:100%;"
                >
            </td>
        </tr>
        <tr>
            <td>Isi</td>
            <td>
                <textarea name="isi" rows="6" required style="width:100%;"><?= htmlspecialchars($data['isi']) ?></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit" name="submit">Update</button>
                <a href="/artikel/index">Kembali</a>
            </td>
        </tr>
    </table>
</form>
```

Fitur:
- Menampilkan data berdasarkan ID
- Mengubah judul dan isi artikel
- Update data ke database

### d. Menghapus Artikel
Proses hapus dilakukan pada file index.php menggunakan parameter hapus.
```php
$db->query("DELETE FROM artikel WHERE id=$id");
```

## Langkah Penggunaan Aplikasi
### 1) Menjalankan server menggunakan XAMPP.
<img width="993" height="647" alt="image" src="https://github.com/user-attachments/assets/93b820d0-f3e3-4e26-8acf-02a3397b1eec" />

### 2) Membuka browser dan mengakses aplikasi.
<img width="1919" height="1062" alt="image" src="https://github.com/user-attachments/assets/0288aab4-9287-4821-9dfb-efeb6cbb71d7" />

### 3) Masuk ke menu Artikel.
<img width="1919" height="1140" alt="Screenshot 2025-12-24 131356" src="https://github.com/user-attachments/assets/f517175e-cf57-4db0-9174-a619cd38bf85" />

### 4) Menambahkan artikel baru melalui tombol Tambah Artikel.
![Uploading Screenshot 2025-12-24 133741.png…]()

### 5) Mengedit artikel menggunakan tombol Edit.
<img width="1919" height="1067" alt="image" src="https://github.com/user-attachments/assets/330f1e42-0460-4f0b-a87f-357b50c14924" />

### 6) Menghapus artikel menggunakan tombol Hapus.
<img width="1919" height="1063" alt="image" src="https://github.com/user-attachments/assets/c05a7275-a44a-40b6-a773-774148dcf839" />

### 7) Menggunakan fitur pencarian dan pagination untuk mempermudah pencarian data.
<img width="368" height="127" alt="image" src="https://github.com/user-attachments/assets/d44a08fe-2e74-4f8b-878a-d40b035496b2" />

## Hasil Praktikum
Hasil yang diperoleh dari praktikum ini adalah:
- Sistem CRUD artikel berjalan dengan baik.
- Data dapat ditambah, ditampilkan, diubah, dan dihapus.
- Koneksi database berhasil menggunakan konsep OOP.
- Routing aplikasi berjalan sesuai modul dan halaman.
- Tampilan sederhana dan mudah digunakan.

## Kesimpulan
Aplikasi ini berhasil mengimplementasikan konsep Object Oriented Programming pada PHP, serta mengintegrasikan database MySQL untuk pengelolaan data artikel. Sistem telah dilengkapi dengan fitur CRUD, pencarian, dan pagination yang berjalan sesuai dengan kebutuhan praktikum.
