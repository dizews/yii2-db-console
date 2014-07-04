<?php

namespace dizews\dbConsole\drivers;

use dizews\dbConsole\Driver;
use yii\db\Connection;

class Mysql extends Driver
{
    public $clientPath = 'mysql';
    public $dumpPath = 'mysqldump';

    public $port = 3306;


    public function __construct(Connection $connection, $config = [])
    {
        parent::__construct($connection, $config);

        $this->dsn['username'] = $connection->username;
        $this->dsn['password'] = $connection->password;
    }

    public function buildConnectionParams()
    {
        $params = [
            'user' => $this->dsn['username'],
            'host' => isset($this->dsn['host']) ? $this->dsn['host'] : $this->host,
            'port' => isset($this->dsn['port']) ? $this->dsn['port'] : $this->port,
        ];

        if ($this->dsn['password']) {
            $params['password'] = $this->dsn['password'];
        }

        $programParams = $this->dsn['dbname'];
        foreach ($params as $key => $value) {
            $programParams .= " --{$key}={$value}";
        }

        return $programParams;
    }

}