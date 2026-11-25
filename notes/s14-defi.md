# Session 14 - Défi

Connexion enregistre le jeton toute seule : oui (le script de test de « Connexion » remplit la variable `token` avec `pm.collectionVariables.set('token', pm.response.json().token)`).

Créer un article répond 201 sans copier le jeton : oui (Newman rejoue la collection : Connexion 201, Articles 200, Créer un article 201, Déconnexion 204 ; la requête utilise `{{token}}`).

Le fichier exporté ne contient aucun jeton : oui (la variable `token` est vide dans `docs/postman/mini-cms.postman_collection.json` et aucune chaîne `|` suivie de lettres ou de chiffres n'y figure).

Le dépôt est public. Que risquez-vous si le fichier exporté contient un jeton, et comment l'annuler ?

N'importe qui peut lire le jeton et l'utiliser pour agir à votre place sur l'API : lire `/api/user`, créer, modifier ou supprimer des articles avec les droits du compte. Il faut immédiatement révoquer le jeton (`POST /api/logout` avec ce jeton, ou suppression de l'empreinte dans `personal_access_tokens`), en émettre un nouveau, puis nettoyer l'historique Git (retirer le jeton du fichier, committer la correction et réécrire l'historique des commits déjà publiés si nécessaire).

Pourquoi les écritures de l'API passent-elles par les Form Requests et PostPolicy plutôt que par des règles écrites dans Api\PostController ?

Pour réutiliser exactement les mêmes règles de validation, les mêmes messages en français et la même autorisation que le site, sans duplication. Les Form Requests valident avant le contrôleur et la `PostPolicy` centralise les droits (401 pour un appel non authentifié, 403 pour l'article d'un autre auteur). Le contrôleur reste mince et l'API ne peut pas diverger du comportement du site.
