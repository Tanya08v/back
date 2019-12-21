<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:17
 */

namespace application\registers;

use application\registers\register_enums\ConfigTypeEnum;
use configs\ConfigInterface;
use configs\RenderConfig;

class RenderConfigRegister implements ConfigRegisterInterface
{
    /**
     * @var RenderConfigRegister
     */
    private static $instance;

    /**
     * @var ConfigRegisterInterface
     */
    private $config;

    /**
     * RenderConfigRegister constructor.
     * @param ConfigInterface $config
     * @author Borys Plotka ( @3plo )
     */
    private function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public static function init(string $type = ConfigTypeEnum::DEFAULT)
    {
        RenderConfigRegister::$instance = new RenderConfigRegister(RenderConfigRegister::getRenderConfig($type));
    }

    public static function getInstance(): ConfigRegisterInterface
    {
        if (!isset(RenderConfigRegister::$instance)) {
            RenderConfigRegister::init();
        }
        return RenderConfigRegister::$instance;
    }

    public function getConfig() : ConfigInterface
    {
        return $this->config;
    }

    /**
     * @param string $type
     * @return ConfigInterface
     * @author Borys Plotka ( @3plo )
     */
    private static function getRenderConfig(string $type) : ConfigInterface
    {
        switch ($type) {
            default :
                $result = new RenderConfig();
        }
        return $result;
    }
}