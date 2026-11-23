<?php
require_once '../includes/layout.php';
require_once '../includes/funcions.php';

$titol = "Compra";

$franja = franja_actual();
$productes = productes_franja($franja);

layout_inici($titol);
?>
<main>

<!-- Banner amb la imatge de fons -->
<section class="banner">
    <p class="banner__subtitol">La Cantina</p>
    <h1>Productes</h1>
    <p>Descobreix els nostres productes i tria el que més t'agradi.</p>

    <?php if ($franja != ''): ?>
        <p class="banner__franja">Ara: <?= ucfirst($franja) ?></p>
    <?php endif; ?>
</section>

<form action="finalitzacio.php" method="POST">

    <ul class="productes">
        <?php foreach ($productes as $producte): ?>
            <li><?php targeta_producte($producte); ?></li>
        <?php endforeach; ?>
    </ul>

    <!-- Tiquet: una línia per producte, amagada fins que se n'afegeix alguna unitat -->
    <aside class="tiquet-viu">
        <h2>El teu tiquet</h2>

        <ul>
            <?php foreach ($productes as $producte): ?>
                <li id="linia-<?= $producte['id'] ?>" hidden>
                    <span class="linia__quantitat">0</span> x <?= htmlspecialchars($producte['nom']) ?>
                    <span class="linia__subtotal"></span>
                </li>
            <?php endforeach; ?>
        </ul>

        <p>Total: <span class="tiquet-viu__total">0,00 €</span></p>
    </aside>

    <div class="compra__accions">
        <button type="submit">Continuar amb la compra</button>
    </div>

</form>

</main>

<?php layout_final(); ?>
