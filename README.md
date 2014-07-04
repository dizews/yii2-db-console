db-console on Yii2
==================

The yii2 extension help to work in console with databases

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
return [
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
    ],
];
```

Run database client (mysql)

```
php yii db-console
```

Run mongodb client

```
php yii db-console --name=mongodb
```


