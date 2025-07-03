let isWebcamActive = false; // État de la webcam
let isStickerActive = false;
let isImageActive = false;


const takePictureButton = document.querySelector('#take-picture');
const resetButton = document.querySelector('#reset-editor');
const saveButton = document.querySelector('#save-image');
const downloadButton = document.querySelector('#download-image');

// Fonction de mise à jour de l'état du bouton
function updateButtonsState() {

  if (isWebcamActive && isStickerActive)
    takePictureButton.disabled = false;
  else
    takePictureButton.disabled = true;

  if (isStickerActive && isImageActive) {
    saveButton.disabled = false;
    downloadButton.disabled = false;
  } else {
    saveButton.disabled = true;
    downloadButton.disabled = true;
  }

  if (isStickerActive || isImageActive) {
    resetButton.disabled = false;
  } else {
    resetButton.disabled = true;
  }
}

// Observer les changements dans l'interface
const observer = new MutationObserver(() => {
  updateButtonsState();
});

observer.observe(targetContainer, {
  childList: true,
  subtree: true
});

// Vérification initiale
updateButtonState();