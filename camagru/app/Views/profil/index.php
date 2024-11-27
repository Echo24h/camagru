<h1><? echo htmlspecialchars($user['username']); ?></h1>
<div class="container container-user">
    <div class="user-info">   
        <?php if ($user['email_visibility'] == 1): ?>
            <p><? echo htmlspecialchars($user['email']); ?></p>
        <?php endif; ?>
        <p>Inscrit le: <? echo htmlspecialchars(date('d/m/Y', strtotime($user['created_at']))); ?></p>
        <p>Nombre de likes reçu: <? echo htmlspecialchars($likes_received); ?></p>
    </div>

    <?php
        if ($images === null || empty($images)) {
            echo '<div class=no-content>';
            echo '<p>Aucune image pour l\'instant</p>';
            if ($user['id'] == $_SESSION['user_id']) {
                echo '<a href="/">Publier la première image !</a>';
            }
            echo '</div>';
        }
        else {
            echo '<div class="gallery-container">';
        
            foreach ($images as $image) {
                echo '<div class="gallery-item" data-id="' . htmlspecialchars($image['id']) . '">';
                // Clic sur l'image redirige vers la page show
                echo '<a href="/gallery/show?id=' . htmlspecialchars($image['id']) . '"><img class="picture" src="image?id=' . htmlspecialchars($image['id']) . '" alt="Image"></a>';
                echo '<div class="item-info">';
                // Créateur avec lien
                echo '<div class="creator"><a href=/profil?id=' . htmlspecialchars($user['id']) . '>' . htmlspecialchars($user['username']) . '</a></div>';
                // Clic sur les commentaires redirige vers la page show
                echo '<div class="comments"><a href="/gallery/show?id=' . htmlspecialchars($image['id']) . '"><img class="icon" src="img/comment.svg" alt="Commentaire"> ' . htmlspecialchars($image['total_comments']) . '</a></div>';
                // J'aime avec clic AJAX
                echo '<div class="likes" data-id="' . htmlspecialchars($image['id']) . '">';
                if (htmlspecialchars($image['total_likes']) > 0) {
            
                    echo '<img class="icon" src="img/like.svg" alt="J\'aime">';
                    echo '<p>' . htmlspecialchars($image['total_likes']) . '</p>';
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
</div>

<script src="/js/gallery.js" defer></script>