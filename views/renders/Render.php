<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:42
 */

namespace views\renders;


use configs\ConfigInterface;

interface Render
{
    /**
     * @param string $templateName
     * @param array $data
     * @return mixed
     * @author Borys Plotka ( @3plo )
     */
    public function rend(string $templateName, array $data = []);

    /**
     * @param ConfigInterface $config
     * @return mixed
     * @author Borys Plotka ( @3plo )
     */
    public static function init(ConfigInterface $config);

    /**
     * @return Render
     * @author Borys Plotka ( @3plo )
     */
    public static function getInstance() : Render;
}