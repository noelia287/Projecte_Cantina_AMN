<!-- Panell lateral dret per editar un producte -->
<aside class="sidebar" id="sidebar-producte" aria-label="Editar producte" >

    <div class="sidebar__capcalera">
        <h2><?= $producteEditat['id'] == 0 ? 'Nou producte' : 'Editar producte' ?></h2>
        <a href="admin.php" class="sidebar__tancar" aria-label="Tancar el panell"><i class="fa-solid fa-x"></i></a>
    </div>

    <?php if (!empty($error_sidebar)): ?>
        <p class="error"><?= htmlspecialchars($error_sidebar) ?></p>
    <?php endif; ?>

    <form class="sidebar__formulari" method="POST" action="admin.php" enctype="multipart/form-data">

        <!-- Identificador del producte (no es pot editar) -->
        <input type="hidden" name="id" id="producte-id" value="<?= (int) $producteEditat['id'] ?>">

        <label for="producte-nom">Nom<span>*</span>:</label>
        <input type="text" id="producte-nom" name="nom" value="<?= htmlspecialchars($producteEditat['nom']) ?>" required>

        <label for="producte-descripcio">Descripció:</label>
        <textarea id="producte-descripcio" name="descripcio" rows="3"><?= htmlspecialchars($producteEditat['descripcio']) ?></textarea>

        <label for="producte-preu">Preu (€)<span>*</span>:</label>
        <input type="number" id="producte-preu" name="preu" min="0" step="0.01" value="<?= htmlspecialchars((string) $producteEditat['preu']) ?>" required>

        <label for="producte-preu-oferta">Preu d'oferta (€):</label>
        <input type="number" id="producte-preu-oferta" name="preu_oferta" min="0" step="0.01" value="<?= htmlspecialchars((string) $producteEditat['preu_oferta']) ?>">
        <small>0 = sense oferta</small>

        <label for="producte-unitats">Unitats disponibles<span>*</span>:</label>
        <input type="number" id="producte-unitats" name="unitats" min="0" step="1" value="<?= htmlspecialchars((string) $producteEditat['unitats']) ?>" required>

        <fieldset>
            <legend>Franges horàries<span>*</span>:</legend>

            <label for="franja-esmorzar">
                <input type="checkbox" id="franja-esmorzar" name="franges[]" value="esmorzar" <?= in_array('esmorzar', $producteEditat['franges']) ? 'checked' : '' ?>> Esmorzar
            </label>
            <label for="franja-dinar">
                <input type="checkbox" id="franja-dinar" name="franges[]" value="dinar" <?= in_array('dinar', $producteEditat['franges']) ? 'checked' : '' ?>> Dinar
            </label>
            <label for="franja-berenar">
                <input type="checkbox" id="franja-berenar" name="franges[]" value="berenar" <?= in_array('berenar', $producteEditat['franges']) ? 'checked' : '' ?>> Berenar
            </label>
        </fieldset>

        <label for="producte-imatge">Imatge<?= $producteEditat['id'] == 0 ? '<span>*</span>' : '' ?>:</label>
        <?php if (!empty($producteEditat['imatge'])): ?>
            <img id="producte-imatge-previa" src="../frontend/public/<?= htmlspecialchars($producteEditat['imatge']) ?>" alt="<?= htmlspecialchars($producteEditat['nom']) ?>">
        <?php endif; ?>
        <input type="file" id="producte-imatge" name="imatge" accept="image/jpeg,image/png,image/webp" <?= $producteEditat['id'] == 0 ? 'required' : '' ?>>

        <div class="sidebar__accions">
            <button type="submit">Desa</button>
            <a href="admin.php" class="sidebar__cancelar">Cancel·la</a>
        </div>

    </form>
</aside>
