<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie d'Images</title>
    <link rel="stylesheet" href="/css/gallery.css" type="text/css">
    <script src="/js/gallery.js" defer></script>
</head>
<body>
    <header>
        <img class="logo" src="/img/logo.png" alt="Logo">
        <h1>Galerie d'Images</h1>
        <a href="/">Editeur</a>
    </header>

    <div class="gallery-container">
        <?php
        // Le code PHP pour lister et afficher les images
        foreach ($images as $image) {
            echo '<div class="gallery-item" data-id="' . $image['id'] . '">';
            // Clic sur l'image redirige vers la page show
            echo '<a href="/gallery/show?id=' . $image['id'] . '"><img class="picture" src="' . $image['data'] . '" alt="Image"></a>';
            echo '<div class="item-info">';
            // Créateur avec lien
            echo '<div class="creator"><a href="' . $image['username'] . '">' . $image['username'] . '</a></div>';
            // Clic sur les commentaires redirige vers la page show
            echo '<div class="comments"><a href="/gallery/show?id=' . $image['id'] . '"><img class="icon" src="img/comment.svg" alt="Commentaire"> ' . $image['total_comments'] . '</a></div>';
            // J'aime avec clic AJAX
            echo '<div class="likes" data-id="' . $image['id'] . '">';
            if ($image['total_likes'] > 0) {
                echo '<p>' . $image['total_likes'] . '</p>';
                echo '<img class="icon" src="img/like.svg" alt="J\'aime">';   
            } else {
                echo '<img class="icon" src="img/like_empty.svg" alt="J\'aime">';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>