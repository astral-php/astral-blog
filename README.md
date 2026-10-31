# astral-blog

Application **blog complet** pour [Astral](https://github.com/astral-php) — basée sur le starter + métier blog.

Articles, catégories, tags, commentaires modérés, auth, admin utilisateurs.

[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Version](https://img.shields.io/badge/version-2.0.2-blue)](./CHANGELOG.md)

> Écosystème : [Astral MVC](https://github.com/astral-php/astral) · [Packagist astral-php](https://packagist.org/packages/astral-php/).
> App `create-project` — pas un package `require`.

## Installation

```bash
composer create-project astral-php/astral-blog:^2.0 mon-blog
cd mon-blog
cp .env.example .env
php bin/console migrate
php -S 127.0.0.1:8083 -t public public/router.php
```

Ouvrir http://127.0.0.1:8083 → **Créer le compte admin** (`/register`) → publier via `/admin/blog/posts`.

## En local (workspace)

```bash
cd components-astral/astral-blog
composer install
cp .env.example .env
php bin/console migrate
composer run serve
```

## Contenu

| Élément | Statut |
|---------|--------|
| Base starter (auth, admin users, layout) | ✅ |
| Front `/blog` (liste, recherche, catégories, tags) | ✅ |
| Article + commentaires publics (modération) | ✅ |
| Admin articles / catégories / tags / commentaires | ✅ |
| Migrations SQLite / MySQL | ✅ |
| `astral-php/astral-core` ^2.1 | ✅ |

## Routes principales

| Route | Description |
|-------|-------------|
| `/` | Accueil + derniers articles |
| `/blog` | Liste + recherche `?q=` |
| `/blog/:slug` | Article + commentaires |
| `/blog/category/:slug` | Filtre catégorie |
| `/blog/tag/:slug` | Filtre tag |
| `/admin/blog/posts` | CRUD articles (admin) |
| `/admin/blog/categories` | CRUD catégories |
| `/admin/blog/tags` | CRUD tags |
| `/admin/blog/comments` | Modération commentaires |

## Configuration

```env
APP_NAME="Astral Blog"
AUTH_REGISTRATION=direct   # ou confirm
DB_DRIVER=sqlite
DB_DATABASE=database/blog.sqlite
```

## Licence

MIT
