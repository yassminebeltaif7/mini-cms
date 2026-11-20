# Session 07 - Défi : articles de la même catégorie

La section « Dans la même catégorie » apparaît sous un article publié qui a une catégorie : oui
L'article affiché n'apparaît pas dans sa propre section : oui
Un brouillon de la même catégorie n'apparaît pas : oui
La page s'ouvre sans LazyLoadingViolationException : oui
Le nouveau test passe et php artisan test est vert : oui

Combien de requêtes SQL charge la section, et pourquoi pas une par carte ?

4 requêtes : une pour les articles publiés de la catégorie, puis une pour les auteurs, une pour les catégories et une pour les tags. Grâce à `with('user', 'category', 'tags')`, le nombre de requêtes ne dépend pas du nombre de cartes. Sans ce chargement anticipé, trois cartes ajouteraient 9 requêtes et la page lèverait `LazyLoadingViolationException`, car les articles sont chargés à plusieurs.

## Réponses aux quiz éclair (15 questions)

1. b — Dans la table `posts`.
2. c — 2 requêtes (1 pour les articles, 1 pour leurs catégories).
3. a — Un article a plusieurs tags et un tag plusieurs articles : la table pivot `post_tag` stocke les couples.
4. b — Aucune ligne : le seeder a été créé mais pas exécuté.
5. a — L'article reste et sa colonne `category_id` devient `NULL`.
6. c — La propriété renvoie le modèle `Category` (ou `null`), la méthode renvoie la relation sur laquelle on peut chaîner.
7. b — L'attach en double échoue : l'index unique `(post_id, tag_id)` refuse une deuxième ligne identique.
8. a — Seulement SQLite : `sync()` rend la liste exactement égale au tableau donné.
9. c — 0 : `make()` construit le modèle en mémoire, seul `create()` l'enregistre.
10. b — Seulement `is_published` et `published_at` ; le reste vient de `definition()`.
11. a — Une erreur sur une colonne unique (`users.email`), et rien n'est ajouté.
12. c — Les articles saisis à la main sont effacés : `fresh` supprime toutes les tables.
13. a — Signaler les oublis de `with()` en développement et tests, sans faire planter la production.
14. b — 4 requêtes (1 pour les articles + 1 par relation).
15. b — Laravel ne bloque le chargement paresseux que sur des modèles chargés à plusieurs ; avec un seul article l'oubli passerait inaperçu.
