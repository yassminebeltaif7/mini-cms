# Session 11 - Défi

/mes-articles ne montre que mes articles, brouillons compris : oui
Un visiteur qui ouvre /mes-articles arrive sur /connexion : oui
Le cookie remember_web_ apparaît quand la case est cochée : oui
Les deux nouveaux tests passent et php artisan test est vert : oui

## Pourquoi partir de $request->user()->posts() plutôt que de Post::where('user_id', $request->user()->id) ?

La relation posts() porte déjà la bonne condition : elle ne vise que les articles de l'utilisateur connecté. Aucun champ de formulaire ni paramètre d'adresse ne peut la diriger vers un autre utilisateur. On part de la session, pas d'une valeur fournie par le navigateur.

## Pourquoi la case remember ne doit-elle pas être dans le tableau passé à Auth::attempt() ?

Auth::attempt() utilise chaque clé du tableau comme une colonne à comparer dans la table users, seule la clé password est traitée à part. Si on y ajoutait remember, Laravel chercherait une colonne remember inexistante et renverrait une erreur SQL au lieu de connecter l'utilisateur. La case se lit donc avec $request->boolean('remember') et se passe en second argument de attempt().
