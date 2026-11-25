# Session 12 - Défi

1. `role` n'est pas dans `#[Fillable]` dans `app/Models/User.php` : l'inscription ne transmet que `name`, `email` et `password`, donc un champ `role` ajouté à la main dans un formulaire ou un `$request->all()` ne pourrait pas modifier le rôle. Le contrôleur `app/Http/Controllers/Admin/UserController.php` affecte explicitement `$user->role = $request->validated()['role']` après l'autorisation de `UserPolicy` et la validation par `UpdateUserRoleRequest`, puis il appelle `$user->save()`.

2. Un callback `Gate::before` qui renvoie `false` donne une réponse définitive : un auteur serait refusé partout, même pour modifier ses propres articles. Renvoyer `null` signifie « pas d'avis » : Laravel poursuit avec le Gate ou la méthode de Policy correspondante. La capacité `updateRole` est volontairement exclue du raccourci administrateur pour que la règle du niveau 1 (interdiction de changer son propre rôle) reste appliquée par `UserPolicy`.
