# Journal des modifications

## [1.0.1] — 2026-10-04

### Ajouté

- `.htaccess` racine : redirection Apache vers `public/` (déploiement mutualisé / OVH, comme Astral MVC).
- `public/.htaccess` : `Options -Indexes` + flag `QSA`.

## [1.0.0] — 2026-09-29

### Ajouté

- Application **astral-blog 1.0** (`type: project`) basée sur **astral-starter**.
- Front : `/blog`, recherche, catégories, tags, article, commentaires publics.
- Admin : CRUD articles / catégories / tags + modération commentaires.
- Auth / admin utilisateurs hérités du starter.
- Migrations blog + users ; UI Fraunces / DM Sans (accent vert).

### Note

- L’ancien package bibliothèque MVP 0.3 est archivé en `astral-blog-legacy-0.3/`.
- Publication GitHub / Packagist : à venir.
