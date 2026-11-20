# Séance 01 — Cycle de vie d'une requête vers /heure

Quand le navigateur demande `http://localhost:8000/heure`, voici les fichiers traversés, dans l'ordre :

1. **`public/index.php`** — seul point d'entrée du site. Il vérifie d'abord le mode maintenance, puis il charge la suite.
2. **`vendor/autoload.php`** — charge automatiquement toutes les classes Composer (Laravel, Pest,etc.).
3. **`bootstrap/app.php`** — crée l'application Laravel. Grâce à `->withRouting(web: ...)`, il déclare que les routes web se trouvent dans `routes/web.php` ; il déclare aussi la route de santé `/up`, les middlewares et les exceptions.
4. **`Request::capture()`** — transforme la requête HTTP reçue en objet PHP `Request` (URL, méthode GET, en-têtes…).
5. **`$app->handleRequest(...)`** — le moteur Laravel prend le relais : il fait passer la requête dans les middlewares, cherche la route correspondante, exécute son code, puis prépare la réponse.
6. **`routes/web.php`** — la table des routes. Laravel y trouve `Route::get('/heure', ...)` et récupère la fonction associée.
7. **La closure de la route** — elle appelle `now()` (heure de Tunis, définie dans **`config/app.php`** avec `'timezone' => 'Africa/Tunis'`), puis formate l'heure en `H:i` et la date en `d/m/Y`, et renvoie `view('heure', [...])`.
8. **`resources/views/heure.blade.php`** — la vue Blade reçoit les variables `$heure` et `$date`. Blade transforme `{{ $heure }}` et `{{ $date }}` en HTML échappé.
9. **`storage/framework/views/`** — contient la version PHP compilée de la vue (générée automatiquement par Blade, à ne pas modifier).
10. **La réponse HTTP** — Laravel renvoie la page HTML au navigateur, qui l'affiche.

En résumé : **public/index.php → vendor/autoload.php → bootstrap/app.php → Request::capture() → handleRequest() → routes/web.php → closure → resources/views/heure.blade.php → storage/framework/views/ → réponse HTTP**.

Côté MVC : la **vue** est le fichier Blade, la **closure** joue ici le rôle du contrôleur, et le **modèle** interviendrait si l'on allait chercher des données (par exemple dans SQLite via `database/database.sqlite`) avant de rendre la vue.
