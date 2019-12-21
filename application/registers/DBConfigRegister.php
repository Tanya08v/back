<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:16
 */

namespace application\registers;

use application\registers\register_enums\ConfigTypeEnum;
use configs\ConfigInterface;
use configs\DBConfig;

class DBConfigRegister implements ConfigRegisterInterface
{
    /**
     * @var DBConfigRegister
     */
    private static $instance;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * DBConfigRegister constructor.
     * @param ConfigInterface $config
     * @author Borys Plotka ( @3plo )
     */
    private function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $type
     * @author Borys Plotka ( @3plo )
     */
    public static function init(string $type = ConfigTypeEnum::DEFAULT)
    {
        if (!isset(DBConfigRegister::$instance)) {
            DBConfigRegister::$instance = new DBConfigRegister(DBConfigRegister::getDBConfig($type));
        }
    }

    /**
     * @return ConfigRegisterInterface
     * @author Borys Plotka ( @3plo )
     */
    public static function getInstance() : ConfigRegisterInterface
    {
        if (!isset(DBConfigRegister::$instance)){
            DBConfigRegister::init();
        }
        return DBConfigRegister::$instance;
    }

    /**
     * @return ConfigInterface
     * @author Borys Plotka ( @3plo )
     */
    public function getConfig() : ConfigInterface
    {
        return $this->config;
    }

    /**
     * @param string $type
     * @return ConfigInterface
     * @author Borys Plotka ( @3plo )
     */
    private static function getDBConfig(string $type) : ConfigInterface
    {
        switch ($type) {
            default :
                $result = new DBConfig();
        }
        return $result;
    }
}