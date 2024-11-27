
const submitButton = document.getElementById('save-image');

// Fonction pour enregistrer l'image
function saveImage() {
    const images = editorInterface.querySelectorAll('img');
    if (images.length === 0) {
        alert("Aucune image trouvée dans l'éditeur.");
        return;
    }

    const canvas = createCanvas();
    const ctx = canvas.getContext('2d');
    const rect = editorInterface.getBoundingClientRect();
    ctx.fillStyle = window.getComputedStyle(editorInterface).backgroundColor || "#ffffff";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    let imagesLoaded = 0;
    images.forEach(img => {
        const imgRect = img.getBoundingClientRect();
        const tempImage = new Image();
        tempImage.crossOrigin = "anonymous";
        tempImage.src = img.src;

        tempImage.onload = function () {
            ctx.drawImage(tempImage, imgRect.left - rect.left, imgRect.top - rect.top, imgRect.width, imgRect.height);
            imagesLoaded++;
            if (imagesLoaded === images.length) {
                const base64Image = canvas.toDataURL('image/png');
                sendImageToServer(base64Image);
            }
        };

        tempImage.onerror = function () {
            alerte("Impossible de charger l'image : ", img.src);
        };
    });
}

// Fonction pour créer un canvas
function createCanvas() {
    const canvas = document.createElement('canvas');
    const rect = editorInterface.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = rect.height;
    return canvas;
}

// Fonction pour envoyer l'image au serveur
function sendImageToServer(base64Image) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "image/save", true);
    xhr.responseType = "json";
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            const image = xhr.response.image;
            displaySavedImage(image);
        } else {
            alerte("Erreur lors de l'envoi de l'image.");
        }
    };
    xhr.send("image=" + encodeURIComponent(base64Image));
}

// Fonction pour afficher l'image enregistrée
function displaySavedImage(image) {
    const imageContainer = document.createElement('div');
    imageContainer.classList.add('gallery-item');
    imageContainer.setAttribute('data-id', image.id);
    imageContainer.innerHTML = `
        <img src="${image.data}" alt="Image" class="picture">
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

// Ajout de l'événement pour le bouton de sauvegarde
submitButton.addEventListener('click', saveImage);

document.querySelectorAll('.delete-image').forEach(button => {
    handleDeleteImage(button);
});