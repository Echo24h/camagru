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
    <h1>Nouveau mot de passe</h1>

    <div class="container">
        <?php if (isset($error)): ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="message success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form action="/reset-password?id=<?= htmlspecialchars($id) ?>&token=<?= htmlspecialchars($token) ?>" method="POST">
            <label for="password">Nouveau mot de passe :</label>
            <input type="password" id="password" name="password" autocomplete="new-password" required>
            <button type="submit">Réinitialiser le mot de passe</button>
        </form>
    </div>
</body>
</html>