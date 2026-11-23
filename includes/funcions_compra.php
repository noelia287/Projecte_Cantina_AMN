<?php

require_once __DIR__ . '/funcions.php';

// Que json_encode escrigui els preus normals (1.2 i no 1.19999...)
ini_set('serialize_precision', -1);


// FUNCIÓ: CALCULAR LA COMPRA
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


// FUNCIÓ: CALCULAR EL TOTAL
// Suma els subtotals de tots els productes de la compra
function calcular_total(array $compra): float {
    $total = 0;

    foreach ($compra as $linia) {
        $total = $total + $linia['subtotal'];
    }

    return $total;
}


// FUNCIÓ: CREAR EL TIQUET
// Crea el tiquet amb les dades de l'usuari i els productes de la compra
function crear_tiquet(string $nom, string $cognom, string $email, string $tel, array $compra): array {
    return [
        'numero' => uniqid(),
        'data' => date('d/m/Y H:i'),
        'nom' => $nom,
        'cognom' => $cognom,
        'email' => $email,
        'telefon' => $tel,
        'productes' => $compra,
        'total' => calcular_total($compra)
    ];
}


// FUNCIÓ: GUARDAR EL TIQUET
// Afegeix el tiquet al final de comandes.txt, una comanda per línia
// LOCK_EX bloqueja el fitxer mentre s'escriu, perquè dues compres alhora no el barregin
function guardar_tiquet(array $tiquet): bool {
    $linia = json_encode($tiquet, JSON_UNESCAPED_UNICODE) . "\n";

    // Si no s'ha pogut escriure (per exemple, per permisos), file_put_contents retorna false
    return file_put_contents(RUTA_COMANDES, $linia, FILE_APPEND | LOCK_EX) !== false;
}


// FUNCIÓ: RESTAR LES UNITATS
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


// FUNCIÓ: COMPROVAR EL NOM
// Retorna true si el text només té lletres (també amb accent), espais, apòstrofs o guions
function nom_correcte(string $text): bool {
    $especials = ['à', 'á', 'è', 'é', 'í', 'ï', 'ò', 'ó', 'ú', 'ü', 'ç', 'ñ',
                  'À', 'Á', 'È', 'É', 'Í', 'Ï', 'Ò', 'Ó', 'Ú', 'Ü', 'Ç', 'Ñ',
                  '·', "'", '’', '-', ' '];

    // Es treuen els caràcters especials permesos
    $net = str_replace($especials, '', $text);

    // El que queda han de ser només lletres de la a a la z (ctype_alpha: com ctype_digit però amb lletres)
    return ctype_alpha($net);
}


// FUNCIÓ: VALIDAR LES DADES DEL FORMULARI
// Comprova les dades del formulari i retorna el missatge d'error, o "" si tot és correcte
function validar_dades(string $nom, string $cognom, string $email, string $tel): string {

    // El nom és obligatori
    if ($nom == "") {
        return "El nom és obligatori.";
    }

    // Ha de tenir entre 2 i 50 caràcters i només lletres
    if (strlen($nom) < 2 || strlen($nom) > 50 || !nom_correcte($nom)) {
        return "El nom ha de tenir entre 2 i 50 caràcters i només lletres.";
    }

    // El cognom és opcional, però si s'escriu ha de ser vàlid
    if ($cognom != "") {
        if (strlen($cognom) < 2 || strlen($cognom) > 60 || !nom_correcte($cognom)) {
            return "El cognom ha de tenir entre 2 i 60 caràcters i només lletres.";
        }
    }

    // L'email és obligatori i ha de tenir format d'email
    if ($email == "") {
        return "L'email és obligatori.";
    }

    // Com al JS, com a màxim 100 caràcters
    if (strlen($email) > 100) {
        return "L'email no és vàlid (exemple: nom@domini.com).";
    }

    // filter_var és la funció de PHP per validar emails
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "L'email no és correcte (exemple: nom@domini.com).";
    }

    // El telèfon es mira sense espais ("612 345 678" es queda "612345678")
    $tel_sense_espais = str_replace(' ', '', $tel);

    // Ha de tenir 9 caràcters
    if (strlen($tel_sense_espais) != 9) {
        return "El telèfon ha de tenir 9 xifres.";
    }

    // I tots han de ser xifres (ctype_digit: només 0-9)
    if (!ctype_digit($tel_sense_espais)) {
        return "El telèfon només pot tenir números.";
    }

    // Si arriba aquí, tot és correcte
    return "";
}


// FUNCIÓ: HI HA COMPRA?
// Retorna true si a la sessió hi ha una compra amb algun producte
function hi_ha_compra(): bool {
    return isset($_SESSION['compra']) && count($_SESSION['compra']) > 0;
}


// FUNCIÓ: MOSTRAR EL TIQUET
// Pinta el tiquet d'una comanda
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

        <table class="taula">
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
