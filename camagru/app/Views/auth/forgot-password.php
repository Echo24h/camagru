<h1>Mot de passe oublié</h1>

<div class="container container-auth">
    <?php if (isset($error)): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <p class="message success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form action="/forgot-password" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
        <label for="email">Email :</label>
        <input type="text" id="email" name="email" autocomplete="email" required>

        <button type="submit">Envoyer un email de réinitialisation</button>
    </form>

    <p>Vous n'avez pas de compte ? <a href="/register">Inscrivez-vous</a></p>
</div>