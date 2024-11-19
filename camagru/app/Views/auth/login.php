<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
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
            <input type="text" id="email" name="email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Se connecter</button>
        </form>

        <p>Vous n'avez pas de compte ? <a href="/register">Inscrivez-vous</a></p>
    </div>
</body>
</html>