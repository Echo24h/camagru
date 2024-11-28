document.addEventListener('DOMContentLoaded', () => {

    const sendAjaxRequest = (url, data, checkbox) => {

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Sauvegarde l'état précédent du checkbox
        const previousState = !checkbox.checked;

        // Ajoute le token CSRF à la requête
        data.append('csrf_token', csrfToken);

        fetch(url, {
            method: 'POST',
            body: data,
        })
            .then(response => {
                if (!response.ok) {
                    // Si la réponse n'est pas OK (status différent de 200)
                    checkbox.checked = previousState; // Rétablit l'état précédent
                    throw new Error(`Erreur serveur: ${response.status}`);
                }
                return response.text(); // Traite la réponse
            })
            .then(result => {
                // Met a jour le token CSRF
                const newToken = JSON.parse(result).token_csrf;
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', newToken);
                // Met a jour toutes les input hidden
                const hiddenInputs = document.querySelectorAll('input[type="hidden"]');
                hiddenInputs.forEach(input => {
                    input.value = newToken;
                });
            })
            .catch(error => {
                alert('Une erreur est survenue, veuillez réessayer plus tard.');
                checkbox.checked = previousState; // Rétablit l'état précédent
            });
    };

    // Gestion des notifications
    const notificationsCheckbox = document.getElementById('notifications');
    notificationsCheckbox.addEventListener('change', () => {
        const formData = new FormData();
        formData.append('notifications', notificationsCheckbox.checked ? 1 : 0);
        sendAjaxRequest('/settings', formData, notificationsCheckbox);
    });

    // Gestion de la visibilité de l'email
    const emailVisibilityCheckbox = document.getElementById('email_visibility');
    emailVisibilityCheckbox.addEventListener('change', () => {
        const formData = new FormData();
        formData.append('email_visibility', emailVisibilityCheckbox.checked ? 1 : 0);
        sendAjaxRequest('/settings', formData, emailVisibilityCheckbox);
    });
});
