
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

    <ul class="productes">

        <?php foreach ($productes as $producte): ?>

            <li>
                <?php $oferta = $producte['preu_oferta'] ?? null; ?>

                <article class="card<?php echo $producte['disponible'] ? '' : ' card--esgotat'; ?><?php echo $oferta ? ' card--oferta' : ''; ?>">

                    <?php if ($oferta): ?>
                        <span class="card__etiqueta"><i class="fa-solid fa-tag" aria-hidden="true"></i> Oferta</span>
                    <?php endif; ?>

                    <span class="card__imatge" aria-hidden="true">
                        <i class="fa-solid <?php echo htmlspecialchars($producte['icona']); ?>"></i>
                    </span>

                    <h2 class="card__nom"><?php echo htmlspecialchars($producte['nom']); ?></h2>

                    <div class="card__compra">
                        <?php if ($oferta): ?>
                            <span class="card__preu">
                                <del><?php echo number_format($producte['preu'], 2, ',', '.'); ?> €</del>
                                <?php echo number_format($oferta, 2, ',', '.'); ?> €
                            </span>
                        <?php else: ?>
                            <span class="card__preu"><?php echo number_format($producte['preu'], 2, ',', '.'); ?> €</span>
                        <?php endif; ?>

                        <div class="card__quantitat">
                            <label for="quantitat-<?php echo $producte['id']; ?>">Quantitat</label>

                            <?php if ($producte['disponible']): ?>

                                <input
                                    type="number"
                                    id="quantitat-<?php echo $producte['id']; ?>"
                                    name="quantitats[<?php echo $producte['id']; ?>]"
                                    min="0"
                                    max="20"
                                    value="0"
                                >

                            <?php else: ?>

                                <input type="number" id="quantitat-<?php echo $producte['id']; ?>" value="0" disabled>

                            <?php endif; ?>
                        </div>
                    </div>

                    <p class="card__estat">
                        <?php if ($producte['disponible']): ?>
                            <i class="fa-solid fa-circle-check" aria-hidden="true"></i> Disponible
                        <?php else: ?>
                            <i class="fa-solid fa-circle-xmark" aria-hidden="true"></i> Esgotat
                        <?php endif; ?>
                    </p>

                </article>
            </li>

        <?php endforeach; ?>

    </ul>

    <div class="compra__accions">
        <button type="submit">Continuar amb la compra</button>
    </div>

</form>

</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
