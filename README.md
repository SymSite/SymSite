SymSite
=======

Simple CMS on Symfony framework.

## Reauirements
- PHP >= 5.5
- Database
 - SQLite
 - MySQL
 - PostgreSQL

## Instration
```
$ composer create-project symsite/symsite path/to/install
$ cd path/to/install
$ composer install
$ bin/bowerphp install
$ php app/console assets:install --symlink
```

## Configration
```
# app/config/parameters.yml

parameters:
    # MySQL, PostgreSQL
    database_host: 127.0.0.1
    database_port: 3306
    database_name: YOURE_DATABASE_NAME
    database_user: YOURE_USER_NAME
    database_password: YOURE_USER_PASSWORD

    # SQLie database path
    database_path: '%kernel.root_dir%/data.db3'

    # Mail
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: YOURE_MAIL_USER_ID
    mailer_password: YOURE_MAIL_USER_PASSWORD

    # Mail Form
    mailform_delivery_to: YOURE_MAIL_ADDRESS

    # Symfony2 Secret Generator -> http://nux.net/secret
    secret: ThisTokenIsNotSoSecretChangeIt
```

```
# app/config/config.yml

parameters:
    locale: en
    site_name: YOUR_SITE_NAME

...

doctrine:
    dbal:
        # pdo_mysql | pdo_pgsql | pdo_sqlite
        driver: pdo_sqlite

```

```
# app/config/security.yml

security:
    providers:
        in_memory:
            memory:
              users:
                  admin:
                      # Admin Pasword
                      password: admin123
```


## Setup Database
```
$ cd path/to/install
$ php app/console doctrine:schema:create
```

## Run SymSite
```
$ cd path/to/install
$ php app/console server:run

Server running on http://127.0.0.1:8000
...
```

## Customize

You can customeize a SymSite in the Symfony way.
- Twig Template
- Form
- Doctrine ORM
- Etc.
