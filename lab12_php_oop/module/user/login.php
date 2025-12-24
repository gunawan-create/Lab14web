<?php
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $db = new Database();
    $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = $db->query($sql);

    if ($result && $result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['is_login'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama']     = $user['nama'];

            header("Location: index.php?mod=artikel&page=index");
            exit;

        } else {
            $msg = "Password salah";
        }

    } else {
        $msg = "Username tidak ditemukan";
    }
}
?>

<h3>Login</h3>

<?php if ($msg): ?>
    <p style="color:red"><?= $msg ?></p>
<?php endif; ?>

<form method="post">
    <input name="username" placeholder="Username" required>
    <input name="password" type="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
