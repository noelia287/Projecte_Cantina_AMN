<?php

require_once __DIR__ . '/../config/config.php';


// FUNCIÓ: LLEGIR ELS PRODUCTES
// Llegeix el JSON de productes i el retorna com un array de PHP
function llegir_productes(): array {
    $fitxer = fopen(RUTA_PRODUCTES, 'r');
    $contingut = fread($fitxer, filesize(RUTA_PRODUCTES));
    fclose($fitxer);

    return json_decode($contingut, true);
}


// FUNCIÓ: FRANJA HORÀRIA ACTUAL
// Retorna la franja de l'hora actual: "esmorzar", "dinar", "berenar" o "" si la cantina està tancada
function franja_actual(): string {

    // Si s'està provant una franja, es fa servir aquesta
    if (FRANJA_PROVES != '') {
        return FRANJA_PROVES;
    }

    $hora = (int) date('G'); // Hora actual, de 0 a 23

    if ($hora >= HORA_ESMORZAR && $hora < HORA_DINAR) {
        return 'esmorzar';
    } elseif ($hora >= HORA_DINAR && $hora < HORA_BERENAR) {
        return 'dinar';
    } elseif ($hora >= HORA_BERENAR && $hora < HORA_TANCAMENT) {
        return 'berenar';
    } else {
        return '';
    }
}


// FUNCIÓ: PRODUCTES DE LA FRANJA
// Retorna els productes de la franja; si la cantina està tancada (franja buida), els retorna tots
function productes_franja(string $franja): array {
    $productes = [];

    foreach (llegir_productes() as $producte) {
        if ($franja == '' || in_array($franja, $producte['franges'])) {
            array_push($productes, $producte);
        }
    }

    return $productes;
}


// FUNCIÓ: OFERTES DEL DIA
// Retorna els productes de la franja actual que tenen preu d'oferta i no estan esgotats
function llegir_ofertes(): array {
    $ofertes = [];

    // Només els que no estan esgotats i tenen preu d'oferta
    foreach (productes_franja(franja_actual()) as $producte) {
        if ($producte['unitats'] > 0 && $producte['preu_oferta'] > 0) {
            array_push($ofertes, $producte);
        }
    }

    return $ofertes;
}


// FUNCIÓ: FORMATAR EL PREU
// Formata el preu amb "," i dos decimals i el signe de l'euro
function format_preu(float $preu): string {
    return number_format($preu, 2, ',', '.') . ' €';
}


// FUNCIÓ: TARGETA D'UN PRODUCTE
// Pinta la targeta d'un producte
function targeta_producte(array $producte, bool $amb_carret = true): void {
    $oferta = $producte['preu_oferta'];
    $disponible = $producte['unitats'] > 0;
    $id = $producte['id'];

    // Preu que es cobra: el d'oferta si en té, sinó el normal
    $preu_final = $producte['preu'];
    if ($oferta > 0) {
        $preu_final = $oferta;
    }

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
                <div class="producte__quantitat" data-unitats="<?= $producte['unitats'] ?>" data-id="<?= $id ?>" data-preu="<?= $preu_final ?>">

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
