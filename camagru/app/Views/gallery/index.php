<?php
if ($images === null || empty($images)) {
    echo '<div class=no-content>';
    echo '<p>Aucune image pour l\'instant</p>';
    echo '<a href="/">Publier la première image !</a>';
    echo '</div>';
}
else {
    echo '<div class="gallery-container">';

    foreach ($images as $image) {
        echo '<div class="gallery-item" data-id="' . htmlspecialchars($image['id']) . '">';
        // Clic sur l'image redirige vers la page show
        echo '<a href="/gallery/show?id=' . htmlspecialchars($image['id']) . '"><img class="picture" src="' . htmlspecialchars($image['data']) . '" alt="Image"></a>';
        echo '<div class="item-info">';
        // Créateur avec lien
        echo '<div class="creator"><a href="profil?id=' . htmlspecialchars($image['user_id']) . '">' . htmlspecialchars($image['username']) . '</a></div>';
        // Clic sur les commentaires redirige vers la page show
        echo '<div class="comments"><a href="/gallery/show?id=' . htmlspecialchars($image['id']) . '"><img class="icon" src="img/comment.svg" alt="Commentaire"> ' . htmlspecialchars($image['total_comments']) . '</a></div>';
        // J'aime avec clic AJAX
        echo '<div class="likes" data-id="' . htmlspecialchars($image['id']) . '">';
        if (htmlspecialchars($image['total_likes']) > 0) {
            echo '<p>' . htmlspecialchars($image['total_likes']) . '</p>';
            echo '<img class="icon" src="img/like.svg" alt="J\'aime">';   
        } else {
            echo '<img class="icon" src="img/like_empty.svg" alt="J\'aime">';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
}
?>
<script src="/js/gallery.js" defer></script>    