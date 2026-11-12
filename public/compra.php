
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

// Llegir el JSON
$fitxer = '../data/productes.json';
$productes = json_decode(file_get_contents($fitxer), true);
?>
<main>

<h1>Comprar productes</h1>

<form action="finalitzacio.php" method="POST">

    <table border="1" cellpadding="10">

        <tr>
            <th>Producte</th>
            <th>Descripció</th>
            <th>Preu</th>
            <th>Disponibilitat</th>
            <th>Quantitat</th>
        </tr>

        <?php foreach ($productes as $producte): ?>

            <tr>
                <td><?php echo htmlspecialchars($producte['nom']); ?></td>

                <td><?php echo htmlspecialchars($producte['descripcio']); ?></td>

                <td><?php echo number_format($producte['preu'], 2, ',', '.'); ?> €</td>

                <td>
                    <?php
                    if ($producte['disponible']) {
                        echo "Disponible";
                    } else {
                        echo "Esgotat";
                    }
                    ?>
                </td>

                <td>
                    <?php if ($producte['disponible']): ?>

                        <input
                            type="number"
                            name="quantitats[<?php echo $producte['id']; ?>]"
                            min="0"
                            max="20"
                            value="0"
                        >

                    <?php else: ?>

                        <input type="number" value="0" disabled>

                    <?php endif; ?>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>

    <br>

    <button type="submit">Continuar amb la compra</button>

</form>

</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
