<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Édition d'image</title>
    <link rel="stylesheet" href="/css/home.css" type="text/css">
</head>
<body>
    <header>
        <img class="logo" src="logo.png" alt="Logo" type="image/png">
        <h1>Édition d'image</h1>
        <div id="container-profil">
            <p>Hello <?= htmlspecialchars($_SESSION['username']) ?> !</p>
            <a href="/profil">Profil</a>
            <a href="/logout"> Déconnexion</a>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="editor">
                <!-- Webcam Preview -->
                <div class="webcam-preview">
                    <h2>Prévisualisation Webcam</h2>
                    <video id="webcam" width="100%" height="auto" autoplay></video>
                    <button id="start-webcam">Démarrer Webcam</button>
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
                        <img src="sticker1.png" alt="Sticker 1" class="sticker">
                        <img src="sticker2.png" alt="Sticker 2" class="sticker">
                        <img src="sticker3.png" alt="Sticker 3" class="sticker">
                    </div>
                </div>

                <!-- Edited Images -->
                <div class="edited-images">
                    <h2>Images Éditées</h2>
                    <div class="image-thumbnails">
                        <img src="image1-thumbnail.jpg" alt="Image 1" class="thumbnail">
                        <img src="image2-thumbnail.jpg" alt="Image 2" class="thumbnail">
                    </div>
                </div>

                <!-- Save and Download -->
                <div class="actions">
                    <button id="save-image" disabled>Sauvegarder l'image</button>
                    <button id="download-image" disabled>Télécharger l'image</button>
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