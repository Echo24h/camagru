const imageUpload = document.getElementById('image-upload');
const editorInterface = document.querySelector('.editor-interface');

function resetEditor() {
    // Suppression de l'ancienne image et des stickers
    const oldImage = document.querySelector('#main-image');
    const allStickers = editorInterface.querySelectorAll('.sticker');
    if (oldImage) {
        oldImage.remove();
    }
    allStickers.forEach(sticker => {
        sticker.remove();
    });
}

// Fonction pour upload l'image dans l'éditeur
imageUpload.addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {

            // Suppression de l'ancienne image et des stickers
            resetEditor(true);

            // Création de la nouvelle image
            const imgElement = document.createElement('img');
            imgElement.src = e.target.result;
            imgElement.id = 'main-image';

            // Ajout de l'image à l'éditeur
            editorInterface.appendChild(imgElement);
            // handleImageEvents(imgElement);

            // Récupération de la largeur et de la hauteur de l'image
            imgElement.onload = function() {
                const width = imgElement.naturalWidth;
                const height = imgElement.naturalHeight;

                // Vérification et redimensionnement si l'image est trop grande
                const maxSize = 1200; // La taille maximale autorisée en px
                if (width > maxSize || height > maxSize) {
                    // Calcul de la mise à l'échelle pour garder les proportions
                    scale = maxSize / Math.max(width, height);
                    // Redimensionnement de l'image
                    var canvas = document.createElement('canvas');
                    var ctx = canvas.getContext('2d');
                    canvas.width = width * scale;
                    canvas.height = height * scale;
                    ctx.drawImage(imgElement, 0, 0, canvas.width, canvas.height);
                    imgElement.src = canvas.toDataURL('image/png');
                }
            };

            const video = document.querySelector('#webcam');
            video.style.display = 'none';

            // Réinitialisation de l'input
            imageUpload.value = '';
        };
        reader.readAsDataURL(file);
    }
});
