<?php

namespace dizews\dbConsole;

use Yii;
use yii\console\Controller;
use yii\console\Exception;

/**
 * Help you to work with different console database clients in unified format.
 *
 * @package dizews\dbConsole
 */
class DbController extends Controller
{
    /**
     * @var string controller default action ID.
     */
    public $defaultAction = 'connect';

    /**
     * @var string database component name.
     */
    public $name = 'db';


    public $drivers = [
        'mysql'   => 'dizews\dbConsole\drivers\Mysql',
        'mongodb' => 'dizews\dbConsole\drivers\MongoDb',
        'sqlite'  => 'dizews\dbConsole\drivers\Sqlite',
        'pgsql'   => 'dizews\dbConsole\drivers\Postgres'
    ];

    /**
     * @var DriverInterface
     */
    protected $driver;


    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        $connection = Yii::$app->get($this->name);

        $driverName = Driver::driverName($connection);

        if (!isset($this->drivers[$driverName])) {
            throw new Exception(Yii::t('yii', 'Unknown database driver: {name}', [
                'name' => $driverName
            ]));
        }
        $this->driver = Yii::createObject($this->drivers[$driverName], [$connection]);

        return true;
    }

    /**
     * Open console database client.
     */
    public function actionConnect()
    {
        $command = $this->driver->getClientCommand();
        $stringParams = $this->driver->combineParams($command['options']);

        $this->runProgram($command[0], $stringParams);
    }

    /**
     * Load data from file.
     *
     * @param string $file
     * @throws \yii\console\Exception
     */
    public function actionLoad($file)
    {
        $path = Yii::getAlias($file);
        if (!is_file($file)) {
            throw new Exception("The file does not exist: $file");
        }

        $command = $this->driver->getLoadCommand($file);
        $stringParams = $this->driver->combineParams($command['options']);

        $this->runProgram($command[0], $stringParams);
    }

    /**
     * Dump data into the destination.
     *
     * @param string $path path to destination file or directory
     */
    public function actionDump($path = '')
    {
        $command = $this->driver->getDumpCommand($path);
        $stringParams = $this->driver->combineParams($command['options']);

        $this->runProgram($command[0], $stringParams);
    }

    /**
     * Restore data from the dump.
     *
     * @param string $path path to input dump data
     * @throws \yii\console\Exception
     */
    public function actionRestore($path)
    {
        $path = Yii::getAlias($path);
        if (!file_exists($path)) {
            throw new Exception("The file or directory does not exist: $path");
        }

        $command = $this->driver->getRestoreCommand($path);
        $stringParams = $this->driver->combineParams($command['options']);

        $this->runProgram($command[0], $stringParams);
    }


    /**
     * @inheritdoc
     */
    public function options($id)
    {
        return array_merge(parent::options($id), ['name']);
    }

    /**
     * run program in interactive mode
     *
     * @param $name string name of program
     * @param $stringParams string program parameters
     * @return int|null
     */
    protected function runProgram($name, $stringParams)
    {
        $handle = proc_open($name .' '. $stringParams, [STDIN, STDOUT, STDERR], $pipes, null, null);
        $output = null;
        if (is_resource($handle)) {
            $output = proc_close($handle);
        }

        return $output;
    }

}
