<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:19
 */

namespace configs;


interface ConfigInterface
{
    /**
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getConfig() : array;
}