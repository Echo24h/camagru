
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

    // Si l'image est un GIF on la laisse telle quelle
    dataURL = '';
    // Récupère les premiers caractères de la source de l'image
    const isGif = mainImage.src.startsWith('data:image/gif');
    if (isGif) {
        dataURL = mainImage.src;
    }
    else {
        dataURL = canvas.toDataURL('image/png');
    }
    if (!dataURL) {
        alert('Erreur lors de la sauvegarde de l\'image.');
        return;
    }

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

// Fonction pour afficher l'image enregistrée
function displaySavedImage(image) {
    const imageContainer = document.createElement('div');
    imageContainer.classList.add('gallery-item');
    imageContainer.setAttribute('data-id', image.id);
    imageSrc = '/image?id=' + image.id;
    imageContainer.innerHTML = `
        <img src="${imageSrc}" alt="Image" class="picture">
        <button class="delete-image">Supprimer</button>
    `;
    document.querySelector('.gallery-container').prepend(imageContainer);
    // Ajout de l'événement pour la suppression de l'image
    handleDeleteImage(imageContainer.querySelector('.delete-image'));
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