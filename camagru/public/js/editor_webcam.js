let webcamStream = null; // Flux de la webcam

async function startWebcam() {
    try {
        const video = document.querySelector('#webcam');
        video.style.display = 'block';
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
        webcamStream = stream;
        const takePictureButton = document.querySelector('#take-picture');
        // recupere la taille de la video
        resetEditor();
        isWebcamActive = true;
        updateButtonsState();
        const toggleIcon = document.querySelector('#toggle-icon');
        toggleIcon.src = "/img/camera-off.svg"; // Change l'image pour l'état "on"
    } catch (error) {
        //console.error("Erreur avec la webcam :", error);
        alert("Impossible d'accéder à la webcam.");
    }
}

function resetEditor() {
    // Suppression de l'ancienne image et des stickers
    const oldImage = document.querySelector('#main-image');
    video.style.display = 'block';
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

function stopWebcam(reset) {
    if (webcamStream) {
        const tracks = webcamStream.getTracks();
        tracks.forEach(track => track.stop()); // Arrête chaque piste
        webcamStream = null;
    }
    isWebcamActive = false;
    updateButtonsState();
    const video = document.querySelector('#webcam');
    video.srcObject = null; // Supprime le flux de la balise vidéo
    const toggleIcon = document.querySelector('#toggle-icon');
    toggleIcon.src = "/img/camera-on.svg"; // Change l'image pour l'état "off"
    if (reset) {
        resetEditor();
    }
}

document.querySelector('#toggle-webcam').addEventListener('click', async () => {
    if (isWebcamActive) {
        stopWebcam(true);
    } else {
        await startWebcam();
    }
});

async function takePicture() {
    // Récupération de la balise vidéo
    const video = document.querySelector('#webcam');
    if (!video) {
        console.error("La balise vidéo n'existe pas.");
        return;
    }

    // Création d'un canvas pour la capture
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    // Capture de l'image
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    video.style.display = 'none';

    // Création de l'image
    const image = new Image();
    image.src = canvas.toDataURL('image/png');
    image.id = 'main-image';
    image.style.overflow = 'hidden';
    image.style.width = '100%';
    image.style.height = '100%';

    // Affichage de l'image
    document.querySelector('.editor-interface').appendChild(image);
    stopWebcam(false);
    isImageActive = true;
    updateButtonsState()
}

// Ajout d'un écouteur pour le bouton
document.querySelector('#take-picture').addEventListener('click', () => {
    takePicture();
});