<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:11
 */

session_start();
require_once __DIR__ . '/application/Autoloader.php';
require_once __DIR__ . '/application/Application.php';
use application\Application;
$application = new Application(
    new \application\strategies\application_strategies\DefaultApplicationInitStrategy(),
    new \controllers\Router(
        new \controllers\strategies\HTTPPathScannerStrategey(),
        new \controllers\comands\GeneralErrorHandleComand()
    )
);
$application->init();
$application->run(
    $_SERVER['REQUEST_URI'],
    isset($_REQUEST) ? $_REQUEST : array(),
    isset($_SESSION) ? $_SESSION : array()
);