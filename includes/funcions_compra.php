<?php

// Que json_encode escrigui els preus normals (1.2 i no 1.19999...)
ini_set('serialize_precision', -1);

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
            'subtotal' => round($preu * $quantitat, 2)
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

// Afegeix el tiquet al final de comandes.txt, una comanda per línia
// LOCK_EX bloqueja el fitxer mentre s'escriu, perquè dues compres alhora no el barregin
function guardar_tiquet(array $tiquet): bool {
    $linia = json_encode($tiquet, JSON_UNESCAPED_UNICODE) . "\n";

    // Si no s'ha pogut escriure (per exemple, per permisos), file_put_contents retorna false
    return file_put_contents(RUTA_COMANDES, $linia, FILE_APPEND | LOCK_EX) !== false;
}

// Resta al JSON les unitats que s'han comprat de cada producte
function restar_unitats(array $compra): void {
    $productes = llegir_productes();

    // Per a cada producte comprat, el busca a la llista pel seu id i li resta la quantitat
    foreach ($compra as $linia) {
        foreach ($productes as $i => $producte) {
            if ($producte['id'] === $linia['id']) {
                $productes[$i]['unitats'] = $producte['unitats'] - $linia['quantitat'];
            }
        }
    }

    // Torna a escriure tot el JSON amb les unitats noves i el mateix format (1.0 es queda 1.0)
    // LOCK_EX bloqueja el fitxer mentre s'escriu
    $json = json_encode($productes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION) . "\n";
    file_put_contents(RUTA_PRODUCTES, $json, LOCK_EX);
}

// Comprova les dades del formulari i retorna el missatge d'error, o "" si tot és correcte
function validar_dades(string $nom, string $email, string $tel): string {

    // El nom és obligatori
    if ($nom == "") {
        return "El nom és obligatori.";
    }

    // L'email és obligatori i ha de tenir format d'email (filter_var és la funció de PHP per validar emails)
    if ($email == "") {
        return "L'email és obligatori.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "L'email no és correcte (exemple: nom@gmail.com).";
    }

    // El telèfon, sense espais, ha de tenir 9 caràcters i tots han de ser xifres (ctype_digit: només 0-9)
    $tel_sense_espais = str_replace(' ', '', $tel);

    if (strlen($tel_sense_espais) != 9) {
        return "El telèfon ha de tenir 9 xifres.";
    }

    if (!ctype_digit($tel_sense_espais)) {
        return "El telèfon només pot tenir números.";
    }

    // Si arriba aquí, tot és correcte
    return "";
}

// Retorna true si a la sessió hi ha una compra amb algun producte
function hi_ha_compra(): bool {
    return isset($_SESSION['compra']) && count($_SESSION['compra']) > 0;
}

// Pinta el tiquet d'una comanda (a finalitzacio.php i a admin.php)
function mostrar_tiquet(array $tiquet): void {
    ?>
    <div class="tiquet">
        <div class="tiquet-capcalera">
            <h2>CANTINA</h2>
            <p>Tiquet <?= htmlspecialchars($tiquet['numero']) ?> · <?= htmlspecialchars($tiquet['data']) ?></p>
        </div>

        <hr>

        <p><?= htmlspecialchars($tiquet['nom'] . ' ' . $tiquet['cognom']) ?></p>
        <p><?= htmlspecialchars($tiquet['email']) ?> · <?= htmlspecialchars($tiquet['telefon']) ?></p>

        <hr>

        <table class="taula taula-tiquet">
            <tr><th>Producte</th><th>U.</th><th>Preu</th></tr>

            <?php foreach ($tiquet['productes'] as $producte): ?>
                <tr>
                    <td><?= htmlspecialchars($producte['nom']) ?></td>
                    <td><?= $producte['quantitat'] ?></td>
                    <td><?= format_preu($producte['subtotal']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="total-tiquet">
            <span>TOTAL</span>
            <span><?= format_preu($tiquet['total']) ?></span>
        </div>
    </div>
    <?php
}
