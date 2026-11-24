<?php

require_once __DIR__ . '/funcions.php';

// Franges que es poden assignar a un producte
define('FRANGES_VALIDES', ['esmorzar', 'dinar', 'berenar']);

// Mida màxima de la imatge d'un producte (5 MB)
define('MIDA_MAX_IMATGE', 5 * 1024 * 1024);


// FUNCIÓ: LLEGIR LES DADES DEL FORMULARI
// Rep el $_POST del sidebar i retorna les dades netes (sense espais i només amb franges permeses)
function llegir_dades_producte(array $post): array {
    $franges = [];

    // Només es queden les franges que existeixen
    if (isset($post['franges']) && is_array($post['franges'])) {
        foreach (FRANGES_VALIDES as $franja) {
            if (in_array($franja, $post['franges'], true)) {
                array_push($franges, $franja);
            }
        }
    }

    // Si el preu d'oferta es deixa buit, es compta com a 0 (sense oferta)
    $oferta = trim($post['preu_oferta'] ?? '');
    if ($oferta === '') {
        $oferta = '0';
    }

    return [
        'id' => (int) ($post['id'] ?? 0),
        'nom' => trim($post['nom'] ?? ''),
        'descripcio' => trim($post['descripcio'] ?? ''),
        'preu' => trim($post['preu'] ?? ''),
        'preu_oferta' => $oferta,
        'unitats' => trim($post['unitats'] ?? ''),
        'franges' => $franges
    ];
}


// FUNCIÓ: VALIDAR UN PRODUCTE
// Retorna el missatge d'error, o "" si les dades són correctes
function validar_producte(array $dades): string {

    if ($dades['nom'] === '') {
        return "El nom és obligatori.";
    }

    if (preg_match_all("/./su", $dades["nom"]) > 60) {
        return "El nom no pot tenir més de 60 caràcters.";
    }

    if (!is_numeric($dades['preu']) || $dades['preu'] <= 0) {
        return "El preu ha de ser un número major que 0.";
    }

    if (!is_numeric($dades['preu_oferta']) || $dades['preu_oferta'] < 0) {
        return "El preu d'oferta ha de ser 0 o més.";
    }

    // L'oferta només té sentit si és més barata que el preu normal
    if ($dades['preu_oferta'] > 0 && $dades['preu_oferta'] >= $dades['preu']) {
        return "El preu d'oferta ha de ser menor que el preu.";
    }

    $unitats = filter_var($dades['unitats'], FILTER_VALIDATE_INT);
    if ($unitats === false || $unitats < 0) {
        return "Les unitats han de ser un nombre enter de 0 o més.";
    }

    if (count($dades['franges']) == 0) {
        return "Has de triar almenys una franja horària.";
    }

    return "";
}


