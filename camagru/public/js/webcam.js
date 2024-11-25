let isWebcamActive = false; // État de la webcam
let webcamStream = null; // Flux de la webcam

async function startWebcam() {
    try {
        const video = document.querySelector('#webcam');
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
        webcamStream = stream;
        isWebcamActive = true;
        const takePictureButton = document.querySelector('#take-picture');
        takePictureButton.disabled = false;
        // recupere la taille de la video
        const { width, height } = stream.getTracks()[0].getSettings();
        editorInterface.style.width = `${width}px`;
        editorInterface.style.height = `${height}px`;
        const toggleIcon = document.querySelector('#toggle-icon');
        toggleIcon.src = "/img/camera-off.svg"; // Change l'image pour l'état "on"
    } catch (error) {
        console.error("Erreur avec la webcam :", error);
        alert("Impossible d'accéder à la webcam.");
    }
}

function stopWebcam() {
    if (webcamStream) {
        const tracks = webcamStream.getTracks();
        tracks.forEach(track => track.stop()); // Arrête chaque piste
        webcamStream = null;
    }
    const takePictureButton = document.querySelector('#take-picture');
    takePictureButton.disabled = true;

    const video = document.querySelector('#webcam');
    video.srcObject = null; // Supprime le flux de la balise vidéo
    isWebcamActive = false;
    const toggleIcon = document.querySelector('#toggle-icon');
    toggleIcon.src = "/img/camera-on.svg"; // Change l'image pour l'état "off"
}

document.querySelector('#toggle-webcam').addEventListener('click', async () => {
    const toggleIcon = document.querySelector('#toggle-icon');

    if (isWebcamActive) {
        stopWebcam();
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

    // Création de l'image
    const image = new Image();
    image.src = canvas.toDataURL('image/png');
    image.id = 'main-image';

    // Suppression de l'ancienne image
    const oldImage = document.querySelector('#main-image');
    if (oldImage) {
        oldImage.remove();
    }

    // Affichage de l'image
    document.querySelector('.editor-interface').appendChild(image);
    stopWebcam();
}

// Ajout d'un écouteur pour le bouton
document.querySelector('#take-picture').addEventListener('click', () => {
    takePicture();
});