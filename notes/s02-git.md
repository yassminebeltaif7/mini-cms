# Session 02 - Git et GitHub

Réponses rédigées avec mes propres mots, puis comparées au corrigé de la page.

## 1. Index et commit

Réponse : Pour préparer un fichier, j'utilise `git add nom-du-fichier` : le fichier passe du répertoire de travail à l'index, aussi appelé zone d'attente. Ensuite, `git commit -m "message"` enregistre les fichiers de l'index dans le dépôt local et ajoute un nouveau commit à l'historique. Pour envoyer ce commit sur GitHub, je fais ensuite `git push`. L'index n'est donc qu'une étape intermédiaire : tant que je n'ai pas fait `commit`, rien n'est enregistré définitivement.

## 2. Branche et tag

Réponse : Une branche est une ligne de travail qui avance automatiquement à chaque nouveau commit : ici, c'est `main`, et elle suit tous les commits du sprint. Un tag, lui, est un repère fixe : je l'ai posé sur le commit de fin de la session 01 avec `git tag lab-01` et il ne bouge plus, même quand `main` avance. Le tag `lab-01` désigne toujours exactement la même version du projet, ce qui permet de retrouver facilement l'état de la session 01.

## 3. Le fichier .env

Réponse : Le fichier `.env` contient la clé `APP_KEY` et tous les réglages propres à mon poste (base de données, fuseau horaire, etc.) ; en production, il peut aussi contenir des secrets comme des mots de passe ou des clés d'API. S'il était publié sur GitHub, n'importe qui pourrait lire ces informations et la clé de chiffrement de l'application. C'est pourquoi il est exclu dans `.gitignore`. Un autre développeur récupère le modèle `.env.example`, qui ne contient que des valeurs vides : il le copie avec `cp .env.example .env` (ou `copy` sur PowerShell), puis génère sa propre clé avec `php artisan key:generate`.

## 4. Le dossier vendor/

Réponse : Le dossier `vendor/` contient toutes les bibliothèques installées par Composer (Laravel, Pest, etc.). Il est volumineux et entièrement généré : il n'y a donc aucune raison de le versionner, d'autant que son contenu peut dépendre du système. Le fichier `composer.lock` suffit : il enregistre la version exacte de chaque paquet. Un autre développeur reconstruit alors le dossier à l'identique avec `composer install`.

## 5. Git Credential Manager

Réponse : Git Credential Manager (GCM) enregistre mon jeton d'authentification GitHub dans le Gestionnaire d'identification Windows, sous l'entrée `git:https://github.com`. Grâce à lui, je n'ai pas à retaper mon mot de passe à chaque `git push`. Sur mon ordinateur personnel, je peux le garder : les prochains pushes se feront sans reconnexion. Sur un poste de l'université, je dois le supprimer en fin de séance avec `cmdkey /delete:git:https://github.com`, sinon la personne suivante pourrait pousser sur mon dépôt en mon nom.

## 6. Historique depuis lab-01

Nombre de commits depuis lab-01 :

6 avant ce dernier commit. Ils correspondent, du plus ancien au plus récent, aux notes Git de la session 02, à la page `/a-propos`, à sa capture, à la correction des captures de la session 01, au README du projet et au tableau des routes du défi.

Fichiers modifiés depuis lab-01 :

`README.md`, `notes/s02-git.md`, `resources/views/a-propos.blade.php`, `routes/web.php`, `screenshots/s01-accueil.png`, `screenshots/s01-bienvenue.png`, `screenshots/s01-route-list.png` et `screenshots/s02-a-propos.png`, soit 8 fichiers au total.

Ce que git tag -n affiche pour lab-01 :

`lab-01          lab-01`

Le tag porte toujours le même message et le même commit : il n'a pas bougé pendant que la branche `main` avançait.
