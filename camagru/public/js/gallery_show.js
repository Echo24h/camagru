// Fonction avec FormData pour envoyer les requêtes AJAX de like
document.addEventListener('DOMContentLoaded', function() {

    const likeButtons = document.querySelectorAll('.likes');
    likeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const imageId = button.getAttribute('data-id');
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
});