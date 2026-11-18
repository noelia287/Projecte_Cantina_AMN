<?php
    $titol = "Projecte Cantina";
    require_once "../includes/funcions.php";
    $productes = llegir_productes();
?>
<?php
    require_once '../includes/layout.php';
    layout_inici($titol);
?>
<main>
    <h1>Productes</h1>
    <table class="taula-productes">
        <thead>
            <tr>
                <th>Imatge</th>
                <th>Nom</th>
                <th>Descripció</th>
                <th>Preu</th>
                <th>Unitats</th>
                <th>Accions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($productes as $p): ?>
            <tr>
                <td>
                    <?php if (!empty($p['imatge'])): ?>
                        <img src="<?= htmlspecialchars($p['imatge']) ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
                    <?php else: ?>
                        <div class="sense-imatge"><i class="fa-solid fa-image"></i></div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['nom']) ?></td>
                <td><?= htmlspecialchars($p['descripcio']) ?></td>
                <td><?= number_format($p['preu'], 2, ',', '.') ?> €</td>
                <td><?= (int)($p['unitats'] ?? 0) ?></td>
                <td class="accions">
                    <a href="editar.php?id=<?= (int)$p['id'] ?>" class="btn-accio editar" title="Editar" aria-label="Editar <?= htmlspecialchars($p['nom']) ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="eliminar.php?id=<?= (int)$p['id'] ?>" class="btn-accio eliminar" title="Eliminar" aria-label="Eliminar <?= htmlspecialchars($p['nom']) ?>"><i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php layout_final(); ?>
