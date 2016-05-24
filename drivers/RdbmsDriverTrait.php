<?php

namespace dizews\dbConsole\drivers;


use yii\db\Connection;

trait RdbmsDriverTrait
{
    public function initDsn(Connection $connection)
    {
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
