const imageUpload = document.getElementById('image-upload');
const editorInterface = document.querySelector('.editor-interface');
const submitButton = document.getElementById('save-image');

// Ajouter un écouteur d'événement sur l'input de fichier
imageUpload.addEventListener('change', function(event) {
    const file = event.target.files[0]; // Récupérer le premier fichier sélectionné

    if (file) {
        const reader = new FileReader(); // Utiliser FileReader pour lire le fichier localement

        // Lorsque le fichier est chargé, l'afficher dans l'interface
        reader.onload = function(e) {
            const imgElement = document.createElement('img'); // Créer un élément img
            imgElement.src = e.target.result; // Définir la source de l'image
            imgElement.style.position = 'absolute'; // Définir la position absolue pour l'image
            imgElement.style.cursor = 'move'; // Définir un curseur de déplacement
            editorInterface.appendChild(imgElement); // Ajouter l'image à l'éditeur

            // Variables pour gérer la suppression après 2 secondes
            let holdTimeout;
            let isHolding = false;

            // Ajouter un événement mousedown pour commencer à tenir l'image
            imgElement.addEventListener('mousedown', function(e) {
                // Réinitialiser la bordure avant de commencer l'animation
                imgElement.style.border = '2px solid transparent';
                imgElement.style.transition = 'border-width 2s linear'; // Transition sur la taille de la bordure

                // Lancer le délai pour détecter le maintien
                holdTimeout = setTimeout(() => {
                    if (isHolding) {
                        imgElement.remove(); // Supprimer l'image après 2 secondes
                    }
                }, 2000);

                // Marquer que l'image est en cours de maintien
                isHolding = true;

                // Animation de la bordure qui devient plus épaisse
                setTimeout(() => {
                    imgElement.style.border = '20px solid red'; // Augmenter la taille de la bordure
                }, 0);
            });

            // Ajouter un événement mouseup pour annuler si l'utilisateur relâche avant 2 secondes
            imgElement.addEventListener('mouseup', function(e) {
                clearTimeout(holdTimeout); // Annuler le délai
                imgElement.style.border = ''; // Retirer la bordure rouge
                isHolding = false; // Réinitialiser l'état
            });

            // Gérer le déplacement de l'image
            let isDragging = false;
            let offsetX, offsetY;
            let activeImage = null;

            imgElement.addEventListener('click', function(e) {
                if (isDragging) {
                    isDragging = false;
                    imgElement.style.cursor = 'move'; // Rétablir le curseur
                } else {
                    isDragging = true;
                    activeImage = imgElement;
                    offsetX = e.clientX - imgElement.getBoundingClientRect().left;
                    offsetY = e.clientY - imgElement.getBoundingClientRect().top;
                    imgElement.style.cursor = 'grabbing'; // Changer le curseur lors du déplacement
                }
            });

            window.addEventListener('mousemove', function(e) {
                if (isDragging && activeImage) {
                    const editorRect = editorInterface.getBoundingClientRect();
                    let x = e.clientX - offsetX - editorRect.left;
                    let y = e.clientY - offsetY - editorRect.top;

                    activeImage.style.left = `${x}px`;
                    activeImage.style.top = `${y}px`;
                }
            });
        };

        reader.readAsDataURL(file); // Lire le fichier en tant qu'URL de données
    }
});

// Ajouter un écouteur d'événement sur le bouton "submit"
submitButton.addEventListener('click', function () {
    // récupérer toutes les images
    const images = editorInterface.querySelectorAll('img');

    if (images.length === 0) {
        console.error("Aucune image trouvée dans l'éditeur.");
        return;
    }

    // Créer un canvas
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');

    // Récupérer les dimensions du conteneur
    const rect = editorInterface.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = rect.height;

    // Dessiner l'arrière-plan
    ctx.fillStyle = window.getComputedStyle(editorInterface).backgroundColor || "#ffffff";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Charger et dessiner chaque image
    let imagesLoaded = 0;
    images.forEach((img) => {
        const imgRect = img.getBoundingClientRect();

        // Créer une nouvelle image pour charger les données
        const tempImage = new Image();
        tempImage.crossOrigin = "anonymous"; // Gérer les restrictions CORS pour les images externes
        tempImage.src = img.src;

        tempImage.onload = function () {
            // Dessiner l'image à la position relative
            ctx.drawImage(tempImage, imgRect.left - rect.left, imgRect.top - rect.top, imgRect.width, imgRect.height);

            // Vérifier si toutes les images ont été chargées et dessinées
            imagesLoaded++;
            if (imagesLoaded === images.length) {
                // Convertir le canvas en Base64 une fois toutes les images chargées
                const base64Image = canvas.toDataURL('image/png');

                // Envoyer l'image au serveur
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "image/save", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        window.location.href = "/";
                    }
                    else {
                        console.error("Erreur lors de l'envoi de l'image.");
                    }
                };
                xhr.send("image=" + encodeURIComponent(base64Image));
            }
        };
        tempImage.onerror = function () {
            console.error("Impossible de charger l'image : ", img.src);
        };
    });
});

// Fonction pour supprimer l'image avec AJAX
document.querySelectorAll('.delete-image').forEach(button => {
    button.addEventListener('click', function() {
        const imageContainer = this.closest('.edited-image');
        const imageId = imageContainer.getAttribute('data-id');
        console.log("Suppression de l'image avec l'ID :", imageId);

        // Créer une requête AJAX pour supprimer l'image
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'image/delete?id=' + imageId, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Quand la requête est terminée
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Vérifier la réponse du serveur
                if (xhr.responseText === 'success') {
                    // Supprimer l'élément image du DOM si la suppression est réussie
                    imageContainer.remove();
                } else {
                    alert("Erreur lors de la suppression de l'image.");
                }
            }
        };
        // Envoyer les données
        xhr.send('id=' + encodeURIComponent(imageId));
    });
});