<?php
    $titol = "Projecte Cantina";
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
<?php
    include '../includes/header.php';
?>
        <main>
            <img src="../frontend/public/hero.png" alt="Hero Cantina">
        </main>
<?php
    include '../includes/footer.php';
?>
    </body>
</html>
