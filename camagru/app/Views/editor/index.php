<div class="container-editor">
    <div class="editor">

        <div class="editor-buttons-container">
            <div class="editor-buttons">
                <div>
                    <button id="toggle-webcam">
                        <img id="toggle-icon" class="editor-icon" src="/img/camera-on.svg" alt="Allumer la camera">
                    </button>
                    <button id="take-picture" disabled>
                        <img class="editor-icon" src="/img/screen.svg" alt="Prendre une photo">
                    </button>
                    <button>
                        <label for="image-upload">
                            <img class="editor-icon" src="/img/upload.svg" alt="Importer une image">
                        <input type="file" id="image-upload" accept="image/*">
                    </button>
                </div>
                <h1>Editeur</h1>
                <div>
                    <button id="save-image">
                        <img class="editor-icon" src="/img/save.svg" alt="Sauvegarder l'image">
                    </button>
                    <button id="download-image" disabled>
                        <img class="editor-icon" src="/img/download.svg" alt="Télécharger l'image">
                    </button>
                </div>
            </div>
        </div>

        <div class="container-center">
            <div class="editor-interface">
                <video id="webcam" width="100%" height="auto" autoplay></video>
            </div>
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
                    echo '<img src="/image?id=' . htmlspecialchars($image['id']) . '" alt="Image" class="picture">';
                    echo '<button class="delete-image">Supprimer</button>';
                    echo '</div>';
                }
                ?>
            </div>

        </div>
    </div>
</div>
<script src="/js/editor.js" defer></script>
<script src="/js/editor_webcam.js" defer></script>
<script src="/js/editor_upload.js" defer></script>
<script src="/js/editor_stickers.js" defer></script>