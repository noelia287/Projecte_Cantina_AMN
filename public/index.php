<?php
require_once '../includes/layout.php';
require_once '../includes/funcions.php';

$titol = "Inici";

$ofertes = llegir_ofertes();

layout_inici($titol);
?>
<main>

<!-- Banner amb la foto de la cantina de fons -->
<section class="banner banner--inici">
    <p class="banner__subtitol">La Cantina</p>
    <h1>Benvinguts a la Cantina</h1>
    <p>Entrepans, begudes i snacks per agafar forces entre classe i classe.</p>
    <a class="banner__boto" href="compra.php">Veure productes</a>
</section>

<?php if (count($ofertes) > 0): ?>

<section class="ofertes">
    <h2>Ofertes del dia</h2>

    <ul class="ofertes__llista">

        <?php foreach ($ofertes as $producte): ?>
            <li><?php targeta_producte($producte, false); ?></li>
        <?php endforeach; ?>

    </ul>
</section>

<?php endif; ?>

</main>

<?php layout_final(); ?>
