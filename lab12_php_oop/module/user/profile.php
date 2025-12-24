<h3>Profil Pengguna</h3>

<table cellpadding="6">
    <tr>
        <td>Nama</td>
        <td>: <?= $_SESSION['nama']; ?></td>
    </tr>
    <tr>
        <td>Username</td>
        <td>: <?= $_SESSION['username']; ?></td>
    </tr>
</table>

<hr>

<h4>Ubah Password</h4>

<?php
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_baru = $_POST['password'];

    $hash = password_hash($password_baru, PASSWORD_DEFAULT);
    $db = new Database();

    $username = $_SESSION['username'];
    $db->query("UPDATE users SET password='$hash' WHERE username='$username'");

    $msg = "Password berhasil diubah";
}
?>

<?php if ($msg): ?>
<p style="color:green"><?= $msg ?></p>
<?php endif; ?>

<form method="post">
    <input type="password" name="password" placeholder="Password baru" required>
    <br><br>
    <button type="submit">Simpan</button>
</form>
