<?php
    $titol = $titol ?? "Cantina";
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titol) ?></title>
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/src/js/main.js"></script>
    <link rel="icon" type="image/png" href="../frontend/public/logo.png">
</head>
<body>
<header>
    <a href="../public/index.php" class="logo"><img src="../frontend/public/logo.png" alt="Logo de la cantina"></a>
    <button class="menu-toggle" type="button" aria-label="Obrir el menú" aria-expanded="false" aria-controls="menu-principal">☰</button>
    <nav id="menu-principal">
        <ul>
            <li><a href="../public/index.php">Inici</a></li>
            <li><a href="../public/compra.php">Compra</a></li>
            <li><a href="../public/admin.php">Panell d'administració</a></li>
        </ul>
    </nav>
</header>
<main>