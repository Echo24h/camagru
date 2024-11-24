# camagru (EN CONSTRUCTION)

## A faire

- Refonte Design HTML CSS
- Protection token CSRF
- Finir éditeur + faire coté serveur PHP et non client Javascript
- Notification commentaire

## Structure de base
```arduino
camagru/
├── app/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   └── UserController.php
│   ├── Models/
│   │   ├── User.php
│   │   └── Post.php
│   ├── Views/
│   │   ├── layouts/
│   │   │   └── main.php
│   │   ├── home/
│   │   │   └── index.php
│   │   └── user/
│   │       └── profile.php
├── public/
│   ├── index.php
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── app.js
├── config/
│   ├── config.php
│   └── database.php
├── core/
│   ├── Router.php
│   ├── Controller.php
│   ├── Model.php
│   ├── View.php
│   └── Database.php
├── vendor/
├── .env
├── composer.json
└── README.md
```

Dans `camagru/` :
```shell
composer install --no-dev --optimize-autoloader
```

## Ressources

### Documentations

- [Boostrap 4.0](https://getbootstrap.com/docs/4.0/getting-started/introduction/)

- [MailJet](https://dev.mailjet.com/email/guides/) - Plateforme de livraison de courrier électronique avec API.
### Sécurité

- [Sécurité web : SessionID, Cookies et Authentification !](https://www.youtube.com/watch?v=J-1s-ONitRc) - **Hafnium - Sécurité informatique** (YouTube)