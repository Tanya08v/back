<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:15
 */

namespace application\registers;

use application\registers\register_enums\ConfigTypeEnum;
use configs\ConfigInterface;

interface ConfigRegisterInterface
{
    /**
     * @param string $type
     * @return mixed
     * @author Borys Plotka ( @3plo )
     */
    public static function init(string $type = ConfigTypeEnum::DEFAULT);

    /**
     * @return ConfigRegisterInterface
     * @author Borys Plotka ( @3plo )
     */
    public static function getInstance(): ConfigRegisterInterface;

    /**
     * @return ConfigInterface
     * @author Borys Plotka ( @3plo )
     */
    public function getConfig(): ConfigInterface;
}