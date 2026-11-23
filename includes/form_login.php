<!-- Formulari d'accés a l'administració -->
<section class="login">
    <h1>Accés a l'administració</h1>

    <?php if ($error_login != ""): ?>
        <p class="error"><?= htmlspecialchars($error_login) ?></p>
    <?php endif; ?>

    <form method="POST" action="admin.php">

        <label for="usuari">Usuari:</label>
        <input type="text" id="usuari" name="usuari" autocomplete="username" required autofocus>

        <label for="clau">Contrasenya:</label>
        <input type="password" id="clau" name="clau" autocomplete="current-password" required>

        <div class="login__accions">
            <button type="submit" name="login" value="1">Entra</button>
        </div>

    </form>
</section>
