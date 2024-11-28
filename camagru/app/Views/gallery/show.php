
<h1>Détails de l'Image</h1>

<div class="image-details">
<!-- Image -->
    <div class="image-container">
        <?php echo '<img src="/image?id=' . htmlspecialchars($image['id']) . '" alt="Image" class="full-image">' ?>
    </div>

    <!-- Informations sur l'image -->
    <div class="image-info">
        <div class="creator">
            Créée par: <a href="/profil?id=<?php echo htmlspecialchars($image['user_id']); ?>"><?php echo htmlspecialchars($image['username']); ?></a>
        </div>
        <?php
            echo '<div class="likes" data-id="' . $image['id'] . '">';
            if (htmlspecialchars($image['total_likes']) > 0) {
                echo '<img class="icon" src="/img/like.svg" alt="J\'aime">';
            } else {
                echo '<img class="icon" src="/img/like_empty.svg" alt="J\'aime">';
            }
            echo '<p>' . htmlspecialchars($image['total_likes']) . '</p>';
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
                        <strong><a href="/profil?id=<? echo htmlspecialchars($comment['user_id']); ?>"><?php echo htmlspecialchars($comment['username']); ?>:</a></strong> <?php echo htmlspecialchars($comment['content']); ?>
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
        <form method="POST" action="/image/comment">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($image['id']); ?>">
            <label for="comment">Commentaire:</label>
            <textarea name="comment" id="comment" required></textarea>
            <button type="submit">Ajouter un commentaire</button>
        </form>
    </div>
</div>

<script src="/js/gallery_show.js" defer></script>