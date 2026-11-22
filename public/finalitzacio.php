<?php
session_start();

require_once '../includes/layout.php';
require_once '../includes/funcions_compra.php';

$titol = "Finalització de la compra";
$error = "";


// LÒGICA: GUARDAR LA COMPRA A LA SESSIÓ
// Si venim de compra.php, guarda la compra a la sessió i torna a carregar la pàgina amb GET
if (isset($_POST['quantitats'])) {
    $_SESSION['compra'] = calcular_compra($_POST['quantitats']);
    unset($_SESSION['tiquet']);

    header('Location: finalitzacio.php');
    exit;
}


// LÒGICA: PROCESSAR EL FORMULARI
// Si s'ha enviat el formulari i hi ha productes a la compra, es comproven les dades
if (isset($_POST['finalitzar']) && hi_ha_compra()) {

    $nom = trim($_POST['nom'] ?? '');
    $cognom = trim($_POST['cognom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel = trim($_POST['tel'] ?? '');

    $error = validar_dades($nom, $cognom, $email, $tel);

    // Si les dades són correctes, es crea el tiquet i es guarda
    if ($error == "") {
        $tiquet = crear_tiquet($nom, $cognom, $email, $tel, $_SESSION['compra']);

        // Si s'ha guardat bé, es resten les unitats, es guarda el tiquet a la sessió i es buida la compra
        if (guardar_tiquet($tiquet)) {
            restar_unitats($tiquet['productes']);

            $_SESSION['tiquet'] = $tiquet;
            unset($_SESSION['compra']);

            header('Location: finalitzacio.php');
            exit;
        } else {
            $error = "Error en guardar el tiquet.";
        }
    }
}

layout_inici($titol);
?>
<main>

<h1>Finalització de la compra</h1>

<?php if (isset($_SESSION['tiquet'])): ?>

    <!-- Compra feta: missatge d'èxit i tiquet final -->
    <p class="exit">La compra s'ha realitzat correctament!</p>

    <div class="zona-tiquet">
        <?php mostrar_tiquet($_SESSION['tiquet']); ?>

        <p>
            <a href="compra.php">Tornar a comprar</a>
        </p>
    </div>

<?php elseif (hi_ha_compra()): ?>

    <div class="finalitzacio">

        <!-- Resum de la compra -->
        <section class="finalitzacio__resum">
            <h2>Resum de la compra</h2>

            <div class="taula-scroll">
                <table class="taula">
                    <tr>
                        <th>Producte</th>
                        <th>Preu</th>
                        <th>Quantitat</th>
                        <th>Subtotal</th>
                    </tr>

                    <?php foreach ($_SESSION['compra'] as $producte): ?>
                        <tr>
                            <td><?= htmlspecialchars($producte['nom']) ?></td>
                            <td><?= format_preu($producte['preu']) ?></td>
                            <td><?= $producte['quantitat'] ?></td>
                            <td><?= format_preu($producte['subtotal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <p class="finalitzacio__total">
                <strong>Total:</strong>
                <strong><?= format_preu(calcular_total($_SESSION['compra'])) ?></strong>
            </p>
        </section>

        <!-- Formulari amb les dades de l'usuari -->
        <section class="finalitzacio__dades">
            <h2>Dades de l'usuari</h2>

            <?php if ($error != ""): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <!-- Si el PHP ha trobat un error, els camps tornen amb el que havia escrit l'usuari -->
            <form id="formulari" method="POST" action="finalitzacio.php" novalidate>

                <label for="nom">Nom<span>*</span>:</label>
                <input id="nom" name="nom" value="<?= htmlspecialchars($nom ?? '') ?>">
                <p class="error" id="error-nom"></p>

                <label for="cognom">Cognom:</label>
                <input id="cognom" name="cognom" value="<?= htmlspecialchars($cognom ?? '') ?>">
                <p class="error" id="error-cognom"></p>

                <label for="email">E-mail<span>*</span>:</label>
                <input id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
                <p class="error" id="error-email"></p>

                <label for="tel">Telèfon<span>*</span>:</label>
                <input id="tel" name="tel" value="<?= htmlspecialchars($tel ?? '') ?>">
                <p class="error" id="error-tel"></p>

                <div class="formulari__accions">
                    <button type="submit" name="finalitzar" value="1">Envia</button>
                    <button type="reset" id="reseteja">Reseteja</button>
                </div>

            </form>
        </section>

    </div>

<?php else: ?>

    <!-- No hi ha compra: s'ha entrat directament o s'ha enviat sense productes -->
    <p>No has seleccionat cap producte.</p>

    <a href="compra.php">Tornar a comprar</a>

<?php endif; ?>

</main>

<?php layout_final(); ?>
