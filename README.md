
This is an API and ADMIN creation with symfony, to validate one of our ecf at Popschool Lens.

The project aims to be able to connect with an authentication, to be able to access the admin via an API, which gives us the possibility to add students and promotion in the API.

The data entered is directly fed back into an SQL database, when you make your registration on the URL register.


Project was made with symfony 4.2

## Install
Pré-requisite

* composer
* symfony 4.2
* php 7.2

Installation guide

```
* Clone this repository
```

```
In the .env file, modify
DATABASE_URL=mysql://username:password@127.0.0.1:3306/ecf_api_admin
```

```
don't forget to change username and password with your phpmyadmin access
port 3306 for mysql
in windows : mariadb default used so the port 3307 will be used
```

```
* When it's done, open a terminal then type
composer install
```

```
* then:
    > php bin/console do:da:cr
```

```
* then:
    > php bin/console make:migration
```

```
* finally:
    > php bin/console do:mi:mi
```

### Admin password

```
Open a terminal:
    > php bin/console security:encode-password
```

Type the password you want, the tool will encode it, you will only have to copy the new code displayed


To modify the admin passwordof the api go in the file config/packages/security.yml


Paste the new code

```
**providers:
    in_memory:
        memory:
            users:
                admin:
                    password: PASTE HERE
                    roles: 'ROLE_ADMIN'**
```

Now, when you will go in the admin interface you will be invited to enter your username and password if you want to continue


------------------------
FRENCH version


Ceci est une création d'API et ADMIN avec symfony, afin de valider l'un de mes ecf chez Popschool Lens.

Réalisé en Février 2019.

Le projet a pour but de pouvoir se connecter avec une authentification, pour pouvoir en suite accéder à l'admin via une api, ce qui nous donne la possibilité de pouvoir ajouter des étudiants et promotion dans l'API.

Les données saisies sont directement réinjecter dans une base de donnée SQL, quand vous faites votres enregistrement sur l'URL register.The data entered is directly fed back into an SQL database, when you make your registration on the URL register.