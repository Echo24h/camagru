<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie d'Images</title>
    <link rel="stylesheet" href="/css/galery.css" type="text/css">
</head>
<body>
    <header>
        <img class="logo" src="img/logo.png" alt="Logo">
        <h1>Galerie d'Images</h1>
        <a href="/">Editeur</a>
    </header>

    <div class="gallery-container">
        <?php
        // Le code PHP pour lister et afficher les images
        foreach ($images as $image) {
            echo '<div class="gallery-item">';
            echo '<img class="picture" src="' . $image['data'] . '" alt="Image">';
            echo '<div class="item-info">';
            // Créateur avec lien
            echo '<div class="creator"><a href="' . $image['username'] . '">' . $image['username'] . '</a></div>';
            // Commentaires
            echo '<div class="comments"><img class="icon" src="img/comment.svg" alt="Commentaire"> ' . $image['total_comments'] . '</div>';
            // J'aime
            if ($image['total_likes'] > 0)
                echo '<div class="likes"><img class="icon" src="img/like.svg" alt="J\'aime"> ' . $image['total_likes'] . '</div>';
            else
                echo '<div class="likes"><img class="icon" src="img/like_empty.svg" alt="J\'aime"></div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>