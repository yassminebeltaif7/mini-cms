# Mini-CMS

## Description

Mini-CMS est le projet fil rouge de l'Atelier Framework Côté Serveur (Laravel 13, PHP 8.3+, ISET Sidi Bouzid, 3e année MDW). Il comprend un site public de publication, un back-office authentifié, une API JSON protégée par jetons Sanctum et une suite de tests Pest. Livrable final : tag `lab-08b`, dépôt `mini-cms`.

Auteur : Yasmine Beltaif, groupe MDW32.

## Fonctionnalités

- Pages publiques : accueil, à propos, heure, liste des articles, archives par année, filtres par catégorie et par tag.
- Back-office : création, modification, suppression et publication des articles ; gestion des catégories et des tags ; gestion des rôles des utilisateurs.
- Authentification manuelle : inscription, connexion, déconnexion, limitation des droits par rôle et `PostPolicy`.
- API JSON : lecture paginée des articles, des catégories et des tags ; filtres `?category=` et `?tag=` ; connexion par jeton, déconnexion et écritures protégées.
- Conventions : Form Requests, Policies, API Resources, chargement anticipé (`with`, `withCount`, `whenLoaded`, `whenCounted`) et contrôleurs légers.
- Tests Pest : `php artisan test` entièrement vert, y compris `ApiReadTest` et `ApiWriteTest`.

Comparaison avec le starter kit Livewire :

| Critère | Mini-CMS (authentification manuelle) | Starter kit Livewire |
|---|---|---|
| Routes d'authentification | `routes/web.php` : `/inscription`, `/connexion` et `/deconnexion` déclarées à la main | Routes enregistrées par Fortify depuis son paquet ; absentes de `routes/web.php` |
| Inscription | `app/Http/Controllers/Auth/RegisterController.php` | `app/Actions/Fortify/CreateNewUser.php` et `resources/views/pages/auth/register.blade.php` |
| Connexion | `app/Http/Controllers/Auth/LoginController.php` (`Auth::attempt`, `regenerate`) | Contrôleur de Fortify et `resources/views/pages/auth/login.blade.php` |
| Déconnexion | `app/Http/Controllers/Auth/LoginController.php` (`logout`, `invalidate`, `regenerateToken`) | Route `logout` de Fortify ; les mêmes trois opérations dans `app/Livewire/Actions/Logout.php` |
| Limitation des tentatives | `routes/web.php` : aucune | 5 tentatives par minute par e-mail et adresse IP (`app/Providers/FortifyServiceProvider.php`) |
| Mot de passe oublié | `routes/web.php` : aucune route | `Features::resetPasswords()` dans `config/fortify.php` |
| Vérification de l'e-mail | `app/Models/User.php` : absente, `MustVerifyEmail` n'est pas utilisé | `Features::emailVerification()`, `MustVerifyEmail` sur `User`, middleware `verified` sur `/dashboard` |
| Double authentification | `app/Models/User.php` : absente, comme les passkeys | `Features::twoFactorAuthentication()` et `Features::passkeys()` |
| Rôles et autorisations | `app/Models/User.php` (colonne `role`), `app/Providers/AppServiceProvider.php` (Gates), `app/Policies/PostPolicy.php` et `app/Http/Middleware/EnsureUserIsAdmin.php` | Aucun : à écrire soi-même |
| Tests | `tests/Feature/AuthTest.php`, `tests/Feature/AuthorizationTest.php`, `tests/Feature/AdminAccessTest.php`, `tests/Feature/PostVisibilityTest.php`, `tests/Feature/ApiReadTest.php` et `tests/Feature/ApiWriteTest.php` | 10 fichiers dans `tests/Feature` (Auth, Settings, Dashboard), écrits avec PHPUnit dans le dépôt |

Ce que je garderais du kit dans un vrai projet : ses fonctionnalités d'authentification (limitation des tentatives, mot de passe oublié, vérification de l'e-mail, double authentification et passkeys) et ses écrans Livewire/Flux, qui évitent de réécrire beaucoup de code. Je continuerais à écrire moi-même les rôles, les Gates et les Policies, tout en gardant visible la logique de `app/Http/Controllers/Auth/LoginController.php` (`regenerate`, `invalidate`, `regenerateToken`) pour comprendre la sécurité.

## Installation

Prérequis : PHP 8.3 ou plus avec les extensions curl, fileinfo, mbstring, openssl, pdo_sqlite, sqlite3 et zip ; Composer 2 ; Node.js LTS et npm ; Git. La base utilise SQLite.

### Bash

```bash
git clone https://github.com/yassminebeltaif7/mini-cms.git
cd mini-cms
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan test
composer run dev
```

### PowerShell

