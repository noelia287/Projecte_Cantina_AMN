<?php
    $titol = "Projecte Cantina";
    require_once "../includes/funcions.php";
    require_once "../includes/funcions_admin.php";
    $error_sidebar = "";
    $producteEditat = null;

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

    // Si no venim d'un error, el sidebar s'obre segons la URL: ?nou=1 per crear o ?editar=ID per editar
    if ($producteEditat === null) {
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
    <div class="admin__capcalera">
        <h1>Productes</h1>
        <a href="admin.php?nou=1" class="admin__afegir"><i class="fa-solid fa-plus" aria-hidden="true"></i> Afegir producte</a>
    </div>
    <table class="taula-productes">
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
</main>
<?php
if ($producteEditat !==null) {
    include '../includes/menu_sidebar.php';
}
?>
<?php layout_final(); ?>
