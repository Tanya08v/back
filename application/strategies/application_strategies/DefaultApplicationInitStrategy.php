<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:14
 */

namespace application\strategies\application_strategies;

use application\registers\DBConfigRegister;
use application\registers\register_enums\ConfigTypeEnum;

class DefaultApplicationInitStrategy implements ApplicationInitStrategyInterface
{
    public function applicationInit()
    {
        DBConfigRegister::init(ConfigTypeEnum::DEFAULT);
    }
}