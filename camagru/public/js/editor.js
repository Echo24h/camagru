const imageUpload = document.getElementById('image-upload');
const editorInterface = document.querySelector('.editor-interface');
const submitButton = document.getElementById('save-image');

// Fonction pour afficher l'image dans l'éditeur
function displayImage(file) {
    const reader = new FileReader();
    reader.onload = function(e) {

        // Suppression de l'ancienne image
        const oldImage = document.querySelector('#main-image');
        if (oldImage) {
            oldImage.remove();
        }

        // Création de la nouvelle image
        const imgElement = createDraggableImage(e.target.result);
        imgElement.id = 'main-image';

        // Ajout de l'image à l'éditeur
        editorInterface.appendChild(imgElement);
        handleImageEvents(imgElement);

        // Réinitialisation de l'input
        imageUpload.value = '';

        // Redimensionnement de l'editor
        editorInterface.style.width = width + 'px';
        editorInterface.style.height = height + 'px';

    };
    reader.readAsDataURL(file);
}

// Fonction pour créer un élément img et appliquer des styles
function createDraggableImage(src) {
    const imgElement = document.createElement('img');
    imgElement.src = src;
    imgElement.style.position = 'absolute';
    imgElement.style.cursor = 'move';
    return imgElement;
}

// Fonction pour gérer les événements liés à l'image (déplacement, suppression)
function handleImageEvents(imgElement) {
    let isHolding = false;
    let holdTimeout;

    // Gestion du maintien pour suppression
    imgElement.addEventListener('mousedown', function() {
        imgElement.style.border = '2px solid transparent';
        imgElement.style.transition = 'border-width 2s linear';

        holdTimeout = setTimeout(() => {
            if (isHolding) {
                imgElement.remove();
            }
        }, 2000);

        isHolding = true;
        imgElement.style.border = '20px solid red';
    });

    // Annulation du maintien
    imgElement.addEventListener('mouseup', function() {
        clearTimeout(holdTimeout);
        imgElement.style.border = '';
        isHolding = false;
    });

    // Gestion du déplacement de l'image
    let isDragging = false;
    let offsetX, offsetY;

    imgElement.addEventListener('click', function(e) {
        if (isDragging) {
            isDragging = false;
            imgElement.style.cursor = 'move';
        } else {
            isDragging = true;
            offsetX = e.clientX - imgElement.getBoundingClientRect().left;
            offsetY = e.clientY - imgElement.getBoundingClientRect().top;
            imgElement.style.cursor = 'grabbing';
        }
    });

    window.addEventListener('mousemove', function(e) {
        if (isDragging) {
            const editorRect = editorInterface.getBoundingClientRect();
            let x = e.clientX - offsetX - editorRect.left;
            let y = e.clientY - offsetY - editorRect.top;
            imgElement.style.left = `${x}px`;
            imgElement.style.top = `${y}px`;
        }
    });
}

// Fonction pour gérer l'upload des images
imageUpload.addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        displayImage(file);
    }
});

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
    imageContainer.classList.add('edited-image');
    imageContainer.setAttribute('data-id', image.id);
    imageContainer.innerHTML = `
        <img src="${image.data}" alt="Image" class="thumbnail">
        <button class="delete-image">Supprimer</button>
    `;
    document.querySelector('.image-thumbnails').prepend(imageContainer);
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