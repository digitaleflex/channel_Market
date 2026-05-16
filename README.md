# Channel Market — Marketplace de Produits Numériques

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

Application de marketplace pour la vente de produits numériques (PDF, vidéos, musiques, etc.) avec paiement intégré via **Charriow** (XOF / FCFA) et téléchargement sécurisé par token.

---

## Stack Technique

| Technologie    | Version                 |
| -------------- | ----------------------- |
| PHP            | ^8.2                    |
| Laravel        | ^12.0                   |
| Laravel Breeze | ^2.4 (authentification) |
| Tailwind CSS   | ^3.1 + Vite             |
| AlpineJS       | ^3.4                    |
| Charriow       | Paiement API            |
| MySQL          | 5.7+ / 8.0              |

---

## Fonctionnalités

### Côté Client

- **Catalogue de produits** — Liste des produits numériques avec page détail
- **Checkout & Paiement** — Formulaire (email, prénom, nom, téléphone) puis redirection vers Charriow
- **Téléchargement sécurisé** — Lien unique à usage unique via token UUID par commande
- **Historique d'achats** — Dashboard client avec liste des commandes payées
- **Authentification** — Inscription, connexion, gestion de profil (Laravel Breeze)

### Côté Admin

- **CRUD Produits** — Création, modification, suppression des produits (titre, description, prix, fichier, image)
- **Gestion des commandes** — Visualisation de toutes les commandes avec statut
- **Paramètres** — Configuration dynamique via clé/valeur
- **Middleware `admin`** — Basé sur le champ `is_admin` du modèle User

### SEO & Technique

- **Sitemap XML** — Généré dynamiquement à `/sitemap.xml`
- **Webhook Charriow** — Vérification HMAC sécurisée des paiements
- **File d'attente** — Traitement des jobs en base de données
- **Sessions** — Gérées en base de données

---

## Architecture

### Modèles

| Modèle    | Champs principaux                                                                               |
| --------- | ----------------------------------------------------------------------------------------------- |
| `Product` | `title`, `description`, `price`, `file_path`, `image`                                           |
| `Order`   | `user_id`, `client_email`, `product_id`, `amount`, `status`, `transaction_id`, `download_token` |
| `User`    | `name`, `email`, `password`, `is_admin`                                                         |
| `Setting` | `key`, `value`                                                                                  |

### Routes Principales

| Méthode    | Route                            | Description                   |
| ---------- | -------------------------------- | ----------------------------- |
| `GET`      | `/`                              | Catalogue des produits        |
| `GET`      | `/product/{product}`             | Détail d'un produit           |
| `GET/POST` | `/checkout/{product}`            | Page de paiement              |
| `GET`      | `/dashboard`                     | Espace client (authentifié)   |
| `GET`      | `/download/{token}`              | Téléchargement sécurisé       |
| `GET`      | `/payment/success/{order}`       | Page de succès après paiement |
| `GET`      | `/sitemap.xml`                   | Sitemap SEO                   |
| `GET`      | `/admin/products`                | Liste des produits (admin)    |
| `GET/POST` | `/admin/products/create`         | Créer un produit (admin)      |
| `GET/PUT`  | `/admin/products/{product}/edit` | Modifier un produit (admin)   |
| `GET`      | `/admin/orders`                  | Liste des commandes (admin)   |
| `GET/POST` | `/admin/settings`                | Paramètres (admin)            |

---

## Prérequis

- PHP >= 8.2 avec les extensions : `pdo_mysql`, `mbstring`, `openssl`, `json`, `fileinfo`
- Composer
- Node.js >= 18 + npm
- MySQL 5.7+ ou 8.0
- Un compte [Charriow](https://charriow.com) avec clé API

---

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/digitaleflex/channel_Market.git
cd channel_Market
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configuration de l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Modifiez le fichier `.env` avec vos paramètres :

```env
APP_NAME="Channel Market"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=produit_digitaux
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

# Charriow
CHARRIOW_API_KEY=votre_cle_secrete_charriow
CHARRIOW_API_URL=https://api.charriow.com
CHARRIOW_WEBHOOK_SECRET=votre_cle_webhook_charriow
```

### 4. Créer la base de données

```bash
mysql -u root -p -e "CREATE DATABASE produit_digitaux CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 5. Exécuter les migrations

```bash
php artisan migrate
```

### 6. Créer un administrateur

```bash
php artisan tinker
```

Puis dans Tinker :

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'is_admin' => true,
]);
exit
```

### 7. Compiler les assets

```bash
npm run build
```

### 8. Lancer l'application

```bash
composer run dev
```

Ou manuellement :

```bash
php artisan serve
npm run dev   # dans un autre terminal
```

Accédez à l'application sur [http://localhost:8000](http://localhost:8000)

---

## Commandes Utiles

| Commande                   | Description                                                       |
| -------------------------- | ----------------------------------------------------------------- |
| `composer run setup`       | Installation complète (dépendances, .env, clé, migrations, build) |
| `composer run dev`         | Lancer le serveur + queue + logs + Vite en parallèle              |
| `composer run test`        | Exécuter les tests PHPUnit                                        |
| `php artisan serve`        | Lancer le serveur de développement                                |
| `npm run dev`              | Compiler les assets en mode watch                                 |
| `npm run build`            | Compiler les assets pour la production                            |
| `php artisan queue:listen` | Traiter les jobs en file d'attente                                |

---

## Configuration Charriow

1. Créez un compte sur [charriow.com](https://charriow.com)
2. Récupérez votre **API Key** dans le tableau de bord
3. Configurez un **Webhook** pointant vers `https://votredomaine.com/payment/charriow/webhook`
4. Copiez la **Webhook Secret** dans votre `.env`

> Le webhook est recommandé : il garantit la mise à jour fiable du statut des commandes même si le client ferme la page de retour Charriow.

---

## Structure des Répertoires Clés

```
channel_Market/
├── app/
│   ├── Http/Controllers/
│   │   ├── ProductController.php      # Catalogue + CRUD admin
│   │   ├── PaymentController.php      # Checkout, Charriow, webhook
│   │   ├── OrderController.php        # Gestion commandes admin
│   │   ├── DownloadController.php     # Téléchargement sécurisé
│   │   ├── SettingController.php      # Paramètres admin
│   │   └── Auth/                      # Authentification Breeze
│   └── Models/
│       ├── Product.php
│       ├── Order.php
│       ├── User.php
│       └── Setting.php
├── database/migrations/               # Tables users, products, orders, settings
├── resources/views/                   # Blade templates
├── routes/
│   └── web.php                        # Toutes les routes web
└── config/services.php               # Configuration des services externes
```

---

## Sécurité

- **CSRF** activé sur toutes les routes (sauf webhook Charriow)
- **HMAC SHA-256** pour la vérification des webhooks Charriow
- **Tokens UUID** uniques pour chaque téléchargement
- **Middleware `admin`** pour protéger le panel d'administration
- **Hashage des mots de passe** via Bcrypt

---

## Licence

Ce projet est sous licence [MIT](https://opensource.org/licenses/MIT).
