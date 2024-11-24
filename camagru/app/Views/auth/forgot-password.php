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
    <h1>Mot de passe oublié</h1>

    <div class="container">
        <?php if (isset($error)): ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="message success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form action="/forgot-password" method="POST">
            <label for="email">Email :</label>
            <input type="text" id="email" name="email" autocomplete="email" required>

            <button type="submit">Envoyer un email de réinitialisation</button>
        </form>

        <p>Vous n'avez pas de compte ? <a href="/register">Inscrivez-vous</a></p>
    </div>
</body>
</html>