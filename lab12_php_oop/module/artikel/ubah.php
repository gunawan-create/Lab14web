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
