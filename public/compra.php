<?php
$titol = "Projecte Cantina";

require_once '../includes/layout.php';
require_once '../includes/funcions.php';

$productes = llegir_productes();

layout_inici($titol);
?>
<main>

<!-- Banner amb la imatge de fons -->
<section class="banner">
    <p class="banner__subtitol">La Cantina</p>
    <h1>Productes</h1>
    <p>Descobreix els nostres productes i tria el que més t'agradi.</p>
</section>

<form action="finalitzacio.php" method="POST">

    <ul class="productes">
        <?php foreach ($productes as $producte): ?>
            <li><?php targeta_producte($producte); ?></li>
        <?php endforeach; ?>
    </ul>

    <div class="compra__accions">
        <button type="submit">Continuar amb la compra</button>
    </div>

</form>

</main>

<?php layout_final(); ?>
