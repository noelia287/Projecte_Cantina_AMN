<?php
    $titol = "Projecte Cantina";
    require_once "../includes/funcions.php";
    require_once "../includes/funcions_admin.php";
    require_once "../includes/funcions_compra.php";
    $error_sidebar = "";
    $producteEditat = null;

    // Apartat de l'administració: "productes" (per defecte) o "tiquets"
    $apartat = 'productes';
    if (($_GET['apartat'] ?? '') === 'tiquets') {
        $apartat = 'tiquets';
    }

    // Eliminar un producte (arriba per POST des del botó de la taula)
    if (isset($_POST['eliminar'])) {
        eliminar_producte((int) $_POST['eliminar']);

        header('Location: admin.php');
        exit;
    }

    // Guardar el sidebar (arriba per POST amb el camp "id"; id 0 = producte nou)
    if (isset($_POST['id'])) {
        $dades = llegir_dades_producte($_POST);
        $nou = $dades['id'] === 0;

        if ($nou) {
            $actual = producte_buit();
        } else {
            $actual = buscar_producte(llegir_productes(), $dades['id']);
        }

        // Si s'edita un producte que ja no existeix, es torna a la llista
        if ($actual === null) {
            header('Location: admin.php');
            exit;
        }

        $error_sidebar = validar_producte($dades);
        if ($error_sidebar == "") {
            // Un producte nou ha de tenir imatge; en un d'existent és opcional
            $error_sidebar = validar_imatge($_FILES['imatge'] ?? [], $nou);
        }

        if ($error_sidebar == "") {
            $imatge = guardar_imatge($_FILES['imatge'] ?? []);

            if ($nou && $imatge === "") {
                $error_sidebar = "No s'ha pogut guardar la imatge.";
            } elseif ($nou) {
                $guardat = crear_producte($dades, $imatge);
            } else {
                $guardat = actualitzar_producte($dades, $imatge);
            }

            if ($error_sidebar == "" && $guardat) {
                header('Location: admin.php');
                exit;
            }

            if ($error_sidebar == "") {
                $error_sidebar = "Error en guardar el producte.";
            }
        }

        // Si hi ha error, el sidebar es torna a obrir amb el que havia escrit l'usuari
        $producteEditat = array_merge($actual, $dades);
    }

    $productes = llegir_productes();
    $comandes = llegir_comandes();
    $tiquetObert = null;

    // Apartat de tiquets: el sidebar mostra el tiquet de la URL (?apartat=tiquets&tiquet=NUMERO)
    if ($apartat === 'tiquets' && isset($_GET['tiquet'])) {
        $tiquetObert = buscar_tiquet($comandes, (string) $_GET['tiquet']);
    }

    // Apartat de productes, si no venim d'un error: ?nou=1 per crear o ?editar=ID per editar
    if ($apartat === 'productes' && $producteEditat === null) {
        if (isset($_GET['nou'])) {
            $producteEditat = producte_buit();
        } else {
            $producteEditat = buscar_producte($productes, (int) ($_GET['editar'] ?? 0));
        }
    }

?>
<?php
    require_once '../includes/layout.php';
    layout_inici($titol);
?>
<main>
    <h1>Panell d'administració</h1>

    <!-- Pestanyes: la de l'apartat actiu es marca amb aria-current -->
    <nav class="admin__pestanyes" aria-label="Apartats de l'administració">
        <a href="admin.php" <?= $apartat === 'productes' ? 'aria-current="page"' : '' ?>>
            Productes <span class="admin__comptador"><?= count($productes) ?></span>
        </a>
        <a href="admin.php?apartat=tiquets" <?= $apartat === 'tiquets' ? 'aria-current="page"' : '' ?>>
            Tiquets <span class="admin__comptador"><?= count($comandes) ?></span>
        </a>
    </nav>

<?php if ($apartat === 'productes'): ?>

    <div class="admin__capcalera">
        <h2>Productes</h2>
        <a href="admin.php?nou=1" class="admin__afegir"><i class="fa-solid fa-plus" aria-hidden="true"></i> Afegir producte</a>
    </div>
    <table class="taula-admin">
        <thead>
            <tr>
                <th>Imatge</th>
                <th>Nom</th>
                <th>Descripció</th>
                <th>Preu</th>
                <th>Unitats</th>
                <th>Franges</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($productes as $p): ?>
            <tr>
                <td>
                    <?php if (!empty($p['imatge'])): ?>
                        <img src="../frontend/public/<?= htmlspecialchars($p['imatge']) ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
                    <?php else: ?>
                        <div class="sense-imatge"><i class="fa-solid fa-image"></i></div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['nom']) ?></td>
                <td><?= htmlspecialchars($p['descripcio']) ?></td>
                <td><?= number_format($p['preu'], 2, ',', '.') ?> €</td>
                <td><?= (int)($p['unitats'] ?? 0) ?></td>
                <td><?= htmlspecialchars(implode(', ', array_map('ucfirst', $p['franges'] ?? []))) ?></td>
                <td class="accions">
                    <a href="admin.php?editar=<?= (int)$p['id'] ?>" class="btn-accio editar" title="Editar" aria-label="Editar <?= htmlspecialchars($p['nom']) ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                    <form method="POST" action="admin.php" onsubmit="return confirm('Segur que vols eliminar aquest producte?');">
                        <input type="hidden" name="eliminar" value="<?= (int)$p['id'] ?>">
                        <button type="submit" class="btn-accio eliminar" title="Eliminar" aria-label="Eliminar <?= htmlspecialchars($p['nom']) ?>"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>

    <div class="admin__capcalera">
        <h2>Tiquets</h2>
    </div>

    <?php if (count($comandes) == 0): ?>

        <p>Encara no hi ha tiquets.</p>

    <?php else: ?>

        <?php
            // Total recaptat: suma del total de tots els tiquets
            $recaptat = 0;
            foreach ($comandes as $c) {
                $recaptat += (float) $c['total'];
            }
        ?>
        <p class="admin__resum">
            <?= count($comandes) ?> tiquets · Total recaptat: <strong><?= format_preu($recaptat) ?></strong>
        </p>

        <div class="taula-scroll">
            <table class="taula-admin">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Data</th>
                        <th>Client</th>
                        <th>Unitats</th>
                        <th>Total</th>
                        <th>Accions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($comandes as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['numero']) ?></td>
                        <td><?= htmlspecialchars($c['data']) ?></td>
                        <td><?= htmlspecialchars($c['nom'] . ' ' . $c['cognom']) ?></td>
                        <td><?= unitats_tiquet($c) ?></td>
                        <td><?= format_preu((float) $c['total']) ?></td>
                        <td class="accions">
                            <a href="admin.php?apartat=tiquets&tiquet=<?= urlencode($c['numero']) ?>" class="btn-accio veure" title="Veure el tiquet" aria-label="Veure el tiquet <?= htmlspecialchars($c['numero']) ?>"><i class="fa-solid fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

<?php endif; ?>
</main>
<?php
if ($producteEditat !== null) {
    include '../includes/menu_sidebar.php';
}

if ($tiquetObert !== null) {
    include '../includes/menu_sidebar_tiquet.php';
}
?>
<?php layout_final(); ?>
