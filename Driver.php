<?php

namespace dizews\dbConsole;

use yii\base\Component;
use yii\base\Object;


abstract class Driver extends Object implements DriverInterface
{
    public $clientPath;

    public $host = '127.0.0.1';

    protected $dsn = [];

    /**
     * @param Component $connection instance of database connection
     * @param array $config
     */
    public function __construct(Component $connection, $config = [])
    {
        $this->dsn = array_fill_keys(['user', 'password', 'host', 'port', 'dbname'], null);
        $this->dsn = $this->parseDsn($connection->dsn);

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function combineParams($params)
    {
        $programParams = '';
        if ($this->getPasswordParamName()) {
            $programParams = $this->getPasswordParamName() .'='. $this->getPasswordValue();
        }
        foreach ($params as $key => $value) {
            if ($key == '') {
                $programParams .= ' '. $value;
            } else {
                $programParams .= " --{$key}={$value}";
            }
        }

        return $programParams;
    }

    public function getPasswordValue()
    {
        return isset($this->dsn['password']) ? $this->dsn['password'] : '';
    }

    public function getPasswordParamName()
    {
        return '';
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

    public function getEnv()
    {
        return null;
    }
}