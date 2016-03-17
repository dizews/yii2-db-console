<?php

namespace dizews\dbConsole\drivers;

use dizews\dbConsole\Driver;
use yii\db\Connection;

class Postgres extends Driver
{
    use RdbmsDriverTrait;

    public $clientPath = 'psql';
    public $dumpPath = 'pg_dump';

    public $port = 5432;


    public function __construct(Connection $connection, $config = [])
    {
        parent::__construct($connection, $config);
        $this->initDsn($connection);
    }

    public function getPasswordParamName()
    {
        return '';
    }

    public function getEnv()
    {
        $env = parent::getEnv();
        $env['PGPASSWORD'] = $this->dsn['password'];

        return $env;
    }
}