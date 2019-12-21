<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:40
 */

namespace controllers\strategies;


interface PathScannerStrategeyInterface
{
    /**
     * @param string $path
     * @return mixed
     * @author Borys Plotka ( @3plo )
     */
    public function parsePath(string $path);

    /**
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    public function getControllerDirectory() : string;

    /**
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    public function getControllerName() : string;
}