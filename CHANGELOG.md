# Journal des modifications

## [2.0.2] — 2026-10-31

### Corrigé

- Dépendance **astral-core 2.1.2** — restaure `bin/console`.

## [2.0.1] — 2026-10-31

### Modifié

- UI **nova-orange** (tokens + Tailwind `ember` ; plus le vert starter)

## [2.0.0] — 2026-10-31

### Breaking

- PHP minimum : **`^8.3`**
- `astral-php/astral-core` **^2.1**
- `Role::ADMIN` / `USER` / `GUEST` → enum `Role::Admin` / `User` / `Guest`

### Notes

- Feuille 2.1 **P4**. Guide core : [UPGRADE-2.1](https://github.com/astral-php/astral-core/blob/main/UPGRADE-2.1.md) · hub [UPGRADE-2.0](https://github.com/astral-php/astral/blob/main/UPGRADE-2.0.md) pour Role.

## [1.0.2] — 2026-10-08

### Ajouté

- Suite PHPUnit : smokes HTTP (`tests/Http`) — home, `/blog`, auth, admin (migrations auto en test).
- Dépendance `astral-php/astral-core:^1.2.4`.

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
