# Session 13 - Défi

/api/posts/9 affiche tags avec des noms : oui
/api/posts ne contient aucun body, /api/posts/14 en contient un : oui
Aucune LazyLoadingViolationException : oui

Pourquoi ne pas envoyer body dans la liste, et qui en profite ?
La liste sert à afficher des aperçus (titre, extrait, date, lien). Envoyer le contenu complet alourdirait la réponse et coûterait de la bande passante et du temps de sérialisation. Le client qui affiche des cartes et le serveur qui répond moins de données en profitent ; le contenu complet reste réservé à la page d'un article.

Un brouillon renvoie 403. Quel avantage aurait un 404 pour un brouillon ?
Un 404 masquerait l'existence même du brouillon : un visiteur ne pourrait pas deviner qu'un article existe sous cet identifiant. Le 403 révèle que la ressource existe mais que l'accès est refusé ; c'est utile au propriétaire, mais moins discret pour un contenu non publié.
