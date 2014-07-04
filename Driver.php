<?php

namespace dizews\dbConsole;

use yii\base\Component;
use yii\base\Object;


abstract class Driver extends Object
{
    public $host = '127.0.0.1';

    protected $dsn = [];

    /**
     * @param Component $connection instance of database connection
     * @param array $config
     */
    public function __construct(Component $connection, $config = [])
    {
        parent::__construct($config);

        $this->dsn = array_fill_keys(['user', 'password', 'host', 'port', 'dbname'], null);

        $this->dsn = $this->parseDsn($connection->dsn);
    }

    /**
     * get name of database driver
     *
     * @param $connection instance of database connection
     * @return string
     */
    public static function driverName($connection)
    {
        if ($connection->hasProperty('driverName')) {
            $driverName = $connection->driverName;
        } elseif (($pos = strpos($connection->dsn, ':')) !== false) {
            $driverName = strtolower(substr($connection->dsn, 0, $pos));
        }

        return $driverName;
    }

    protected function parseDsn($dsn)
    {
        $result = [];

        $params = explode(':', $dsn, 2);
        $params = explode(';', $params[1]);
        foreach ($params as $v) {
            list($name, $value) = explode('=', $v);
            $result[$name] = $value;
        }

        return $result;
    }
}