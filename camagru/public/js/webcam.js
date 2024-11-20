async function startWebcam() {
    try {
        // Récupération de la balise vidéo
        const video = document.querySelector('#webcam');
        if (!video) {
            console.error("La balise vidéo n'existe pas.");
            return;
        }

        // Accès à la webcam
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });

        // Affectation du flux à la balise vidéo
        video.srcObject = stream;

        // Lecture explicite au cas où autoplay ne fonctionnerait pas
        await video.play();
    } catch (error) {
        // Gestion des erreurs
        if (error.name === "NotAllowedError") {
            alert("Vous avez refusé l'accès à la webcam.");
        } else if (error.name === "NotFoundError") {
            alert("Aucune webcam détectée sur cet appareil.");
        } else {
            console.error("Erreur avec la webcam :", error);
            alert("Impossible d'accéder à la webcam.");
        }
    }
}

// Ajout d'un écouteur pour le bouton
document.querySelector('#start-webcam').addEventListener('click', () => {
    startWebcam();
});