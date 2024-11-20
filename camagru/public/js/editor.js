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
submitButton.addEventListener('click', function() {
    // Récupérer toutes les images dans l'éditeur
    const images = editorInterface.querySelectorAll('img');
    
    // Si une image existe, on l'envoie
    if (images.length > 0) {
        const base64Image = images[0].src; // On suppose qu'on envoie la première image ajoutée

        // Créer une requête AJAX pour envoyer l'image au serveur
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "upload_image.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log("Image envoyée et sauvegardée dans la base de données.");
            }
        };

        // Envoyer l'image en Base64
        xhr.send("image=" + encodeURIComponent(base64Image));
    }
});