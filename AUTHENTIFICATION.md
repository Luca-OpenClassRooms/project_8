# Documentation technique concernant l'authentification

## Introduction

L'authentification est une fonctionnalité essentielle pour notre application. Elle permet de sécuriser l'accès à l'application et de gérer les droits d'accès des utilisateurs.

## Technologies utilisées

Pour l'authentification, nous avons utilisé la librairie symfony/security-bundle. Cette librairie permet de gérer l'authentification et les droits d'accès des utilisateurs.

## Fonctionnement

### Authentification

L'authentification est gérée par le fichier `security.yaml` situé dans le dossier `config/packages`. Ce fichier contient la configuration de l'authentification.

```yaml
security:
    # https://symfony.com/doc/current/security.html#registering-the-user-hashing-passwords
    password_hashers:
        Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: 'auto'
    # https://symfony.com/doc/current/security.html#loading-the-user-the-user-provider
    providers:
        # used to reload user from session & other features (e.g. switch_user)
        app_user_provider:
            entity:
                class: App\Entity\User
                property: username
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            lazy: true
            provider: app_user_provider

            form_login:
                login_path: login
                check_path: login
                always_use_default_target_path:  true
                default_target_path:  /

            logout:
                path: logout                

            # activate different ways to authenticate
            # https://symfony.com/doc/current/security.html#the-firewall

            # https://symfony.com/doc/current/security/impersonating_user.html
            # switch_user: true

    # Easy way to control access for large sections of your site
    # Note: Only the *first* access control that matches will be used
    access_control:
        # - { path: ^/admin, roles: ROLE_ADMIN }
        # - { path: ^/profile, roles: ROLE_USER }
        - { path: ^/login, roles: PUBLIC_ACCESS }
        - { path: ^/users, roles: ROLE_ADMIN }
        - { path: ^/, roles: ROLE_USER }

when@test:
    security:
        password_hashers:
            # By default, password hashers are resource intensive and take time. This is
            # important to generate secure password hashes. In tests however, secure hashes
            # are not important, waste resources and increase test times. The following
            # reduces the work factor to the lowest possible values.
            Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface:
                algorithm: auto
                cost: 4 # Lowest possible value for bcrypt
                time_cost: 3 # Lowest possible value for argon
                memory_cost: 10 # Lowest possible value for argon
```

Ce fichier contient plusieurs sections :

- `password_hashers` : Cette section permet de définir le type de hashage utilisé pour les mots de passe des utilisateurs. Nous avons choisi d'utiliser le hashage `auto` qui permet de choisir le type de hashage en fonction de la configuration du serveur.

- `providers` : Cette section permet de définir le fournisseur d'utilisateurs. Nous avons choisi d'utiliser le fournisseur `entity` qui permet de récupérer les utilisateurs depuis la base de données.

- `firewalls` : Cette section permet de définir les pare-feux de l'application. Nous avons choisi d'utiliser deux pare-feux : `dev` et `main`. Le pare-feu `dev` est utilisé pour les environnements de développement. Le pare-feu `main` est utilisé pour les environnements de production. Le pare-feu `main` est le pare-feu principal de l'application. Il permet de gérer l'authentification des utilisateurs.

- `access_control` : Cette section permet de définir les contrôles d'accès de l'application. Nous avons choisi de définir plusieurs contrôles d'accès. Le premier contrôle d'accès permet d'accéder à la page de connexion. Le deuxième contrôle d'accès permet d'accéder à la page de gestion des utilisateurs. Le troisième contrôle d'accès permet d'accéder à toutes les autres pages de l'application.

### Gestion des droits d'accès

La gestion des droits d'accès est gérée par l'anotation `@IsGranted` située dans les contrôleurs. Cette anotation permet de définir les droits d'accès nécessaires pour accéder à une fonctionnalité.

```php
#[Route('/users', name: 'users')]
#[IsGranted('ROLE_ADMIN')]
public function index(UserRepository $userRepository): Response
{
    // ...
}
```

## Comment modifier ou ajouter utilisateur ?

Pour modifier la création d'un utilisateur, il faut modifier les fonctions `createAction` ou `editAction` situées dans le contrôleur `UserController.php` situé dans le dossier `src/Controller`.

Celles-ci contient un formulaire qui permet de créer un utilisateur.

Vous pouvez modifier ce formulaire en modifiant le fichier `UserType.php` situé dans le dossier `src/Form`.


## Comment s'opère l'authentification ?

L'authentification est gérée par le fichier `SecurityController.php` situé dans le dossier `src/Controller`.

Ce fichier contient deux fonctions :

- `loginAction` : Cette fonction permet d'afficher la page de connexion.
- `logoutCheck` : Cette fonction permet de déconnecter l'utilisateur.

## Où sont stockés les utilisateurs ?

Les utilisateurs sont stockés dans la base de données. Pour accéder à la base de données, il faut utiliser le fichier `User.php` situé dans le dossier `src/Entity`.

Ce fichier contient les informations relatives aux utilisateurs.
