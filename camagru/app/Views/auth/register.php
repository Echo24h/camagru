<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Lien vers le fichier CSS externe -->
    <link rel="stylesheet" href="/css/styles.css" type="text/css">
</head>
<body>
    <h1>Inscription</h1>

    <div class="container">
        <?php if (isset($error)): ?>
            <p class="message error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="/register" method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Adresse e-mail :</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">S'inscrire</button>
        </form>

        <p>Vous avez déjà un compte ? <a href="/login">Connectez-vous</a></p>
    </div>
</body>
</html>