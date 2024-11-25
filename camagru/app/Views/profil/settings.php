<h1>Paramètres</h1>

<div class="container container-settings">
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
    <div class="settings-item">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <button type="submit" name="update_username">Mettre à jour</button>
    </form>

    <!-- Modification de l'email -->
    <form action="/settings" method="POST">
        <div class="settings-item">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <button type="submit" name="update_email">Mettre à jour</button>
    </form>

    <!-- Modification du mot de passe -->
    <form action="/settings" method="POST">
    <div class="settings-item">
        <label for="password">Nouveau mot de passe :</label>
        <input type="password" id="password" autocomplete="new-password" name="password" placeholder="Entrez un nouveau mot de passe">
        </div>
        <button type="submit" name="update_password">Mettre à jour</button>
    </form>

    <!-- Visibilité de l'email -->
    <form action="/settings" method="POST">
        <div class="checkbox-group">
            <label for="email_visibility">Rendre l'email public :</label>
            <input type="checkbox" id="email_visibility" name="email_visibility" <?= htmlspecialchars($user['email_visibility']) ? 'checked' : '' ?>>
        </div>
    </form>

    <!-- Modification des notifications -->
    <form action="/settings" method="POST">
        <div class="checkbox-group">
            <label for="notifications">Activer les notifications par email :</label>
            <input type="checkbox" id="notifications" name="notifications" <?= htmlspecialchars($user['notifications']) ? 'checked' : '' ?>>
        </div>
    </form>

    <script src="/js/settings.js" defer></script>
</div>