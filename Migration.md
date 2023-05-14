# Comment migré de la version 3 à la version 6 de symfony

## Création d'un nouveau projet vierge

```bash
symfony new --full my_project
```

## Copie des fichiers de l'ancien projet

### Controllers
    
```bash
cp -r old_project/src/AppBundle/Controller my_project/src/Controller
```

### Entities

On créer 2 nouvelles entitées avec les commandes suivantes :

```bash
php bin/console make:entity
php bin/console make:entity
```
On remplit alors les champs de la même façon que dans l'ancien projet.
On créer ensuite les migrations avec la commande :

```bash
php bin/console make:migration
```

On vérifie que les fichiers de migrations sont bien dans le dossier migrations du nouveau projet.
On lance ensuite la migration avec la commande :

```bash
php bin/console doctrine:migrations:migrate
```

### Forms

```bash
cp -r old_project/src/AppBundle/Form my_project/src/Form
```

### Templates
    
```bash
cp -r old_project/src/web my_project/templates
```

On vérifie toujours que les bons namespace sont utilisés dans les fichiers copiés, le cas contraire une erreur sera levée.

Le nouveau projet sera alors sur la version 6 de symfony. Il faudra alors vérifier que les fichiers de configuration sont bien à jour. Pour cela, il faut comparer les fichiers de configuration du nouveau projet avec ceux de l'ancien projet. Si des différences sont présentes, il faudra les ajouter au nouveau projet. Il faudra aussi vérifier que les fichiers de configuration du nouveau projet sont bien à jour avec la version 6 de symfony. Pour cela, il faut se référer à la documentation de symfony.

## Installation des dépendances

On installe les dépendances avec la commande :

```bash
composer install
```
