<h1>Inscription</h1>

<div class="container container-auth">
    <?php if (isset($error)): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="/register" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" autocomplete="username" required>

        <label for="email">Adresse e-mail :</label>
        <input type="email" id="email" name="email" autocomplete="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" autocomplete="new-password" required>

        <button type="submit">S'inscrire</button>
    </form>

    <p>Vous avez déjà un compte ? <a href="/login">Connectez-vous</a></p>
    <p>Mot de passe oublié ? <a href="/forgot-password">Réinitialisez-le</a></p>
</div>