```powershell
git clone https://github.com/yassminebeltaif7/mini-cms.git
cd mini-cms
composer install
npm install
npm run build
copy .env.example .env
php artisan key:generate
New-Item database\database.sqlite -ItemType File
php artisan migrate:fresh --seed
php artisan test
composer run dev
```

`php artisan migrate:fresh --seed` vide la base avant de la remplir. Ouvrir ensuite http://localhost:8000 (adresse de l'application, différente de l'adresse de Vite sur le port 5173). `http://localhost:8000/api/posts` renvoie la liste des articles en JSON.

## Comptes de test

Le seeder crée un administrateur et deux auteurs. Le mot de passe est `password` pour les trois comptes.

- Administrateur : `test@example.com` / `password`.
- Auteurs : deux comptes créés par `User::factory(2)` ; leurs adresses sont générées par Faker. Après `migrate:fresh --seed`, les afficher avec :

```powershell
php artisan tinker --execute="dump(App\Models\User::where('role','author')->pluck('email'));"
```

Un compte auteur permet de vérifier le 403 sur l'article d'un autre auteur.

## API

### Endpoints

| Méthode | URI | Accès | Description |
|---|---|---|---|
| GET | `/api/posts` | public | Articles publiés, paginés ; filtres `?per_page=`, `?category=<slug>` et `?tag=<slug>` |
| GET | `/api/posts/{post}` | public | Un article publié ; 403 pour un brouillon, 404 si inexistant |
| GET | `/api/categories` | public | Catégories avec leur nombre d'articles publiés |
| GET | `/api/categories/{category}` | public | Une catégorie avec son nombre d'articles publiés |
| GET | `/api/tags` | public | Tags avec leur nombre d'articles publiés |
| GET | `/api/tags/{tag}` | public | Un tag avec son nombre d'articles publiés |
| POST | `/api/login` | public | Connexion ; renvoie un jeton Sanctum |
| POST | `/api/logout` | jeton | Révoque le jeton courant ; 204 |
| GET | `/api/user` | jeton | Utilisateur authentifié |
| POST | `/api/posts` | jeton | Créer un article ; le propriétaire est l'utilisateur connecté ; 201 |
| PUT/PATCH | `/api/posts/{post}` | jeton + Policy | Modifier son article ; 200, 403 pour celui d'un autre auteur |
| DELETE | `/api/posts/{post}` | jeton + Policy | Supprimer son article ; 204 |

Les réponses passent par `PostResource`, `CategoryResource` et `TagResource` : une clé `data`, des dates ISO 8601, ni e-mail ni rôle des auteurs. Les listes paginées ajoutent `links` et `meta`.

### Authentification par jeton

`POST /api/login` avec `{"email":"...","password":"..."}` renvoie `{"token":"..."}` avec le statut 201. Le jeton s'envoie ensuite dans chaque requête protégée :

```text
Authorization: Bearer VOTRE_JETON
Accept: application/json
```

`POST /api/logout` révoque uniquement le jeton utilisé pour la requête. Après révocation, une requête protégée avec le même jeton renvoie 401. La collection Postman du défi de la session 14 est dans `docs/postman/mini-cms.postman_collection.json` : sa requête « Connexion » enregistre le jeton dans la variable `token`, qui reste vide dans le fichier exporté.

### Codes d'erreur

| Code | Corps | Situation |
|---|---|---|
| 401 | `{"message":"Unauthenticated."}` | Jeton absent, révoqué ou invalide sur une route protégée |
| 403 | `{"message":"This action is unauthorized."}` | Modification ou suppression de l'article d'un autre auteur ; brouillon demandé par un visiteur |
| 404 | `{"message":"No query results for model [...] "}` | Identifiant d'article, de catégorie ou de tag inconnu |
| 422 | `{"message":"...","errors":{...}}` | Corps invalide ou filtre inconnu ; les messages de validation sont en français |

L'ordre des contrôles est 401 (jeton), puis 403 (autorisation dans la Form Request ou la Policy), puis 422 (validation). Les erreurs sont toujours JSON grâce à l'en-tête `Accept: application/json`.

### Exemple curl.exe

```powershell
curl.exe -X POST -H "Accept: application/json" -H "Authorization: Bearer VOTRE_JETON" http://localhost:8000/api/logout
```

La réponse 204 n'a pas de corps : la commande n'affiche donc rien. Remplacer `VOTRE_JETON` par le jeton obtenu avec la requête de connexion dans Postman ; ne jamais committer un vrai jeton.

## Captures d'écran

### Page d'accueil

