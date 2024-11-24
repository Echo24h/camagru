document.addEventListener('DOMContentLoaded', () => {
    const sendAjaxRequest = (url, data, checkbox) => {
        // Sauvegarde l'état précédent du checkbox
        const previousState = !checkbox.checked;

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
                //console.log('Résultat:', result);
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
