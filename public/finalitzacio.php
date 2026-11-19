<?php
session_start();

require_once '../includes/funcions.php';
require_once '../includes/funcions_compra.php';

$titol = "Projecte Cantina";

$missatge = "";
$error = "";


// Si venim de compra.php, calcula la compra i la guarda a la sessió per quan s'enviï el formulari de dades
if (isset($_POST['quantitats'])) {
    $compra = calcular_compra($_POST['quantitats']);
    $_SESSION['compra'] = $compra;
    $_SESSION['total'] = calcular_total($compra);
}


// REBRE LES DADES DEL FORMULARI

if (isset($_POST['finalitzar'])) {

    $nom = trim($_POST['nom'] ?? '');
    $cognom = trim($_POST['cognom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel = trim($_POST['tel'] ?? '');

    if ($nom == "" || $email == "" || $tel == "") {

        $error = "Omple tots els camps obligatoris.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "El correu electrònic no és correcte.";

    } elseif (!hi_ha_compra()) {

        $error = "No hi ha cap producte seleccionat.";

    } else {

        // CREAR EL TIQUET

        $tiquet = [
            'numero' => uniqid(),
            'data' => date('d/m/Y H:i'),
            'nom' => $nom,
            'cognom' => $cognom,
            'email' => $email,
            'telefon' => $tel,
            'productes' => $_SESSION['compra'],
            'total' => $_SESSION['total']
        ];

        // Si el tiquet es guarda bé, es resten les unitats, es mostra el tiquet i es buida la compra de la sessió
        if (guardar_tiquet($tiquet)) {

            restar_unitats($tiquet['productes']);

            $_SESSION['tiquet'] = $tiquet;

            unset($_SESSION['compra']);
            unset($_SESSION['total']);

            $missatge = "La compra s'ha realitzat correctament!";

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


<?php if ($missatge != ""): ?>

    <!-- MISSATGE D'ÈXIT -->
    <p class="exit no-imprimir"><?php echo htmlspecialchars($missatge); ?></p>

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
                    <?php echo htmlspecialchars($producte['nom']); ?>
                </td>

                <td>
                    <?php echo format_preu($producte['preu']); ?>
                </td>

                <td>
                    <?php echo $producte['quantitat']; ?>
                </td>

                <td>
                    <?php echo format_preu($producte['subtotal']); ?>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>
    </div>

    <p class="finalitzacio__total">
        <strong>Total:</strong>
        <strong><?php echo format_preu($_SESSION['total']); ?></strong>
    </p>

    </section>

    <!-- FORMULARI -->

    <section class="finalitzacio__dades">

    <h2>Dades de l'usuari</h2>

    <?php if ($error != ""): ?>
        <p class="error">
            <?php echo htmlspecialchars($error); ?>
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
