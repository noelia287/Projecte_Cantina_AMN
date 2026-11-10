
<?php
session_start();

$titol = "Projecte Cantina";

$missatge = "";
$error = "";

// Rebre les dades de la compra
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


// Rebre les dades del formulari
if (isset($_POST['finalitzar'])) {

    $nom = trim($_POST['nom'] ?? '');
    $cognom = trim($_POST['cognom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel = trim($_POST['tel'] ?? '');

    if ($nom == "" || $email == "" || $tel == "") {

        $error = "Omple tots els camps obligatoris.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "El correu electrònic no és correcte.";

    } else {

        // Crear el tiquet
        $tiquet = [
            'data' => date('d/m/Y H:i'),
            'nom' => $nom,
            'cognom' => $cognom,
            'email' => $email,
            'telefon' => $tel,
            'productes' => $_SESSION['compra'],
            'total' => $_SESSION['total']
        ];

        // Guardar el tiquet en un fitxer
        $fitxer = '../comandes/tiquet_' . uniqid() . '.json';

        if (!is_dir('../comandes')) {
            mkdir('../comandes', 0755, true);
        }

        if (file_put_contents($fitxer, json_encode($tiquet, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false) {

        unset($_SESSION['compra']);
        unset($_SESSION['total']);

        $missatge = "La compra s'ha realitzat correctament!";

        } else {
            $error = "Error en guardar el tiquet.";
            }
        }
    }

include '../includes/header.php';
?>

<h1>Finalització de la compra</h1>

<?php if ($missatge != ""): ?>

    <h2><?php echo $missatge; ?></h2>
    <p>Gràcies per comprar a la nostra cantina!</p>

    <a href="compra.php">Tornar a comprar</a>

<?php elseif (isset($_SESSION['compra']) && count($_SESSION['compra']) > 0): ?>

    <h2>Tiquet de compra</h2>

    <table border="1" cellpadding="10">

        <tr>
            <th>Producte</th>
            <th>Preu</th>
            <th>Quantitat</th>
            <th>Subtotal</th>
        </tr>

        <?php foreach ($_SESSION['compra'] as $producte): ?>

            <tr>
                <td><?php echo htmlspecialchars($producte['nom']); ?></td>
                <td><?php echo number_format($producte['preu'], 2, ',', '.'); ?> €</td>
                <td><?php echo $producte['quantitat']; ?></td>
                <td><?php echo number_format($producte['subtotal'], 2, ',', '.'); ?> €</td>
            </tr>

        <?php endforeach; ?>

    </table>

    <h3>Total: <?php echo number_format($_SESSION['total'], 2, ',', '.'); ?> €</h3>

    <hr>

    <h2>Dades de l'usuari</h2>

    <?php if ($error != ""): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <script src="../fronted/src/js/main.js"></script>

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

        <button type="submit" name="finalitzar" value="1">Envia</button>

        <button type="reset" id="reseteja">Reseteja</button>

    </form>

<?php else: ?>

    <p>No has seleccionat cap producte.</p>
    <a href="compra.php">Tornar a comprar</a>

<?php endif; ?>

<?php
include '../includes/footer.php';
?>
