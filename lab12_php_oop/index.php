<?php
session_start();
include "config.php";
include "class/Database.php";
$mod  = $_GET['mod']  ?? 'home';
$page = $_GET['page'] ?? 'index';

$public_pages = ['home', 'user'];
if (!in_array($mod, $public_pages)) {
    if (!isset($_SESSION['is_login'])) {
        header("Location: index.php?mod=user&page=login");
        exit;
    }
}

$file = "module/$mod/$page.php";
if (file_exists($file)) {
    include "template/header.php";
    include $file;
    include "template/footer.php";
} else {
    echo "<h3>Halaman tidak ditemukan</h3>";
}
