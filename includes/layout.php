<?php
// Layout comú. Ús: layout_inici($titol); ...contingut... layout_final();

function layout_inici(string $titol = "Projecte Cantina"): void { ?>
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
<?php include __DIR__ . '/header.php';
}

function layout_final(): void {
    include __DIR__ . '/footer.php'; ?>
</body>
</html>
<?php }
