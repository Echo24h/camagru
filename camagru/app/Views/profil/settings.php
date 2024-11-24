<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres Individuels</title>
    <link rel="stylesheet" href="/css/styles.css" type="text/css">
</head>
<body>

    <img class="logo" src="/img/logo.png" alt="Logo">
    <h1>Paramètres</h1>

    <div class="container">
        <?php if (isset($error)): ?>
            <p class="message error">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="message success">
                <?= htmlspecialchars($success) ?>
            </p>
        <?php endif; ?>

        <!-- Modification du nom d'utilisateur -->
        <form action="/settings" method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            <button type="submit" name="update_username">Mettre à jour</button>
        </form>

        <!-- Modification de l'email -->
        <form action="/settings" method="POST">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <button type="submit" name="update_email">Mettre à jour</button>
        </form>

        <!-- Modification du mot de passe -->
        <form action="/settings" method="POST">
            <label for="password">Nouveau mot de passe :</label>
            <input type="password" id="password" name="password" placeholder="Entrez un nouveau mot de passe">
            <button type="submit" name="update_password">Mettre à jour</button>
        </form>

        <!-- Modification des notifications -->
        <form action="/settings" method="POST">
            <div class="checkbox-group">
                <label for="notifications">Activer les notifications :</label>
                <input type="checkbox" id="notifications" name="notifications" <?= $notificationsEnabled ? 'checked' : '' ?>>
            </div>
            <button type="submit" name="update_notifications">Mettre à jour</button>
        </form>
    </div>

</body>
</html>