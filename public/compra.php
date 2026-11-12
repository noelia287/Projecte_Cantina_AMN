
<?php
$titol = "Projecte Cantina";
include '../includes/header.php';

// Llegir el JSON
$fitxer = '../data/productes.json';
$productes = json_decode(file_get_contents($fitxer), true);
?>

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

<?php
include '../includes/footer.php';
?>
