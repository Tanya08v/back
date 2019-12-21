<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:19
 */

namespace configs;


class DBConfig implements ConfigInterface
{
    const DRIVER = 'mysql';
    const HOST = '127.0.0.1';
    const NAME = 'ha';
    const USER = 'root';
    const PASSWORD = '';
    const PORT = '3306';

    final public function getConfig() : array
    {
        return array(
            'driver' => DBConfig::DRIVER,
            'host' => DBConfig::HOST,
            'name' => DBConfig::NAME,
            'user' => DBConfig::USER,
            'password' => DBConfig::PASSWORD,
            'port' => DBConfig::PORT
        );
    }
}