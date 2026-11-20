# Session 03 - Routes, contrôleurs et layout

## Défi

URL produite par route('about') :

http://localhost:8000/a-propos

URL produite par route('posts.show', 'routes-et-controleurs') :

http://localhost:8000/posts/routes-et-controleurs

URL produite par route('posts.archive', 2025) :

http://localhost:8000/posts/archive/2025

Pourquoi /posts/archive/abc et /posts/article-inconnu répondent toutes deux 404, et quelle ligne de code en est responsable dans chaque cas ?

Les deux URL répondent 404, mais pour deux raisons différentes. Pour `/posts/archive/abc`, c'est la contrainte `->whereNumber('year')` définie dans `routes/web.php` qui rejette la route : `abc` n'est pas un nombre, donc aucune route ne correspond et le contrôleur `PostController::archive` n'est jamais appelé. Pour `/posts/article-inconnu`, la contrainte `->where('slug', '[a-z0-9-]+')` accepte bien le slug, donc la route correspond et le contrôleur est appelé ; c'est alors la ligne `abort(404)` dans `PostController::show()` qui répond 404, parce que la clé `article-inconnu` n'existe pas dans le tableau des articles.

Que se passe-t-il si une vue appelle route('posts.show') sans paramètre ?

Laravel affiche une erreur « Missing required parameter for [Route: posts.show] [URI: posts/{slug}] [Missing parameter: slug]. » : le nom de la route existe, mais la génération de l'URL a besoin de la valeur du paramètre `{slug}` pour remplir l'URI.
