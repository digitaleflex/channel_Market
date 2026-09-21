# All_Books — Librairie en Ligne & Marketplace d'E-books

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

Application e-commerce et marketplace pour la vente de **livres numériques et produits digitaux (PDF, EPUB, livres audio)** avec paiement sécurisé multicanal via **Chariow** (XOF / FCFA, Mobile Money & Cartes) et téléchargement sécurisé par token temporaire.

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

### Côté Lecteur / Client

- **Catalogue de livres enrichi** — Affichage vertical avec couverture réaliste, filtres par catégorie/thématique et recherche instantanée par titre ou auteur
- **Extrait gratuit (« Feuilleter le livre »)** — Lecture ou téléchargement des premiers chapitres en PDF avant achat
- **Checkout & Paiement instantané** — Formulaire sans friction (email, prénom, nom, téléphone) puis redirection vers Chariow
- **Ma Bibliothèque Numérique** — Dashboard client avec liste des livres acquis et accès permanent aux fichiers
- **Téléchargement sécurisé** — Lien temporaire sécurisé via token d'accès
- **Authentification complète** — Inscription, connexion, gestion de profil lecteur (Laravel Breeze)

### Côté Administrateur (Gestionnaire Librairie)

- **CRUD Livres & E-books** — Ajout et modification avec métadonnées complètes : Titre, Auteur, Catégorie, Format (PDF, EPUB, Audio), Nb de pages, Langue, Année, ISBN, Extrait gratuit, Fichier complet ou lien Cloud, Couverture verticale
- **Gestion des commandes** — Visualisation de toutes les ventes et clients
- **Configuration & Pixels** — Gestion dynamique du Meta Pixel (Facebook) et Google Analytics
- **Middleware `admin`** — Accès restreint aux administrateurs (`is_admin = true`)

### SEO & Technique

- **Données structurées Schema.org `Book`** — Indexation optimale sur Google Books et moteurs de recherche
- **Sitemap XML** — Généré dynamiquement à `/sitemap.xml`
- **Webhook Chariow** — Vérification HMAC sécurisée des paiements
- **Files d'attente & Sessions en BDD**

---

## Architecture

### Modèle `Product` (Livre Numérique)

| Champ | Type | Description |
|---|---|---|
| `title` | string | Titre de l'ouvrage |
| `author` | string | Auteur de l'ouvrage |
| `category` | string | Genre / Thématique (Business, Dév. Personnel, Finance...) |
| `format` | string | Format de lecture (PDF, EPUB, Audio...) |
| `pages_count` | integer | Nombre de pages de l'ouvrage |
| `language` | string | Langue de publication (Français, etc.) |
| `publication_year` | integer | Année d'édition |
| `isbn` | string | Référence ou numéro ISBN |
| `description` | text | Résumé et table des matières |
| `price` | decimal | Prix de vente en FCFA (XOF) |
| `file_path` | string | Fichier complet ou lien Cloud sécurisé |
| `sample_file` | string | Fichier d'extrait gratuit (PDF) ou lien de prévisualisation |
| `image` | string | Couverture verticale du livre |
| `chariow_product_id` | string | ID du produit dans le dashboard Chariow |

---

## Installation Rapide

```bash
# 1. Cloner ou ouvrir le projet
cd allbooks

# 2. Installer les dépendances
composer install
npm install

# 3. Configuration de l'environnement
cp .env.example .env
php artisan key:generate

# 4. Lancer les migrations et charger les livres de démonstration
php artisan migrate --seed

# 5. Compiler les assets
npm run build

# 6. Lancer le serveur local
php artisan serve
```

Accédez ensuite à la boutique sur `http://127.0.0.1:8000`.

### Compte Administrateur par défaut (Seeder) :
- **Email** : `admin@allbooks.store`
- **Mot de passe** : `AllBooks_Admin2026!`

---

## Licence

Ce projet est sous licence [MIT](https://opensource.org/licenses/MIT).
