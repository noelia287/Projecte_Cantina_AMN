<?php
// Funcions dels productes.

// Llegeix el JSON i retorna la llista de productes
function llegir_productes(): array {
    $fitxer = __DIR__ . '/../data/productes.json';
    return json_decode(file_get_contents($fitxer), true);
}

// Ofertes del dia: productes disponibles que tenen preu d'oferta
function llegir_ofertes(): array {
    return array_filter(llegir_productes(), function ($producte) {
        return $producte['disponible'] && isset($producte['preu_oferta']);
    });
}

// 2.5 → "2,50 €"
function format_preu(float $preu): string {
    return number_format($preu, 2, ',', '.') . ' €';
}

// Pinta la targeta d'un producte
// $amb_carret = true → carret amb quantitat (compra.php)
// $amb_carret = false → botó "Comprar" que porta a compra.php (index.php)
function targeta_producte(array $producte, bool $amb_carret = true): void {
    $oferta = $producte['preu_oferta'] ?? null;
    $disponible = $producte['disponible'];
    $id = $producte['id'];
    ?>
    <article class="producte<?= $disponible ? '' : ' producte--esgotat' ?>">

        <?php if ($oferta): ?>
            <span class="producte__etiqueta">Oferta</span>
        <?php elseif (!$disponible): ?>
            <span class="producte__etiqueta">Esgotat</span>
        <?php endif; ?>

        <img class="producte__imatge" src="../frontend/public/<?= htmlspecialchars($producte['imatge']) ?>" alt="<?= htmlspecialchars($producte['nom']) ?>">

        <div class="producte__info">
            <h2 class="producte__nom"><?= htmlspecialchars($producte['nom']) ?></h2>
            <p class="producte__descripcio"><?= htmlspecialchars($producte['descripcio']) ?></p>

            <div class="producte__compra">
                <span class="producte__preu">
                    <?php if ($oferta): ?>
                        <del><?= format_preu($producte['preu']) ?></del>
                        <?= format_preu($oferta) ?>
                    <?php else: ?>
                        <?= format_preu($producte['preu']) ?>
                    <?php endif; ?>
                </span>

                <?php if (!$amb_carret): ?>
                    <a class="producte__boto" href="compra.php">Comprar</a>
                <?php else: ?>
                <div class="producte__quantitat">
                    <!-- Botó del carret: es veu mentre la quantitat és 0 -->
                    <button type="button" class="producte__carret" aria-label="Afegir al carret"<?= $disponible ? '' : ' disabled' ?>>
                        <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                    </button>

                    <!-- Selector - quantitat +: es veu quan la quantitat és 1 o més -->
                    <div class="producte__selector" hidden>
                        <button type="button" class="producte__menys" aria-label="Treure'n un">-</button>
                        <span class="producte__numero">0</span>
                        <button type="button" class="producte__mes" aria-label="Afegir-ne un">+</button>
                    </div>

                    <!-- Valor que s'envia amb el formulari -->
                    <input type="hidden" name="quantitats[<?= $id ?>]" value="0">
                </div>
                <?php endif; ?>
            </div>
        </div>

    </article>
    <?php
}
