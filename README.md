### 1 . symfony.com -> documentation -> setup -> récupérer et taper ce code dans le terminal :

 $ composer create-project symfony/website-skeleton nomduprojet

#### ne pas hésitez à essayer, taper dans le terminal dans le dossier du projet : 
 
 $ php bin/console server:run

### 2 . Ajouter les logiciels "admin && api", taper dans le terminal (ce qui nous permet d'installer api platform, et l'admin nous permettera de faire l'authentification)

$ composer require admin 
$ composer require api

### 3 . Dans config/packages/doctrine.yml, ligne 12, changer la version en 5.6 au lieu de 5.7 (sinon ça nous posera problème pour plus tard) 

### 4 . créer à la racine du dossier projet le fichier .env.local, et on va prendre dans le fichier .env la ligne 27, la ligne 17 et 18, afin de les insérer dans notre fichier.env.local (pour mettre le fichier automatiquement en .gitignore), en suite modifier db_user par votre pseudo phpmyadmin, ainsi que le db_password par votre mots de passe, et modifier db_name par le nom de votre Base de donnée (mettre le même nom que votre dossier créer, c'est conseillé)

exemple : 
ligne 28 :
-> DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
ligne 17 :
-> APP_ENV=dev
ligne 18 :
-> APP_SECRET=2757187901da7f0hfa9f5e01f3e46610


### 5 . en suite créer la base de donnée, taper la commande :

$ php bin/console doctrine:database:create

### 6 . On va commencer à créer les "entity", intégrer les colonnes etc..., et surtout accepter de vouloir intégrer dans l'API.

#### Pour pouvoir créer les entity, taper cette commande : 

$ php bin/console make:entity

#### Commençons par créer la  première entity promotion : 

- 1er question, il demande de nommé votre entity -> promotion

- 2ieme, indiquer que vous souhaitez l'intégrer dans l'API -> yes

