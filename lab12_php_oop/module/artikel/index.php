<?php
$db = new Database();

/* =========================
   HAPUS DATA
========================= */
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $db->query("DELETE FROM artikel WHERE id=$id");
    header("Location: index.php?mod=artikel&page=index");
    exit;
}

/* =========================
   SEARCH
========================= */
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

/* =========================
   PAGINATION
========================= */
$limit = 5;
$hal   = (isset($_GET['hal']) && $_GET['hal'] > 0) ? (int)$_GET['hal'] : 1;
$start = ($hal - 1) * $limit;

/* =========================
   HITUNG TOTAL DATA
========================= */
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

/* =========================
   AMBIL DATA
========================= */
$sql = "SELECT * FROM artikel";
if ($keyword != '') {
    $sql .= " WHERE judul LIKE '%$keyword%' OR isi LIKE '%$keyword%'";
}
$sql .= " LIMIT $start, $limit";

$data = $db->query($sql);
?>

<h3>Data Artikel</h3>

<!-- FORM SEARCH -->
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

<!-- TOMBOL TAMBAH -->
<a href="index.php?mod=artikel&page=tambah"
   style="padding:6px 10px; background:#6c757d; color:white; text-decoration:none; border-radius:4px;">
    Tambah Artikel
</a>

<br><br>

<!-- TABEL DATA -->
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

<!-- PAGINATION -->
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
