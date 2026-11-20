# Session 06 - Défi : publier et dépublier

La route posts.publication apparaît dans route:list avec le verbe PATCH : oui
Le bouton affiche « Publier » sur un brouillon et « Dépublier » sur un article publié : oui
published_at est rempli à la publication et vidé à la dépublication : oui
Le nouveau test passe et php artisan test est vert : oui

Pourquoi PATCH et pas PUT ni GET pour ce bouton ?

PATCH parce qu'on ne modifie qu'une partie de l'article (is_published et published_at) : PUT serait destiné à remplacer la ressource entière. GET ne doit jamais modifier de données : il sert seulement à lire une page. PATCH dit clairement l'intention, et @method('PATCH') permet au navigateur d'envoyer ce verbe via un formulaire POST.
