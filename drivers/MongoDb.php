<?php

namespace dizews\dbConsole\drivers;

use dizews\dbConsole\Driver;

class MongoDb extends Driver
{
    public $clientPath = 'mongo';
    public $dumpPath = 'mongodump';
    public $restorePath = 'mongorestore';

    public $port = 27017;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->dsn['host'] = isset($this->dsn['host']) ? $this->dsn['host'] : $this->host;
        $this->dsn['port'] = isset($this->dsn['port']) ? $this->dsn['port'] : $this->port;
    }

    /**
     * @inheritdoc
     */
    public function getClientCommand()
    {
        $params = $this->getLoginParams();
        $params[''] = $this->dsn['host'] .':'. $this->dsn['port'] .'/'. $this->dsn['dbname'];

        return [$this->clientPath, 'options' => $params];
    }

    /**
     * @inheritdoc
     */
    public function getLoadCommand($file)
    {
        $params = $this->getLoginParams();
        $params[''] = $this->dsn['host'] .':'. $this->dsn['port'] .'/'. $this->dsn['dbname'];

        $params[''] .= ' '.$file;

        return [$this->clientPath, 'options' => $params];
    }

    /**
     * @inheritdoc
     */
    public function getDumpCommand($path = '')
    {
        $params = $this->getLoginParams();

        $params['db'] = $this->dsn['dbname'];
        $params['host'] = $this->dsn['host'];
        $params['port'] = $this->dsn['port'];
        if ($path) {
            $params[''] = '-o '. $path;
        }

        return [$this->dumpPath, 'options' => $params];
    }

    /**
     * @inheritdoc
     */
    public function getRestoreCommand($path)
    {
        $params = $this->getLoginParams();

        $params['db'] = $this->dsn['dbname'];
        $params['host'] = $this->dsn['host'];
        $params['port'] = $this->dsn['port'];
        $params[''] = $path;

        return [$this->restorePath, 'options' => $params];
    }

    public function getPasswordParamName()
    {
        return $this->getPasswordValue() ? '--password' : '';
    }

    protected function getLoginParams()
    {
        $params = [];

        if (isset($this->dsn['user'])) {
            $params['username'] = $this->dsn['user'];
        }

        return $params;
    }

    protected function parseDsn($dsn)
    {
        $exp = '/^((?P<driver>[\w\d]+):\/\/)?((?P<user>[\w_]+)(:(?P<password>[\w\d_-]+))?@)?(?P<host>[.\w]+)(:(?P<port>\d+))?\/(?P<dbname>[\w_]+)$/im';

        $result = [];
        if (preg_match($exp, $dsn, $matches)) {
            foreach ($matches as $key => $value) {
                if (array_key_exists($key, $this->dsn) && !empty($value)) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }
}