// FUNCIÓ: VALIDAR LA IMATGE PUJADA
// Retorna el missatge d'error, o "" si no s'ha pujat cap imatge (i no és obligatòria) o és correcta
function validar_imatge(array $fitxer, bool $obligatoria = false): string {

    // Si no s'ha triat cap fitxer, es manté la imatge actual (excepte en un producte nou, que n'ha de tenir)
    if (($fitxer['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        if ($obligatoria) {
            return "Has de pujar una imatge per al producte.";
        }

        return "";
    }

    if ($fitxer['error'] !== UPLOAD_ERR_OK) {
        return "No s'ha pogut pujar la imatge.";
    }

    if ($fitxer['size'] > MIDA_MAX_IMATGE) {
        return "La imatge pesa massa (màxim 5 MB).";
    }

    // getimagesize mira el contingut real del fitxer, no només l'extensió
    $info = @getimagesize($fitxer['tmp_name']);
    if ($info === false) {
        return "El fitxer no és una imatge vàlida.";
    }

    if (!in_array($info['mime'], ['image/jpeg', 'image/png', 'image/webp'])) {
        return "La imatge ha de ser JPG, PNG o WEBP.";
    }

    return "";
}


// FUNCIÓ: GUARDAR LA IMATGE PUJADA
// Mou la imatge a frontend/public amb un nom nou i retorna el nom ("" si no hi ha imatge o falla)
function guardar_imatge(array $fitxer): string {
    if (($fitxer['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return "";
    }

    // L'extensió es tria segons el tipus real de la imatge
    $info = getimagesize($fitxer['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    // Nom nou i únic, perquè no trepitgi cap imatge que ja hi hagi
    $nom = 'producte-' . uniqid() . '.' . $extensions[$info['mime']];

    if (move_uploaded_file($fitxer['tmp_name'], __DIR__ . '/../frontend/public/' . $nom)) {
        return $nom;
    }

    return "";
}


// FUNCIÓ: ACTUALITZAR UN PRODUCTE
// Canvia les dades del producte amb aquest id. Si no hi ha imatge nova, es manté l'actual
// Retorna false si el producte no existeix o no s'ha pogut guardar
function actualitzar_producte(array $dades, string $imatge = ''): bool {
    $productes = llegir_productes();
    $trobat = false;

    foreach ($productes as $i => $producte) {
        if ($producte['id'] === $dades['id']) {
            $trobat = true;

            // Sense oferta es guarda un 0 enter, com ja hi és al JSON
            $oferta = (float) $dades['preu_oferta'];
            if ($oferta == 0) {
                $oferta = 0;
            }

            $productes[$i]['nom'] = $dades['nom'];
            $productes[$i]['descripcio'] = $dades['descripcio'];
            $productes[$i]['preu'] = round((float) $dades['preu'], 2);
            $productes[$i]['preu_oferta'] = $oferta;
            $productes[$i]['unitats'] = (int) $dades['unitats'];
            $productes[$i]['franges'] = $dades['franges'];

            if ($imatge !== '') {
                $productes[$i]['imatge'] = $imatge;
            }
        }
    }

    if (!$trobat) {
        return false;
    }

    return guardar_productes($productes);
}


// FUNCIÓ: PRODUCTE BUIT
// Dades per defecte del sidebar quan es crea un producte nou (id 0 vol dir que encara no existeix)
function producte_buit(): array {
    return [
        'id' => 0,
        'nom' => '',
        'descripcio' => '',
        'preu' => '',
        'preu_oferta' => 0,
        'unitats' => 0,
        'imatge' => '',
        'franges' => []
    ];
}


// FUNCIÓ: CREAR UN PRODUCTE
// Afegeix un producte nou al final del JSON amb l'id següent al més gran que hi ha
// Retorna false si no s'ha pogut guardar
function crear_producte(array $dades, string $imatge): bool {
    $productes = llegir_productes();

    // Id nou: el més gran dels existents + 1 (1 si encara no n'hi ha cap)
    $id = 1;
    foreach ($productes as $producte) {
        if ($producte['id'] >= $id) {
            $id = $producte['id'] + 1;
        }
    }

    // Sense oferta es guarda un 0 enter, com ja hi és al JSON
    $oferta = (float) $dades['preu_oferta'];
    if ($oferta == 0) {
        $oferta = 0;
    }

    array_push($productes, [
        'id' => $id,
        'nom' => $dades['nom'],
        'descripcio' => $dades['descripcio'],
        'preu' => round((float) $dades['preu'], 2),
        'unitats' => (int) $dades['unitats'],
        'imatge' => $imatge,
        'preu_oferta' => $oferta,
        'franges' => $dades['franges']
    ]);

    return guardar_productes($productes);
}


// FUNCIÓ: LLEGIR LES COMANDES
// Llegeix comandes.txt (una comanda en JSON per línia) i les retorna de la més recent a la més antiga
// Les línies buides, que no siguin JSON o a les quals els falti alguna dada s'ignoren
function llegir_comandes(): array {
    if (!file_exists(RUTA_COMANDES)) {
        return [];
    }

    // Si no es pot obrir (per exemple, per permisos), es retorna una llista buida
    $fitxer = fopen(RUTA_COMANDES, 'r');
    if ($fitxer === false) {
        return [];
    }

    $comandes = [];
    $obligatoris = ['numero', 'data', 'nom', 'cognom', 'email', 'telefon', 'productes', 'total'];

    // Es llegeix una línia cada vegada fins al final del fitxer
    while (!feof($fitxer)) {
        $linia = trim(fgets($fitxer));

        // Línia buida (per exemple, la de després de l'últim salt de línia)
        if ($linia === '') {
            continue;
        }

        $tiquet = json_decode($linia, true);

        if (!is_array($tiquet) || !is_array($tiquet['productes'] ?? null)) {
            continue;
        }

        // Si falta alguna dada, la línia no es pot mostrar bé
        $correcte = true;
        foreach ($obligatoris as $camp) {
            if (!array_key_exists($camp, $tiquet)) {
                $correcte = false;
            }
        }

        if ($correcte) {
            array_push($comandes, $tiquet);
        }
    }

    fclose($fitxer);

    return array_reverse($comandes);
}


// FUNCIÓ: BUSCAR UN TIQUET
// Retorna el tiquet amb aquest número, o null si no existeix
function buscar_tiquet(array $comandes, string $numero): ?array {
    foreach ($comandes as $tiquet) {
        if ((string) $tiquet['numero'] === $numero) {
            return $tiquet;
        }
    }

    return null;
}


// FUNCIÓ: UNITATS D'UN TIQUET
// Retorna el nombre total d'unitats compradas en un tiquet
function unitats_tiquet(array $tiquet): int {
    $unitats = 0;

    foreach ($tiquet['productes'] as $producte) {
        $unitats += (int) ($producte['quantitat'] ?? 0);
    }

    return $unitats;
}


// FUNCIÓ: L'ADMINISTRADOR HA ENTRAT? (Caducitat sessió)
// Retorna true si a la sessió hi ha l'administrador identificat i no ha caducat
function admin_validat(): bool {
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        return false;
    }

    // Si ha passat massa temps sense activitat, la sessió caduca
    $inactiu = time() - ($_SESSION['ultima_activitat'] ?? 0);
    if ($inactiu > ADMIN_CADUCITAT) {
        unset($_SESSION['admin'], $_SESSION['ultima_activitat']);
        return false;
    }

    // Encara és vàlida: es renova el temps
    $_SESSION['ultima_activitat'] = time();
    return true;
}


// FUNCIÓ: COMPROVAR LES CREDENCIALS
// Retorna true si l'usuari i la contrasenya coincideixen amb els de config.php (la contrasenya es compara amb el hash)
function credencials_correctes(string $usuari, string $clau): bool {
    return $usuari === ADMIN_USUARI && md5($clau) === ADMIN_CLAU_HASH;
}
