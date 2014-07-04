<?php

namespace dizews\dbConsole\drivers;

use dizews\dbConsole\Driver;

class MongoDb extends Driver
{
    public $clientPath = 'mongo';
    public $dumpPath = 'mongodump';

    public $port = 27017;

    public $extraParams = '--norc';

    public function buildConnectionParams()
    {
        $params = [];

        if (isset($this->dsn['user'])) {
            $params['username'] = $this->dsn['user'];
        }

        if (isset($this->dsn['password'])) {
            $params['password'] = $this->dsn['password'];
        }

        $db = isset($this->dsn['host']) ? $this->dsn['host'] : $this->host;
        $db .= isset($this->dsn['port']) ? ':'. $this->dsn['port'] : $this->port;
        $db .= '/'.$this->dsn['dbname'];

        $programParams = $db;
        foreach ($params as $key => $value) {
            $programParams .= " --{$key}={$value}";
        }

        return $programParams .' '. $this->extraParams;
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