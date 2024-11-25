<div class="container-editor">
    <div class="editor">

        <div class="editor-buttons">
            <div>
                <button id="start-webcam">
                    <img class="editor-icon" src="/img/camera_on.svg" alt="Allumer la camera">
                </button>
                <button>
                    <label for="image-upload">
                        <img class="editor-icon" src="/img/upload.svg" alt="Importer une image">
                    <input type="file" id="image-upload" accept="image/*">
                </button>
            </div>
            <div>
                <button id="save-image">
                    <img class="editor-icon" src="/img/save.svg" alt="Sauvegarder l'image">
                </button>
                <button id="download-image" disabled>
                    <img class="editor-icon" src="/img/download.svg" alt="Télécharger l'image">
                </button>
            </div>
        </div>

        <div class="editor-interface">
            <video id="webcam" width="100%" height="auto" autoplay></video>
        </div>

        <!-- Stickers List -->
        <div class="stickers">
            <h2>Autocollants</h2>
            <div class="sticker-list">
                <?php
                // Le code PHP pour lister et afficher les stickers
                $stickerDirectory = 'img/stickers/';
                $stickers = array_diff(scandir(htmlspecialchars($stickerDirectory)), array('..', '.')); // Ignorer '.' et '..'

                foreach ($stickers as $sticker) {
                    echo '<div class="sticker">';
                    echo '<img src="' . htmlspecialchars($stickerDirectory) . htmlspecialchars($sticker) . '" alt="Sticker">';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <!-- Edited Images -->
        <div class="edited-images">
            <h2>Images Éditées</h2>
            <div class="gallery-container">
                <?php 
                foreach ($images as $image) {
                    echo '<div class="gallery-item" data-id="' . htmlspecialchars($image['id']) . '" >';
                    echo '<img src="' . htmlspecialchars($image['data']) . '" alt="Image" class="picture">';
                    echo '<button class="delete-image">Supprimer</button>';
                    echo '</div>';
                }
                ?>
            </div>

        </div>
    </div>
</div>
<script src="/js/editor.js" defer></script>
<script src="/js/webcam.js" defer></script>