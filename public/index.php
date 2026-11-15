<?php
    $titol = "Projecte Cantina";

    // Ofertes del dia: productes disponibles que tenen preu d'oferta
    $productes = json_decode(file_get_contents('../data/productes.json'), true);
    $ofertes = array_filter($productes, function ($producte) {
        return $producte['disponible'] && isset($producte['preu_oferta']);
    });
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

            <?php if (count($ofertes) > 0): ?>

            <section class="ofertes">
                <h2>Ofertes del dia</h2>

                <ul class="ofertes__llista">

                    <?php foreach ($ofertes as $producte): ?>

                        <li>
                            <article class="card card--oferta">

                                <span class="card__etiqueta"><i class="fa-solid fa-tag" aria-hidden="true"></i> Oferta</span>

                                <span class="card__imatge" aria-hidden="true">
                                    <i class="fa-solid <?php echo htmlspecialchars($producte['icona']); ?>"></i>
                                </span>

                                <h3 class="card__nom"><?php echo htmlspecialchars($producte['nom']); ?></h3>

                                <div class="card__compra">
                                    <span class="card__preu">
                                        <del><?php echo number_format($producte['preu'], 2, ',', '.'); ?> €</del>
                                        <?php echo number_format($producte['preu_oferta'], 2, ',', '.'); ?> €
                                    </span>
                                </div>

                                <a class="card__enllac" href="compra.php">
                                    Comprar <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                </a>

                            </article>
                        </li>

                    <?php endforeach; ?>

                </ul>
            </section>

            <?php endif; ?>
        </main>
<?php
    include '../includes/footer.php';
?>
    </body>
</html>
