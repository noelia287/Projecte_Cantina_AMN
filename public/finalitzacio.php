<?php
session_start();

require_once '../includes/funcions.php';
require_once '../includes/funcions_compra.php';

$titol = "Projecte Cantina";

$error = "";


// Si venim de compra.php, guarda la compra a la sessió i torna a carregar la pàgina amb GET
if (isset($_POST['quantitats'])) {
    $_SESSION['compra'] = calcular_compra($_POST['quantitats']);
    unset($_SESSION['tiquet']);

    header('Location: finalitzacio.php');
    exit;
}


// REBRE LES DADES DEL FORMULARI

if (isset($_POST['finalitzar'])) {

    $nom = trim($_POST['nom'] ?? '');
    $cognom = trim($_POST['cognom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel = trim($_POST['tel'] ?? '');

    $error = validar_dades($nom, $email, $tel);

    // Encara que les dades estiguin bé, cal que hi hagi alguna cosa a la compra
    if ($error == "" && !hi_ha_compra()) {
        $error = "No hi ha cap producte seleccionat.";
    }

    // Si no hi ha cap error, es crea i es guarda el tiquet
    if ($error == "") {

        // CREAR EL TIQUET

        $tiquet = [
            'numero' => uniqid(),
            'data' => date('d/m/Y H:i'),
            'nom' => $nom,
            'cognom' => $cognom,
            'email' => $email,
            'telefon' => $tel,
            'productes' => $_SESSION['compra'],
            'total' => calcular_total($_SESSION['compra'])
        ];

        // Si el tiquet es guarda bé, es resten les unitats, es guarda el tiquet a la sessió i es buida la compra
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

?>
<?php
require_once '../includes/layout.php';
layout_inici($titol);
?>
<main>

<h1>Finalització de la compra</h1>


<?php if (isset($_SESSION['tiquet'])): ?>

    <!-- MISSATGE D'ÈXIT -->
    <p class="exit no-imprimir">La compra s'ha realitzat correctament!</p>

    <!-- TIQUET FINAL -->
    <div class="zona-tiquet">

        <?php mostrar_tiquet($_SESSION['tiquet']); ?>

        <p class="no-imprimir">
            <a href="compra.php">Tornar a comprar</a>
        </p>

    </div>

<?php elseif (hi_ha_compra()): ?>

    <div class="finalitzacio">

    <!-- RESUM DE LA COMPRA -->

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
                <td>
                    <?= htmlspecialchars($producte['nom']) ?>
                </td>

                <td>
                    <?= format_preu($producte['preu']) ?>
                </td>

                <td>
                    <?= $producte['quantitat'] ?>
                </td>

                <td>
                    <?= format_preu($producte['subtotal']) ?>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>
    </div>

    <p class="finalitzacio__total">
        <strong>Total:</strong>
        <strong><?= format_preu(calcular_total($_SESSION['compra'])) ?></strong>
    </p>

    </section>

    <!-- FORMULARI -->

    <section class="finalitzacio__dades">

    <h2>Dades de l'usuari</h2>

    <?php if ($error != ""): ?>
        <p class="error">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form id="formulari" method="POST" action="finalitzacio.php" novalidate>

        <label for="nom">Nom<span>*</span>:</label>
        <input id="nom" name="nom">
        <p class="error" id="error-nom"></p>

        <label for="cognom">Cognom:</label>
        <input id="cognom" name="cognom">
        <p class="error" id="error-cognom"></p>

        <label for="email">E-mail<span>*</span>:</label>
        <input id="email" name="email">
        <p class="error" id="error-email"></p>

        <label for="tel">Telèfon<span>*</span>:</label>
        <input id="tel" name="tel">
        <p class="error" id="error-tel"></p>

        <div class="formulari__accions">
            <button type="submit" name="finalitzar" value="1">
                Envia
            </button>

            <button type="reset" id="reseteja">
                Reseteja
            </button>
        </div>

    </form>

    </section>

    </div>



<?php else: ?>

    <p>No has seleccionat cap producte.</p>

    <a href="compra.php">Tornar a comprar</a>

<?php endif; ?>


</main>

<?php layout_final(); ?>
