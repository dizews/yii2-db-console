<?php

namespace dizews\dbConsole\drivers;

use dizews\dbConsole\Driver;
use yii\db\Connection;

class Sqlite extends Driver
{
    public $clientPath = 'sqlite3';


    public function __construct(Connection $connection, $config = [])
    {
        parent::__construct($connection, $config);
    }

    public function buildConnectionParams()
    {
        $params = [];

        $programParams = $this->dsn['dbname'];
        foreach ($params as $key => $value) {
            $programParams .= " -{$key}={$value}";
        }

        return $programParams;
    }

    protected function parseDsn($dsn)
    {
        list($driverName, $dbName) = explode(':', $dsn, 2);

        $result = [
            'driverName' => $driverName,
            'dbname' => $dbName
        ];

        return $result;
    }

}