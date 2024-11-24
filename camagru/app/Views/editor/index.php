<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Édition d'image</title>
    <link rel="stylesheet" href="/css/home.css" type="text/css">
    <script src="/js/webcam.js" defer></script>
    <script src="/js/editor.js" defer></script>
</head>
<body>
    <header>
        <img class="logo" src="/img/logo.png" alt="Logo" type="image/png">
        <h1>Édition d'image</h1>
        <div id="container-profil">
            <p>Hello <?= htmlspecialchars($_SESSION['username']) ?> !</p>
            <a href="/gallery">Galerie</a>
            <a href="/profil?id=<? echo htmlspecialchars($user_id); ?>">Mon profil</a>
            <a href="/settings">Paramètres</a>
            <a href="/logout"> Déconnexion</a>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="editor">

                <!-- Save and Download -->
                <div class="actions">
                    <button id="save-image">Sauvegarder l'image</button>
                    <button id="download-image" disabled>Télécharger l'image</button>
                </div>

                <div class="editor-interface">

                </div>

                <!-- Image Upload -->
                <div class="upload-image">
                    <h2>Télécharger une image</h2>
                    <input type="file" id="image-upload" accept="image/*">
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
                    <div class="image-thumbnails">
                        <?php 
                        foreach ($images as $image) {
                            echo '<div class="edited-image" data-id="' . htmlspecialchars($image['id']) . '" >';
                            echo '<img src="' . htmlspecialchars($image['data']) . '" alt="Image" class="thumbnail">';
                            echo '<button class="delete-image">Supprimer</button>';
                            echo '</div>';
                        }
                        ?>
                    </div>

                </div>

                <!-- Webcam Preview -->
                <div class="webcam-preview">
                    <h2>Prévisualisation Webcam</h2>
                    <video id="webcam" width="100%" height="auto" autoplay></video>
                    <button id="start-webcam">Démarrer Webcam</button>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Votre Application. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>