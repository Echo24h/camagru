<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="/css/styles.css" type="text/css">
</head>
<body>

    <img class ="logo" src="/img/logo.png" alt="Logo" type="image/png">
    <h1>Connexion</h1>

    <div class="container">
        <?php if (isset($error)): ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="message success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form action="/login" method="POST">
            <label for="email">Email :</label>
            <input type="text" id="email" name="email" autocomplete="email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required>

            <button type="submit">Se connecter</button>
        </form>

        <p>Vous n'avez pas de compte ? <a href="/register">Inscrivez-vous</a></p>
        <p>Mot de passe oublié ? <a href="/forgot-password">Réinitialisez-le</a></p>
    </div>
</body>
</html>