![Page d'accueil de Mini-CMS](screenshots/s01-accueil.png)

### Liste des routes

![Sortie de php artisan route:list](screenshots/s01-route-list.png)

### Page À propos

![Page A propos](screenshots/s02-a-propos.png)

### Accueil avec le layout

![Page d'accueil avec le layout](screenshots/s03-accueil.png)

### Liste des articles

![Liste des articles](screenshots/s03-articles.png)

### Détail d'un article

![Détail d'un article](screenshots/s03-article.png)

### Session 04

![Liste des articles](screenshots/s04-articles.png)

### Session 05

![Liste paginée des articles](screenshots/s05-articles.png)

![Formulaire avec erreurs de validation](screenshots/s05-formulaire.png)

### Session 06

![Formulaire de modification prérempli](screenshots/s06-modification.png)

![Confirmation de suppression](screenshots/s06-suppression.png)

### Session 07

![Liste des articles avec leur catégorie, leur auteur et leurs tags](screenshots/s07-liste-relations.png)

![Page d'un article avec ses tags et les articles de la même catégorie](screenshots/s07-detail-tags.png)

### Session 08

![Formulaire de modification avec la catégorie et les tags](screenshots/s08-formulaire-relations.png)

![Page des articles d'une catégorie](screenshots/s08-page-categorie.png)

### Session 09

![Page d'un article avec le message flash « Article créé. »](screenshots/s09-message-flash.png)

![Page Article introuvable pour un identifiant inexistant](screenshots/s09-article-introuvable.png)

### Session 10

![Formulaire de catégorie avec les messages de validation en français](screenshots/s10-validation.png)

![Page 404 personnalisée dans le layout du site](screenshots/s10-404.png)

![Page 503 en lecture seule](screenshots/s10-503.png)

![Liste des catégories du back-office](screenshots/s10-categories-liste.png)

![Création d'une catégorie](screenshots/s10-categories-creation.png)

![Modification d'une catégorie](screenshots/s10-categories-modification.png)

![Liste des tags du back-office](screenshots/s10-tags-liste.png)

![Création d'un tag](screenshots/s10-tags-creation.png)

![Modification d'un tag](screenshots/s10-tags-modification.png)

![Page Catégorie introuvable](screenshots/s10-categorie-introuvable.png)

![Pagination en français](screenshots/s10-pagination-fr.png)

### Session 11

![Barre de navigation d'un utilisateur connecté, avec le message « Vous êtes connecté. »](screenshots/s11-connexion.png)

![Page 403 quand un auteur ouvre la modification de l'article d'un autre auteur](screenshots/s11-interdit.png)

### Session 12

![Page 403 « Réservé aux administrateurs. » pour un compte auteur sur /admin/tags](screenshots/s12-admin-interdit.png)

![Barre de navigation d'un compte auteur, sans les liens Catégories et Tags](screenshots/s12-menu-auteur.png)

### Session 13

![Postman : réponse JSON de GET /api/posts?per_page=2, avec data, links et meta](screenshots/s13-postman-liste.png)

![Postman : réponse 422 de GET /api/posts?per_page=500, avec le message en français](screenshots/s13-postman-422.png)

### Session 14

![Postman : POST /api/posts avec un jeton Bearer, onglet Body, réponse 201 Created](screenshots/s14-postman-201.png)

![Postman : PUT sur l'article d'un autre auteur, réponse 403](screenshots/s14-postman-403.png)

## Choix d'architecture

### Pourquoi les écritures de l'API passent par PostPolicy

La même règle décide qui peut modifier ou supprimer un article, que la demande vienne du site ou de l'API. Si le propriétaire change, ou si un administrateur doit être autorisé, je ne modifie qu'un seul fichier. L'API ne devient pas un raccourci qui contourne les droits du site. La Form Request appelle la Policy dans `authorize()`, donc un utilisateur non autorisé reçoit 403 avant la validation des données : l'ordre 401, 403, 422 reste stable.

### Pourquoi preventLazyLoading est activé

Une relation lue dans une boucle sans chargement anticipé produit une requête supplémentaire par article : c'est le problème N+1. En développement, `preventLazyLoading(! app()->isProduction())` transforme cette lenteur silencieuse en erreur visible. Les listes chargent donc explicitement `user`, `category` et `tags` avec `with()`, et les `whenLoaded()` de la Resource ne lisent que ces relations déjà présentes. L'option reste désactivée en production pour ne pas casser le site.

### Pourquoi l'API utilise des jetons et non la session

Le groupe `api` n'a ni session ni cookie. Un client qui n'est pas un navigateur — script, application mobile ou Postman — présente donc un jeton à chaque requête dans l'en-tête `Authorization: Bearer`. Le site garde son cookie de session pour le navigateur. Un jeton peut être révoqué seul avec `/api/logout`, sans déconnecter les autres appareils de l'utilisateur.
