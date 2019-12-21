<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:12
 */

namespace application;


class Autoloader
{
    /**
     * @param $class
     * @author Borys Plotka ( @3plo )
     */
    public function loadClass($class)
    {
        $rootDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $class = str_replace('/', DIRECTORY_SEPARATOR, $class);
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $path = $rootDir . $class . '.php';
        if (!class_exists($class))
        {
            if (file_exists($path))
            {
                require_once($path);
            }
        }
    }
}
spl_autoload_register([new Autoloader(), 'loadClass']);