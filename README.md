# SkillSwap

SkillSwap est une plateforme web d'echange de competences entre apprenants.
Le projet utilise Laravel pour le backend, Breeze pour l'authentification web
et Spatie Permission pour la gestion des roles.

## Stack actuelle

- Laravel 13
- PHP 8.3
- Breeze pour login, inscription, mot de passe oublie et profil
- Spatie Permission pour les roles
- MySQL
- Blade, Vite, Tailwind CSS et Alpine.js

## Architecture actuelle

Le projet est organise avec la structure Laravel classique :

- `app/Models` contient les modeles Eloquent
- `app/Http/Controllers` contient les controleurs
- `app/Http/Requests` contient la validation des formulaires
- `app/Enums` contient les enums metier
- `database/migrations` contient la structure de la base de donnees
- `database/seeders` contient les seeders
- `resources/views` contient les vues Blade

## Fonctionnalites deja preparees

- Authentification utilisateur avec Breeze
- Gestion du profil
- Roles avec Spatie Permission
- Migrations de base pour les utilisateurs, competences, besoins,
  demandes d'echange, sessions, transactions et notes
- Relations Eloquent entre les modeles principaux

## Roles

Deux roles sont prepares :

- `admin`
- `student`

Lors d'une nouvelle inscription, l'utilisateur recoit automatiquement
le role `student`.

## Base de donnees

Les tables principales deja creees sont :

- `users`
- `roles`, `permissions` et tables pivot de Spatie
- `categories`
- `skills`
- `needs`
- `exchange_requests`
- `proposed_times`
- `conversations`
- `messages`
- `learning_sessions`
- `transactions`
- `ratings`

## Etat actuel

Le socle technique du backend est pret.
La prochaine etape consiste a implementer la logique metier du projet :

- enums et seeders
- CRUD des categories, competences et besoins
- demandes d'echange
- sessions d'apprentissage
- credits et transactions
- notation et reputation
