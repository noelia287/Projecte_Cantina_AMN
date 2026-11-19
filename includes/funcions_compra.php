<?php

// Rep les quantitats del formulari i retorna una llista amb els productes que es compren
function calcular_compra(array $quantitats): array {
    $compra = [];

    foreach (llegir_productes() as $producte) {
        $id = $producte['id'];

        // Si no arriba la quantitat del producte, es queda a 0
        $quantitat = 0;
        if (isset($quantitats[$id])) {
            $quantitat = (int) $quantitats[$id];
        }

        // Si no se n'ha demanat cap o se'n demanen més de les que hi ha, passa al següent producte
        if ($quantitat <= 0 || $quantitat > $producte['unitats']) {
            continue;
        }

        // Si té preu d'oferta es cobra aquest, sinó el normal
        $preu = $producte['preu'];
        if ($producte['preu_oferta'] > 0) {
            $preu = $producte['preu_oferta'];
        }

        // Afegeix el producte a la compra amb el seu subtotal
        array_push($compra, [
            'id' => $id,
            'nom' => $producte['nom'],
            'preu' => $preu,
            'quantitat' => $quantitat,
            'subtotal' => $preu * $quantitat
        ]);
    }

    return $compra;
}

// Suma els subtotals de tots els productes de la compra
function calcular_total(array $compra): float {
    $total = 0;

    foreach ($compra as $linia) {
        $total = $total + $linia['subtotal'];
    }

    return $total;
}
