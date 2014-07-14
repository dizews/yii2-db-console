<?php

namespace dizews\dbConsole\drivers;

use dizews\dbConsole\Driver;
use SebastianBergmann\Exporter\Exception;
use yii\db\Connection;

class Sqlite extends Driver
{
    public $clientPath = 'sqlite3';


    public function __construct(Connection $connection, $config = [])
    {
        parent::__construct($connection, $config);
    }

    /**
     * @inheritdoc
     */
    public function getClientCommand()
    {
        $params[''] = $this->dsn['dbname'];

        return [$this->clientPath, 'options' => $params];
    }

    /**
     * @inheritdoc
     */
    public function getLoadCommand($file)
    {
        $params[''] = $this->dsn['dbname'] ." '.read {$file}'";

        return [$this->clientPath, 'options' => $params];
    }

    /**
     * @inheritdoc
     */
    public function getDumpCommand($path = '')
    {
        $params[''] = $this->dsn['dbname'] .' ".dump" > '. $path;

        return [$this->clientPath, 'options' => $params];
    }

    /**
     * @inheritdoc
     */
    public function getRestoreCommand($path)
    {
        $params[''] = $this->dsn['dbname'] .' <'. $path;

        return [$this->clientPath, 'options' => $params];
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