<?php
$db = new Database();

if (isset($_POST['submit'])) {
    $judul = trim($_POST['judul']);
    $isi   = trim($_POST['isi']);

    // VALIDASI SEDERHANA
    if ($judul != '' && $isi != '') {

        $sql = "INSERT INTO artikel (judul, isi) VALUES ('$judul', '$isi')";
        $result = $db->query($sql);

        // CEK HASIL INSERT
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
