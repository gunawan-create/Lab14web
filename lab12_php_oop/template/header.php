<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LAB OOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
  <div class="container">
    <a class="navbar-brand" href="index.php">LAB OOP</a>

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php?mod=home&page=index">Home</a>
        </li>

        <?php if (isset($_SESSION['is_login'])): ?>
        <li class="nav-item">
          <a class="nav-link" href="index.php?mod=artikel&page=index">Artikel</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?mod=user&page=profile">Profil</a>
        </li>
        <?php endif; ?>
      </ul>

      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['is_login'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?mod=user&page=logout">
              Logout (<?= $_SESSION['nama'] ?>)
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?mod=user&page=login">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
