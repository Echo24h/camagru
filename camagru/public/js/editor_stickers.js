// Gere l'evenement click sur les stickers
document.querySelectorAll('.sticker').forEach(sticker => {
    sticker.addEventListener('click', function() {
        // Récupère la source de l'image (.sticker img) et ajoute le
        sticker.src = sticker.querySelector('img').src;
        addSticker(sticker.src);
    });
});

// Fonction pour ajouter un sticker à l'éditeur quand on clique dessus
function addSticker(sticker) {
    const imgElement = document.createElement('img');
    imgElement.classList.add('sticker');
    imgElement.src = sticker;
    imgElement.style.position = 'absolute';
    imgElement.style.cursor = 'move';
    imgElement.style.zIndex = 1;
    imgElement.style.left = (editorInterface.clientWidth / 2) + 'px';;
    imgElement.style.top = (editorInterface.clientHeight / 2) + 'px';
    editorInterface.appendChild(imgElement);
    handleImageEvents(imgElement);
}

// Fonction pour gérer les événements liés à l'image (déplacement, suppression)
function handleImageEvents(imgElement) {
    let isHolding = false;
    let holdTimeout;

    // Ajout d'une bordure au survol
    imgElement.addEventListener('mouseover', function() {
        if (!isHolding) {
            imgElement.style.border = '2px dashed #333';
        }
    });

    // Suppression de la bordure
    imgElement.addEventListener('mouseout', function() {
        if (!isHolding) {
            imgElement.style.border = '';
        }
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
            imgElement.style.border = '2px dashed #333';    
        }
    });

    window.addEventListener('mousemove', function(e) {
        if (isDragging) {
            // const editorRect = editorInterface.getBoundingClientRect();
            let x = e.clientX - offsetX;
            let y = e.clientY - offsetY;
            imgElement.style.left = `${x}px`;
            imgElement.style.top = `${y}px`;
        }
    });
}