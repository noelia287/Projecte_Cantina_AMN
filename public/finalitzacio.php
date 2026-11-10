<?php
$titol = "Projecte Cantina";
include '../includes/header.php';
?>
    <script src="../fronted/src/js/main.js"></script>
    <form id="formulari" novalidate>
        <label for="nom">Nom<span>*</span>:</label>
        <input id="nom" name="nom" >
        <p class="error" id="error-nom"></p>

        <label for="cognom">Cognom:</label>
        <input id="cognom" name="cognom">
        <p class="error" id="error-cognom"></p>

        <label for="email">E-mail<span>*</span>:</label>
        <input id="email" name="email" >
        <p class="error" id="error-email"></p>

        <label for="tel">Telèfon<span>*</span>:</label>
        <input id="tel" name="tel" >
        <p class="error" id="error-tel"></p>

        <button>Envia</button>
        <button type="button" id="reseteja">Reseteja</button>
    </form>

<?php
include '../includes/footer.php';
?>