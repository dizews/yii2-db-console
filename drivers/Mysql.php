<?php

namespace dizews\dbConsole\drivers;

use dizews\dbConsole\Driver;
use yii\db\Connection;

class Mysql extends Driver
{
    use RdbmsDriverTrait;
    
    public $clientPath = 'mysql';
    public $dumpPath = 'mysqldump';

    public $port = 3306;


    public function __construct(Connection $connection, $config = [])
    {
        parent::__construct($connection, $config);
        $this->initDsn($connection);
    }

    
    public function getPasswordParamName()
    {
        return '--password';
    }

}