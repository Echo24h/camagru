
const submitButton = document.getElementById('save-image');

submitButton.addEventListener('click', () => {
    const mainImage = document.querySelector('#main-image');
    if (!mainImage) {
        alert('Aucune image trouvée dans l\'éditeur.');
        return;
    }
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = mainImage.width;
    canvas.height = mainImage.height;
    ctx.drawImage(mainImage, 0, 0);
    const dataURL = canvas.toDataURL('image/png');

    stickers = editorInterface.querySelectorAll('.sticker');

    dataStickers = [];

    stickers.forEach(sticker => {
        const stickerRect = sticker.getBoundingClientRect();
        dataStickers.push({
            // Remove the domain from the src attribute
            src: new URL(sticker.src).pathname,
            x: stickerRect.left - mainImage.getBoundingClientRect().left,
            y: stickerRect.top - mainImage.getBoundingClientRect().top,
            width: stickerRect.width,
            height: stickerRect.height
        });
    });

    fetch('/editor/save', {
        method: 'POST',
        body: JSON.stringify({
            image: dataURL,
            stickers: dataStickers
        }),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Image enregistrée avec succès.');
            const image = data.image;
            displaySavedImage(image);
        } else {
            alert('Erreur lors de l\'enregistrement de l\'image.');
        }
    });
});

// Fonction pour télécharger l'image
function downloadImage() {
    const mainImage = document.querySelector('#main-image');
    if (!mainImage) {
        alert('Aucune image trouvée dans l\'éditeur.');
        return;
    }

    // Vérifiez que l'image est bien chargée
    if (mainImage.complete !== true) {
        alert('L\'image n\'est pas encore complètement chargée.');
        return;
    }

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');

    // Définir la taille du canvas en fonction de l'image principale
    canvas.width = mainImage.naturalWidth;  // Utilisez 'naturalWidth' et 'naturalHeight' pour la taille originale de l'image
    canvas.height = mainImage.naturalHeight;

    // Dessiner l'image principale sur le canvas
    ctx.drawImage(mainImage, 0, 0);

    // Optionnel : ajouter des autocollants ou d'autres éléments
    const stickers = editorInterface.querySelectorAll('.sticker');
    if (stickers.length > 0) {
        stickers.forEach(sticker => {
            const stickerRect = sticker.getBoundingClientRect();
            const x = stickerRect.left - mainImage.getBoundingClientRect().left;
            const y = stickerRect.top - mainImage.getBoundingClientRect().top;
            const width = stickerRect.width;
            const height = stickerRect.height;
            // Dessiner chaque sticker sur le canvas
            ctx.drawImage(sticker, x, y, width, height);
        });
    }

    // Convertir le canvas en image au format PNG
    const dataURL = canvas.toDataURL('image/png');

    // Créer un élément <a> pour le téléchargement
    const link = document.createElement('a');
    link.download = 'camagru_image.png';
    link.href = dataURL;
    link.click();
}

// Fonction pour afficher l'image enregistrée
function displaySavedImage(image) {
    const imageContainer = document.createElement('div');
    imageContainer.classList.add('thumbnail-item');
    imageContainer.setAttribute('data-id', image.id);
    $imageSrc = "/thumbnail?id=" + image.id;
    imageContainer.innerHTML = `
        <img src="${$imageSrc}" alt="Image" class="thumbnail">
        <div class="thumbnail-info">
            <button class="delete-image">Supprimer</button>
            <button class="download-image">Télécharger</button>
        </div>
    `;
    document.querySelector('.thumbnail-container').prepend(imageContainer);
    // Ajout de l'événement pour la suppression de l'image
    handleDeleteImage(imageContainer.querySelector('.delete-image'));
}

// Fonction pour réinitialiser l'éditeur
document.querySelector('#reset-editor').addEventListener('click', resetEditor);

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
    editorInterface.style.width = '100%';
    editorInterface.style.height = '720px';
    editorInterface.style.maxWidth = '1280px';
}

// Fonction pour gérer la suppression d'une image
function handleDeleteImage(button) {
    if (button) {
        button.addEventListener('click', function() {
            const imageContainer = button.parentElement;
            const imageId = imageContainer.getAttribute('data-id');
            deleteImage(imageId, imageContainer);
        });
    }
}

// Fonction pour supprimer l'image avec AJAX
function deleteImage(imageId, imageContainer) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'image/delete', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200 && xhr.responseText === 'success') {
            imageContainer.remove();
        } else {
            alert("Erreur lors de la suppression de l'image.");
        }
    };
    xhr.send('id=' + encodeURIComponent(imageId));
}

document.querySelectorAll('.delete-image').forEach(button => {
    handleDeleteImage(button);
});

document.querySelector('#download-image').addEventListener('click', downloadImage);