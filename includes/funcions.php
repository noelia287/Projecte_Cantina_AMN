<?php

// Llegeix el JSON de productes i el retorna com un array de PHP 
function llegir_productes(): array {
    $ruta = '../data/productes.json';

    $fitxer = fopen($ruta, 'r');  
    $contingut = fread($fitxer, filesize($ruta)); 
    fclose($fitxer); 

    return json_decode($contingut, true);
}

// Extra Ofertes
function llegir_ofertes(): array {
    $ofertes = [];

    // Si algun producte no està esgotat i té preu d'oferta l'afegeix a "ofertes"
    foreach (llegir_productes() as $producte) {
        if ($producte['unitats'] > 0 && $producte['preu_oferta'] > 0) {
            array_push($ofertes, $producte);
        }
    }

    return $ofertes;
}

// Formata el preu amb "," i dos decimals i el signe del euro
function format_preu(float $preu): string {
    return number_format($preu, 2, ',', '.') . ' €';
}


// Pinta la targeta d'un producte
// $amb_carret = true → carret amb quantitat (compra.php)
// $amb_carret = false → botó "Comprar" que porta a compra.php (index.php)
function targeta_producte(array $producte, bool $amb_carret = true): void {
    $oferta = $producte['preu_oferta'];
    $disponible = $producte['unitats'] > 0;
    $id = $producte['id'];

    // Si està esgotat, la targeta surt apagada i el carret no es pot clicar
    $classe = 'producte';
    $desactivat = '';

    if (!$disponible) {
        $classe = 'producte producte--esgotat';
        $desactivat = 'disabled';
    }
    ?>
    <article class="<?= $classe ?>">

        <img class="producte__imatge" src="../frontend/public/<?= htmlspecialchars($producte['imatge']) ?>" alt="<?= htmlspecialchars($producte['nom']) ?>">

        <?php if (!$disponible): ?>
            <span class="producte__etiqueta">Esgotat</span>
        <?php elseif ($oferta > 0): ?>
            <span class="producte__etiqueta">Oferta</span>
        <?php endif; ?>

        <div class="producte__info">
            <h2 class="producte__nom"><?= htmlspecialchars($producte['nom']) ?></h2>
            <p class="producte__descripcio"><?= htmlspecialchars($producte['descripcio']) ?></p>

            <div class="producte__compra">
                <span class="producte__preu">
                    <?php if ($oferta > 0): ?>
                        <del><?= format_preu($producte['preu']) ?></del>
                        <?= format_preu($oferta) ?>
                    <?php else: ?>
                        <?= format_preu($producte['preu']) ?>
                    <?php endif; ?>
                </span>

                <?php if ($amb_carret): ?>
                <div class="producte__quantitat" data-unitats="<?= $producte['unitats'] ?>">

                    <!-- Botó del carret: es veu mentre la quantitat és 0 -->
                    <button type="button" class="producte__carret" aria-label="Afegir al carret" <?= $desactivat ?>>
                        <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                    </button>

                    <!-- Selector "-" quantitat "+": es veu quan la quantitat és 1 o més -->
                    <div class="producte__selector" hidden>
                        <button type="button" class="producte__menys" aria-label="Treure'n un">-</button>
                        <span class="producte__numero">0</span>
                        <button type="button" class="producte__mes" aria-label="Afegir-ne un">+</button>
                    </div>

                    <!-- Valor que s'envia amb el formulari -->
                    <input type="hidden" name="quantitats[<?= $id ?>]" value="0">
                </div>
                <?php else: ?>
                    <a class="producte__boto" href="compra.php">Comprar</a>
                <?php endif; ?>
            </div>
        </div>

    </article>
    <?php
}
