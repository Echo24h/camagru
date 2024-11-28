// Attendre que le document soit prêt
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter un écouteur au conteneur parent
    const galleryContainer = document.querySelector('.gallery-container');

    galleryContainer.addEventListener('click', function(event) {
        const button = event.target.closest('.likes');
        if (!button) return; // Ignore si ce n'est pas un bouton avec la classe "likes"

        const imageId = button.getAttribute('data-id');
        if (!imageId) return; // Ignore si aucun ID d'image n'est présent

        // Création de la requête AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData();
        formData.append('id', imageId);
        formData.append('csrf_token', csrfToken);
        fetch('/image/like', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const newToken = data.token_csrf;
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', newToken);
                document.querySelectorAll('input[type="hidden"]').forEach(input => {
                    if (input.name === 'csrf_token') {
                        input.value = newToken;
                    }
                });
                const count = data.count.count;
                if (count > 0) {
                    button.innerHTML = '<img class="icon" src="/img/like.svg" alt="J\'aime">';
                } else {
                    button.innerHTML = '<img class="icon" src="/img/like_empty.svg" alt="Je n\'aime pas">';
                }
                button.innerHTML += '<p>' + count + '</p>';
            } else {
                alert("Erreur lors de la mise à jour des likes.");
            }
        });
    });
});



let page = 1;
let isLoading = false;

window.addEventListener('scroll', () => {
    // Vérifie si l'utilisateur est proche du bas de la page
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100 && !isLoading) {
        isLoading = true;
        page++;
        loadImages(page);
    }
});

function loadImages(page) {
    fetch(`/gallery?page=${page}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(image => {
                    const itemElement = document.createElement('div');
                    itemElement.classList.add('gallery-item');
                    itemElement.setAttribute('data-id', image.id);
                    itemElement.innerHTML = `
                        <a href="/gallery/show?id=${image.id}">
                            <img class="picture" src="/image?id=${image.id}" alt="Image" class="picture">
                        </a>
                        <div class="item-info">
                            <div class="creator">
                                <a href="/profile?id=${image.user_id}">${image.username}</a>
                            </div>
                            <div class="comments">
                                <a href="/gallery/show?id=${image.id}">
                                <img class="icon" src="img/comment.svg" alt="Commentaire">${image.total_comments}
                                </a>
                            </div>
                            <div class="likes" data-id="${image.id}">
                                ${image.total_likes > 0 ? '<img class="icon" src="/img/like.svg" alt="J\'aime">' : '<img class="icon" src="/img/like_empty.svg" alt="Je n\'aime pas">'}
                                <p>${image.total_likes}</p>
                            </div>
                        </div>
                    `;
                    document.querySelector('.gallery-container').appendChild(itemElement);
                });
                isLoading = false;
            } else {
                isLoading = true;
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des images :', error);
            isLoading = false;
        });
}