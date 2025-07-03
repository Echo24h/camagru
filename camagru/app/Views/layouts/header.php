<header class="header">
    <a href="/gallery" class="logo">
        <img src="/img/logo.png" alt="Logo">
    </a>
    <nav class="navbar">
        <div class="navbar-toggle">
            <button class="navbar-toggle-button">☰</button>
        </div>
        <div class="navbar-other">
            <a class="navbar-link" href="/gallery">Galerie</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="navbar-link" href="/">Editeur</a>
                <a class="navbar-link" href="/settings">Paramètres</a>
            <?php endif; ?>
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
        <div class="navbar-responsive">
            <div class="navbar-responsive-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                <?php echo "<a class=\"navbar-link\" href=\"/profil?id=" . htmlspecialchars($_SESSION['user_id']) . "\">Profil</a>" ?>
                    <a class="navbar-link" href="/logout">Déconnexion</a>
                <?php else: ?>
                    <a class="navbar-link" href="/register">Inscription</a>
                    <a class="navbar-link" href="/login">Connexion</a>
                <?php endif; ?>
                <a class="navbar-link" href="/gallery">Galerie</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a class="navbar-link" href="/">Editeur</a>
                    <a class="navbar-link" href="/settings">Paramètres</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <script src="/js/navbar.js" defer></script>
</header>