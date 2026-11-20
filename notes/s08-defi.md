# Session 08 - Défi

La route posts.tag utilise {tag:slug} : oui
La page d'un tag n'affiche ni brouillon ni article d'un autre tag : oui
Les tags des cartes sont des liens : oui
L'accueil n'affiche que les tags qui ont au moins un article publié : oui
Les trois nouveaux tests passent et php artisan test est vert : oui

Pourquoi filtrer les tags vides après get() plutôt que d'afficher (0) ?

Un tag sans article publié mènerait à une page vide : il vaut mieux ne pas l'afficher. Filtrer après get() évite d'ajouter une condition SQL à la sous-requête ; la collection reçue est déjà complète et le filtre reste simple.

Combien de requêtes ajoute la liste des tags populaires, et pourquoi pas une par tag ?

Une seule requête. withCount() ajoute une sous-requête calculée par la base dans la requête des tags, pour tous les tags à la fois. Aucun article n'est chargé et il n'y a pas une requête par tag.
