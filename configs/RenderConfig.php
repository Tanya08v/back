<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:20
 */

namespace configs;


class RenderConfig implements ConfigInterface
{
    const TEMPLATE_DIR = __DIR__ . '/../views/templates';
    const AUTOLOADER_PATH = __DIR__ . '/../vendor/autoload.php';
    const CACHE_DIR = '';

    final public function getConfig() : array
    {
        return array(
            'template_dir' => RenderConfig::TEMPLATE_DIR,
            'autoloader_path' => RenderConfig::AUTOLOADER_PATH,
            'cache_dir' => RenderConfig::CACHE_DIR
        );
    }
}
