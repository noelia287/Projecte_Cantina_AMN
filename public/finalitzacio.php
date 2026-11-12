
<?php
session_start();

$titol = "Projecte Cantina";

$missatge = "";
$error = "";

// REBRE LES DADES DE LA COMPRA

if (isset($_POST['quantitats'])) {

    $productes = json_decode(file_get_contents('../data/productes.json'), true);

    $compra = [];
    $total = 0;

    foreach ($productes as $producte) {

        $id = $producte['id'];
        $quantitat = (int)($_POST['quantitats'][$id] ?? 0);

        if ($quantitat > 0 && $quantitat <= 20 && $producte['disponible']) {

            $subtotal = $producte['preu'] * $quantitat;

            $compra[] = [
                'nom' => $producte['nom'],
                'preu' => $producte['preu'],
                'quantitat' => $quantitat,
                'subtotal' => $subtotal
            ];

            $total += $subtotal;
        }
    }

    $_SESSION['compra'] = $compra;
    $_SESSION['total'] = $total;
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

    } elseif (!isset($_SESSION['compra']) || count($_SESSION['compra']) == 0) {

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

        // CREAR LA CARPETA

        if (!is_dir('../comandes')) {
            mkdir('../comandes', 0755, true);
        }

        // GUARDAR EL TIQUET

        $fitxer = '../comandes/tiquet_' . $tiquet['numero'] . '.json';

        if (file_put_contents($fitxer, json_encode($tiquet, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false) {

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
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titol) ?></title>
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/src/js/main.js"></script>
    <link rel="icon" type="image/png" href="../frontend/public/logo.png">
</head>
<body>
<?php
include '../includes/header.php';
?>
<main>

<h1>Finalització de la compra</h1>


<?php if ($missatge != ""): ?>

    <!-- TIQUET FINAL -->

    <?php $tiquet = $_SESSION['tiquet']; ?>

    <div class="zona-tiquet">

        <div class="tiquet">

            <div class="tiquet-capcalera">
                <h2>CANTINA</h2>
                <p>TIQUET DE COMPRA</p>
                <p>Gràcies per la vostra visita!</p>
            </div>

            <hr>

            <p>
                <strong>Ticket:</strong>
                <?php echo htmlspecialchars($tiquet['numero']); ?>
            </p>

            <p>
                <strong>Data:</strong>
                <?php echo htmlspecialchars($tiquet['data']); ?>
            </p>

            <hr>

            <p><strong>CLIENT</strong></p>

            <p>
                <?php echo htmlspecialchars($tiquet['nom'] . ' ' . $tiquet['cognom']); ?>
            </p>

            <p>
                <?php echo htmlspecialchars($tiquet['email']); ?>
            </p>

            <p>
                <?php echo htmlspecialchars($tiquet['telefon']); ?>
            </p>

            <hr>

            <p class="text-centre">
                <strong>DETALL DE LA COMPRA</strong>
            </p>

            <table class="taula-tiquet">

                <tr>
                    <th>Producte</th>
                    <th>U.</th>
                    <th>Preu</th>
                </tr>

                <?php foreach ($tiquet['productes'] as $producte): ?>

                    <tr>
                        <td>
                            <?php echo htmlspecialchars($producte['nom']); ?>
                        </td>

                        <td>
                            <?php echo $producte['quantitat']; ?>
                        </td>

                        <td>
                            <?php echo number_format($producte['subtotal'], 2, ',', '.'); ?> €
                        </td>
                    </tr>

                <?php endforeach; ?>

            </table>

            <hr>

            <div class="total-tiquet">
                <span>TOTAL</span>

                <span>
                    <?php echo number_format($tiquet['total'], 2, ',', '.'); ?> €
                </span>
            </div>

            <hr>

            <div class="tiquet-peu">
                <p>Gràcies per comprar a la nostra cantina!</p>
            </div>

        </div>

        <p class="no-imprimir">
            <a href="compra.php">Tornar a comprar</a>
        </p>

    </div>


<?php elseif (isset($_SESSION['compra']) && count($_SESSION['compra']) > 0): ?>

    <!-- RESUM DE LA COMPRA -->

    <h2>Resum de la compra</h2>

    <table border="1" cellpadding="10">

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
                    <?php echo number_format($producte['preu'], 2, ',', '.'); ?> €
                </td>

                <td>
                    <?php echo $producte['quantitat']; ?>
                </td>

                <td>
                    <?php echo number_format($producte['subtotal'], 2, ',', '.'); ?> €
                </td>
            </tr>

        <?php endforeach; ?>

    </table>

    <h3>
        Total:
        <?php echo number_format($_SESSION['total'], 2, ',', '.'); ?> €
    </h3>

    <hr>

    <!-- FORMULARI -->

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

        <button type="submit" name="finalitzar" value="1">
            Envia
        </button>

        <button type="reset" id="reseteja">
            Reseteja
        </button>

    </form>



<?php else: ?>

    <p>No has seleccionat cap producte.</p>

    <a href="compra.php">Tornar a comprar</a>

<?php endif; ?>


</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