- 3ieme, il vous demande de créer votre première colonne -> name

    -> donc, pour le type = string
    -> nombre de caractère autorisé = 255
    -> nullable ou pas (c'est à dire optionnel ou non) -> no

- pour ajouter d'autres colonnes, il vous demande si vous souhaiter en ajouter une autre, nommé directement votre prochaine colonne -> createdAt

    -> donc, pour le type = dateTime
    -> nullable ou pas (c'est à dire optionnel ou non) -> no

- autres colonnes -> dateEnd

    -> donc, pour le type = dateTime
    -> nullable ou pas (c'est à dire optionnel ou non) -> no

- on valide jusqu'à en sortir pour reprendre sur une nouvelle création d'entity.


### 7 . Pour la prochaine entity "student", refaire la commande suivante : 

$ php bin/console make:entity

- Donc, vous donnez le nom de votre entity -> student

- On indique qu'on souhaite l'intégrer à l'API

- ainsi de suite, c'est le même principe que l'entity promotion pour l'insertion.

Sauf que les colonnes seront firstname, lastname, et promotion (pour le ManyToOne)

## ATTENTION IMPORTANT !!! pour la colonne "promotion" qui va nous permettre de faire la relation "ManyToOne", il faut quand même la créer.

donc on ajoute la colonne promotion à la suite.

    -> donc, pour le type = relation
    -> nombre de caractère autorisé = 255
    -> nullable = no

Grâce à ce mots clé, ça va déclencher la demande de relation.

- Pour la question suivante pour préciser que c'est nullable, on indique  : yes

- en suite, la question suivante, on précise : yes
afin de finaliser la relation.

- question suivante, il vous mets normalement par défaut, le nom de l'entity student, avec un S, on valide.

et voilà la relation, ainsi que la création sont terminé !! 

### 8 . Aller dans config/packages/easy_admin.yaml

#### -> supprimer, le code existant, et y ajouter le code suivant:

easy_admin:
  entities:
          Promotion:
                class: App\Entity\Promotion
                form:
                  fields: 
                      - "name"
                      - "createdAt"
                      - "dateEnd"
                      - { property: 'students', type: 'entity', type_options: { choice_label: 'firstname'}}
          Student:     
                class:  App\Entity\Student
                form:
                  fields: 
                      - "firstname"
                      - "lastname"
                      - { property: 'promotion', type: 'entity', type_options: { choice_label: 'name'}}

### 9 . Ne pas oublier de faire la migration de la base de donnée
#### s'assurer que vous n'avez pas oublié d'utiliser la commande pour créer la bdd, "$ php bin/console doctrine:database:create"

$ php bin/console make:migration
$ php bin/console do:mi:mi
-> valider avec YES

- s'assurer que la bdd remonte bien sur phpmyadmin, et que les relations sont correct.

- essayer :

$ php bin/console server:run

- une fois la page internet ouverte, ajouter "/admin" pour voir si ça fonctionne.

### 10 . Création d'un controller de base

$ php bin/console make:controller

- donner le Nom de votre fichier controller, en revanche toujours mettre "Controller" à la fin.

ex: MainController

### à la suite de ça, on constate qu'il y a eu un fichier de créer dans template/main/index.html.twig, et un second nommée "MainController.php", (attention si lors de la création du controller vous avez changer de nom, ça sera forcement QuelquechoseController.php)

Pour essayer:

$ php bin/console server:run

- sur le naviguateur, /main, (ça représente le début de nom de votre controller) pour avoir l'apperçue du fichier index.html.twig

### 11 . Afin de mieux comprendre le système des templates, aller dans templates/main/ et créer un fichier nommé "hello.html.twig" et y ajouter le code suivant :

```
{# templates/main/hello.html.twig #}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>{{ greeting }}</title>
</head>
<body>
    <h1>{{ greeting }}</h1>
</body>
</html>
```

### 12 . Accéder au service database_connection (optionnel), à ajouter dans le fichier de votre Controller, pour moi c'est src/Controller/MainController.php

```
use Doctrine\DBAL\Connection;

// ...

    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }
```

### 13 . Ajouter le Bundle Request, car sinon cela nous posera problème, à ajouter dans le fichier MainController

```
use Symfony\Component\HttpFoundation\Request;
```

### 13 . Il faut aussi créer un template qui peut afficher les données de la requête SQL. Dans le dossier templates/main, créer le fichier items-index.html.twig :

```
{# templates/main/items-index.html.twig #}
{% block title %}Liste des items{% endblock %}
{% block body %}
<h1>Liste des items</h1>

<ul>
{% for item in items %}
    <li><a href="/item/{{item.id}}">{{ item.name }}</a></li>
{% endfor %}
</ul>
{% endblock %}
```

### 14 . Authentification par mot de passe, création d'une route sécurisée, Ouvrir le fichier src/Controller/MainController.php et ajouter la route suivante :

```
    /**
     * @Route("/secured", name="main_secured")
     */
    public function secured(Request $request)
    {
        return $this->render('main/secured.html.twig', [
            // ...
        ]);
    }
```

### 15 . Créez le fichier secured.html.twig dans le dossier templates/main :

```
{% extends 'base.html.twig' %}

{% block title %}Hello {{ app.user.username }}!{% endblock %}

{% block body %}
<h1>Hello {{ app.user.username }}!</h1>
{% endblock %}
```



### 16 . Ouvrir le fichier config/packages/security.yaml pour protéger la nouvelle route. Modifier la partie access_control : 

```
    access_control:
        # - { path: ^/admin, roles: ROLE_ADMIN }
        # - { path: ^/profile, roles: ROLE_USER }
        - { path: ^/admin, roles: IS_AUTHENTICATED_FULLY }
```

### 17 . 






### mettre à jour PHP, afin de faire fonctionner composer sur le VPS.

### 1 . se connecter à votre VPS

```
ssh jthery@thery-ble.com
```

### 2 . aller dans le fichier etc/apt/ et taper :

```
sudo nano sources.list
```

- une fois dedans, modifier les "stretch" par "testing", enregistrer, quitter.

### 3 . taper les commande suivante : 

```
sudo apt upgrade
sudo apt dist-upgrade

```

il va vous demander si vous souhaiter restart, acceptez.

### 4 . Installer composer sur le VPS

```
$ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
$ php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
$ php composer-setup.php
$ php -r "unlink('composer-setup.php');"
```

```
$ mv composer.phar /usr/local/bin/composer
```

vérifier que composer est bien installé, et fonctionne:
```
composer -V
```

### 5 . Afin de faire fonctionner symfony, installer php-curl et php-zip

```
sudo apt-get install php-curl
sudo service apache2 restart
sudo apt install php-zip
```

### 6 . Cloner le dossier concerné, et exécutez le code suivant :

```
composer install
```