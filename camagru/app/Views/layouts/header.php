<header class="header">
    <a href="/gallery" class="logo">
        <img src="/img/logo.png" alt="Logo">
    </a>
    <nav class="navbar">
        <div>
            <a class="navbar-link" href="/gallery">Galerie</a>
            <a class="navbar-link" href="/">Editeur</a>
            <a class="navbar-link" href="/settings">Paramètres</a>
        </div>
        <div class="navbar-login">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php echo "<p>Hello <a href=\"/profil?id=" . htmlspecialchars($_SESSION['user_id']) . "\">" . htmlspecialchars($_SESSION['username']) . "</a> !</p>" ?>
                <a class="navbar-link" href="/logout">Déconnexion</a>
            <?php else: ?>
                <a class="navbar-link" href="/register">Inscription</a>
                <a class="navbar-link" href="/login">Connexion</a>
            <?php endif; ?>
        </div>
    </nav>
</header>