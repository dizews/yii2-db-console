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

        $this->dsn['host'] = isset($this->dsn['host']) ? $this->dsn['host'] : $this->host;
        $this->dsn['port'] = isset($this->dsn['port']) ? $this->dsn['port'] : $this->port;

    }

    /**
     * @inheritdoc
     */
    public function getClientCommand()
    {
        $params = $this->getConnectionParams();
        $params[''] = $this->dsn['dbname'];

        return [$this->clientPath, 'options' => $params];
    }

    /**
     * @inheritdoc
     */
    public function getLoadCommand($file)
    {
        $params = $this->getConnectionParams();
        $params[''] = $this->dsn['dbname'] .'<'. $file;

        return [$this->clientPath, 'options' => $params];
    }

    /**
     * @inheritdoc
     */
    public function getDumpCommand($path = '')
    {
        $params = $this->getConnectionParams();
        $params[''] = $this->dsn['dbname'];
        if ($path) {
            $params[''] .= '>'. $path;
        }

        return [$this->dumpPath, 'options' => $params];
    }

    /**
     * @inheritdoc
     */
    public function getRestoreCommand($path)
    {
        return $this->getLoadCommand($path);
    }

    public function getPasswordParamName()
    {
        return '--password';
    }

    protected function getConnectionParams()
    {
        $params = [
            'host' => $this->dsn['host'],
            'port' => $this->dsn['port'],
        ];

        if (isset($this->dsn['username'])) {
            $params['user'] = $this->dsn['username'];
        }

        return $params;
    }
}