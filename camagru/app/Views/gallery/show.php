<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Image</title>
    <link rel="stylesheet" href="/css/show.css" type="text/css">
    <script src="/js/gallery.js" defer></script>
</head>
<body>
    <header>
        <img class="logo" src="/img/logo.png" alt="Logo">
        <h1>Détails de l'Image</h1>
        <a href="/gallery">Retour à la Galerie</a>
    </header>

    <div class="image-details">
        <!-- Image -->
        <div class="image-container">
            <?php echo '<img src="' . $image['data'] . '" alt="Image" class="full-image">' ?>
        </div>

        <!-- Informations sur l'image -->
        <div class="image-info">
            <div class="creator">
                Créée par: <a href="<?php echo $image['username']; ?>"><?php echo $image['username']; ?></a>
            </div>
            <?php
                echo '<div class="likes" data-id="' . $image['id'] . '">';
                if ($image['total_likes'] > 0) {
                    echo '<p>' . $image['total_likes'] . '</p>';
                    echo '<img class="icon" src="/img/like.svg" alt="J\'aime">';
                } else {
                    echo '<img class="icon" src="/img/like_empty.svg" alt="J\'aime">';
                }
                echo '</div>';
            ?>
        </div>

        <!-- Commentaires -->
        <div class="comments-section">
            <h2>Commentaires</h2>
            <?php if (count($comments) > 0): ?>
                <ul class="comment-list">
                    <?php foreach ($comments as $comment): ?>
                        <li class="comment-item">
                            <strong><?php echo $comment['username']; ?>:</strong> <?php echo $comment['text']; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucun commentaire pour cette image.</p>
            <?php endif; ?>
        </div>

        <!-- Formulaire de commentaire -->
        <div class="comment-form">
            <h3>Ajouter un Commentaire</h3>
            <form method="POST" action="/image/comment?id=<?php echo $image_id; ?>">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" name="username" id="username" required>
                <label for="comment">Commentaire:</label>
                <textarea name="comment" id="comment" required></textarea>
                <button type="submit">Ajouter un commentaire</button>
            </form>
        </div>
    </div>
</body>
</html>