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
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/image/like', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Mise à jour du nombre de likes
                const count = JSON.parse(xhr.responseText).count.count;
                if (count > 0) {
                    button.innerHTML = '<img class="icon" src="/img/like.svg" alt="J\'aime">';
                } else {
                    button.innerHTML = '<img class="icon" src="/img/like_empty.svg" alt="Je n\'aime pas">';
                }
                button.innerHTML += '<p>' + count + '</p>';
            } else if (xhr.status === 401) {
                // Redirection vers la page de connexion
                window.location.href = '/login';
            } else {
                alert("Erreur lors de la mise à jour des likes.");
            }
        };
        // Envoi de la requête
        xhr.send('id=' + encodeURIComponent(imageId));
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