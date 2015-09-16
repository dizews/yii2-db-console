db-console on Yii2
==================

Help you to work with different console database clients in unified format.
Currently it support ```mysql```, ```postgres```, ```sqlite``` and ```mongodb``` drivers.

[![Total Downloads](https://poser.pugx.org/dizews/yii2-db-console/downloads)](https://packagist.org/packages/dizews/yii2-db-console)
[![Code Climate](https://codeclimate.com/github/dizews/yii2-db-console/badges/gpa.svg)](https://codeclimate.com/github/dizews/yii2-db-console)

Features
--------
- Open console database client.
- Load data from file.
- Dump data into the destination.
- Restore data from the dump.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --dev --prefer-dist dizews/yii2-db-console "*"
```

or add

```
"dizews/yii2-db-console": "*"
```

to the require section of your composer.json.

General Usage
-------------

```php
    'controllerMap' => [
        'db-console' => [
            'class' => 'dizews\dbConsole\DbController'
            //'drivers' => [
            //    'mysql' => [
            //        'class' => 'dizews\dbConsole\drivers\Mysql',
            //        'clientPath' => '/usr/local/bin/mysql'
            //    ]
            //]
        ]
    ]
```

Run database client (mysql)

Usually if you want to connect to a mysql server in terminal you need to write:

```bash
mysql --host=127.0.0.1 --port=3306 --user=user database --password=pwd
```

with this extension you just need:

```
php yii db-console
```

Run mongodb client

```
php yii db-console --name=mongodb
```


