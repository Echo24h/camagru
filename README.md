# camagru

Le projet consiste en une application web permettant de faire un petit montage photo avec ou sans webcam.

L'utilisateur peut prendre une photo à partir de sa webcam ou importer une image, y ajouter des stickers issus d'une liste et publier le rendu. Toutes les images finales sont publiques, peuvent être aimées et commentées.

Sujet : [EN](https://github.com/Echo24h/camagru/blob/main/en.subject.pdf)

## Déploiement

Utilisez `make`, tout simplement

_PS: assurez-vous d'avoir docker-compose installé sur votre machine_

## Pages principales

| **Connexion / Inscription** | **Editeur** |
|-----------------------------|-------------|
| ![Connexion / Inscription](https://i.ibb.co/rtYcfgb/Capture-d-cran-du-2024-12-19-20-43-14.png) | ![Editeur](https://i.ibb.co/jWmzVXZ/Capture-d-cran-du-2024-12-19-20-47-52.png) |
| **Galerie** | **Paramètres** |
| ![Galerie](https://i.ibb.co/6NF03NJ/GALERIE.png) | ![Paramètres](https://i.ibb.co/RvWcr6h/Capture-d-cran-du-2024-12-19-20-50-17.png) |

## Reste à faire

- Responsive
- Nettoyer le code :
  - Uniformiser les requêtes AJAX en JSON.
  - Réorganiser la structure du JavaScript.
  - Effectuer un nettoyage général du PHP.

## BONUS (fait)

- Scroll infini dans les galeries
- Requetes AJAX
- Page profil
- Protection CSRF
- Controle de SESSION avancé
- Caméra en direct dans l'éditeur
- Déplacement des Stickers

### Points à améliorer:

- Fix le bug qui transforme le background noir en transparent dans EditorController

## Ressources

### Documentations

- [MailJet](https://dev.mailjet.com/email/guides/) - Plateforme de livraison de courrier électronique avec API.

- [Docker](https://docs.docker.com/)

- [NGINX](https://nginx.org/)

- [PHP](https://www.php.net/)

- [JavaScript](https://developer.mozilla.org/fr/docs/Web/JavaScript)

### Sécurité

- [Sécurité web : SessionID, Cookies et Authentification !](https://www.youtube.com/watch?v=J-1s-ONitRc) - **Hafnium - Sécurité informatique** (YouTube)
