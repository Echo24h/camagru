
const submitButton = document.getElementById('save-image');

submitButton.addEventListener('click', () => {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const mainImage = document.querySelector('#main-image');
    if (!mainImage) {
        alert('Aucune image trouvée dans l\'éditeur.');
        return;
    }
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    try {
        const rect = mainImage.getBoundingClientRect();

        canvas.width = rect.width;
        canvas.height = rect.height;

        ctx.drawImage(mainImage, 0, 0, rect.width, rect.height);
    } catch (e) {
        alert("L'image est cassée ou invalide, impossible de la dessiner.");
        return ;
    }

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
            stickers: dataStickers,
            csrf_token: csrfToken
        }),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => {
        if (response.status === 403) {
            // Redirige vers la page de connexion en cas de 403
            window.location.href = '/logout';
            return;
        }
        if (!response.ok) {
            throw new Error(`Erreur serveur: ${response.status}`);
        }
        // Vérifie si la requête est en JSON
        if (response.headers.get('content-type').includes('application/json')) {
            return response.json();
        } else {
            window.location.href = '/logout';
            return;
        }
    })
    .then(data => {
        if (data) {
            if (data.success) {
                const image = data.image;
                const newToken = data.token_csrf;
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', newToken);
                displaySavedImage(image);
                // attendre 0.5 seconde avant de pouvoir renvoyer une autre image
                submitButton.disabled = true;
                setTimeout(() => {
                    submitButton.disabled = false;
                }, 1000);
            } else {
                alert('Erreur lors de l\'enregistrement de l\'image.');
            }
        }
    })
    .catch(error => {
        window.location.href = '/logout';
        return;
    });
});

// Fonction pour télécharger l'image
function downloadImage_current() {
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
            <button class="download-thumbnail">Télécharger</button>
        </div>
    `;
    document.querySelector('.thumbnail-container').prepend(imageContainer);
    // Ajout de l'événement pour la suppression de l'image
    handleDeleteImage(imageContainer.querySelector('.delete-image'));
    handleDownloadImage(imageContainer.querySelector('.download-thumbnail'));
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
    isStickerActive = false;
    isImageActive = false;
    updateButtonsState();
}

// Fonction pour gérer la suppression d'une image
function handleDeleteImage(button) {
    if (button) {
        button.addEventListener('click', function() {
            const imageContainer = button.parentElement.parentElement;
            const imageId = imageContainer.getAttribute('data-id');
            deleteImage(imageId, imageContainer);
        });
    }
}

function handleDownloadImage(button) {
    if (button) {
        button.addEventListener('click', function() {
            const imageContainer = button.parentElement.parentElement;
            const imageId = imageContainer.getAttribute('data-id');
            downloadImage(imageId);
        });
    }
}

// Fonction pour supprimer l'image avec AJAX
function deleteImage(imageId, imageContainer) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    data = new FormData();
    data.append('id', imageId);
    data.append('csrf_token', csrfToken);
    fetch('/image/delete', {
        method: 'POST',
        body: data
    })
    .then(response => {
        if (response.status === 403) {
            // Redirige vers la page de connexion en cas de 403
            window.location.href = '/logout';
            return;
        }
        if (!response.ok) {
            throw new Error(`Erreur serveur: ${response.status}`);
        }
        // Vérifie si la requête est en JSON
        if (response.headers.get('content-type').includes('application/json')) {
            return response.json();
        } else {
            window.location.href = '/logout';
            return;
        }
    })
    .then(data => {
        if (data) {
            if (data.success) {
                const newToken = data.token_csrf;
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', newToken);
                imageContainer.remove();
            } else {
                alert("Erreur lors de la suppression de l'image.");
            }
        }
    })
    .catch(error => {
        window.location.href = '/logout';
        return;
    });
}

function downloadImage(imageId) {
    const link = document.createElement('a');
    link.download = 'camagru_image.png';
    link.href = '/image?id=' + imageId;
    console.info(link.href)
    link.click();
}

document.querySelectorAll('.delete-image').forEach(button => {
    handleDeleteImage(button);
});

document.querySelectorAll('.download-thumbnail').forEach(button => {
    handleDownloadImage(button);
});

document.querySelector('#download-image').addEventListener('click', downloadImage_current);