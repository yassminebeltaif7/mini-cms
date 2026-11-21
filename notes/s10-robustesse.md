# Session 10 - Robustesse

TagAdminTest contient 5 tests verts : oui
/posts/categorie/inconnue affiche Catégorie introuvable avec le statut 404 : oui
php artisan test est vert : oui

1. Pourquoi le message de messages() l'emporte-t-il sur celui de lang/fr/validation.php ?
Laravel cherche d'abord le message écrit pour cette règle de ce champ précis dans la Form Request. Le fichier de langue n'est lu que si la Form Request ne fournit pas de message : la clé slug.unique est donc plus précise que la clé générale unique, et elle gagne.

2. Pourquoi phpunit.xml fixe-t-il APP_LOCALE ?
Les tests comparent maintenant des textes français. Les valeurs de phpunit.xml s'appliquent avant celles de .env : les tests donnent ainsi le même résultat sur chaque poste et sur un clone propre, quelle que soit la langue réglée localement.

3. Pourquoi CategoryController n'a-t-il besoin d'aucun try/catch ?
Aucune action ne peut échouer d'une manière qu'il saurait réparer : la Form Request renvoie les données invalides au formulaire avant l'appel, la liaison de modèle répond 404 pour une catégorie inconnue, le middleware répond 503 en lecture seule, et le gestionnaire d'exceptions transforme ces erreurs en pages. Un try/catch ne ferait que masquer ces réponses.
