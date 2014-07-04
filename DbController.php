<?php

namespace dizews\dbConsole;

use Yii;
use yii\console\Controller;
use yii\console\Exception;


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
        'mysql' => 'console\modules\dbconsole\drivers\Mysql',
        'mongodb' => 'console\modules\dbconsole\drivers\MongoDb',
        'sqlite' => 'console\modules\dbconsole\drivers\Sqlite'
    ];

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

    public function actionConnect()
    {
        $stringParams = $this->driver->buildConnectionParams();

        $this->runProgram($this->driver->clientPath, $stringParams);
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
        $handle = proc_open($name .' '. $stringParams, [STDIN, STDOUT, STDERR], $pipes, null, []);

        $output = null;
        if (is_resource($handle)) {
            $output = proc_close($handle);
        }

        return $output;
    }

}