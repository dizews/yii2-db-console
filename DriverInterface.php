<?php

namespace dizews\dbConsole;


interface DriverInterface
{
    public function combineParams($params);
    public function getClientCommand();
    public function getLoadCommand($path);
    public function getDumpCommand($path);
    public function getRestoreCommand($path);
    public function getPasswordParamName();
    public function getEnv();

}