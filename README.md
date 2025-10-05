# TabbyLD-Client

**TabbyLD-Client** is a web-based software (<i>a client application</i>) for semantic interpretation of spreadsheets.

TabbyLD-Client is based on [PHP 7](https://www.php.net/releases/7.0/ru.php) and [Yii 2 Framework](http://www.yiiframework.com/).

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Build Status](https://travis-ci.org/yiisoft/yii2-app-basic.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-app-basic)


### Version

1.0


### DIRECTORY STRUCTURE

      assets/             contains assets definition
      commands/           contains console commands for creation of langs by-default
      config/             contains application configurations
      controllers/        contains controllers
      migrations/         contains all database migrations
      models/             contains database models
      views/              contains views
      web/                contains the entry script and Web resources


### REQUIREMENTS

The minimum requirement by this project template that your Web server supports <b>PHP 7.0</b> and <b>PostgreSQL 9.0</b>.


### INSTALLATION

### Install via Git
If you do not have [Git](https://git-scm.com/), you can install [it](https://git-scm.com/downloads) depending on your OS.

You can clone this project into your directory (recommended installation):

~~~
git clone https://github.com/tabbydoc/tabbyld2_client.git
~~~


### CONFIGURATION

#### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=tabbyld2client;',
    'username' => 'postgres',
    'password' => 'root',
    'charset' => 'utf8',
    'tablePrefix' => 'tabbyld_',
    'schemaMap' => [
        'pgsql'=> [
            'class'=>'yii\db\pgsql\Schema',
            'defaultSchema' => 'public'
        ]
    ],
];
```

**NOTES:**
- TabbyLD-Client won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.

### USING

#### Commands for configuring database

Applying migrations (creating tables in a database):
~~~
php yii migrate/up
~~~
Creating default locale records in a database:
~~~
php yii lang/create
~~~

**NOTES:**
- Commands are entered sequentially into the console, being in the folder with the project.


### AUTHORS

* [Nikita O. Dorodnykh](mailto:tualatin32@mail.ru)
* [Aleksandr Yu. Yurin](mailto:iskander@icc.ru)