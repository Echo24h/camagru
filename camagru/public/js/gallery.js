// Attendre que le document soit prêt
document.addEventListener('DOMContentLoaded', function() {
    // Écouteurs d'événements pour la gestion des likes avec AJAX
    const likeButtons = document.querySelectorAll('.likes');
    likeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const imageId = button.getAttribute('data-id');
            
            // Création de la requête AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/image/like', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Mise à jour du nombre de likes
                    const count = JSON.parse(xhr.responseText).count.count;
                    if (count > 0) {
                        button.innerHTML = '<p>' + count + '</p>';
                        button.innerHTML += '<img class="icon" src="/img/like.svg" alt="J\'aime">';
                    } else {
                        button.innerHTML = '<img class="icon" src="/img/like_empty.svg" alt="Je n\'aime pas">';
                    }
                    
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
});