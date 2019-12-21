<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:13
 */

namespace application;

use application\strategies\application_strategies\ApplicationInitStrategyInterface;
use controllers\Router;
use model\Model;

class Application
{
    /**
     * @var ApplicationInitStrategyInterface
     */
    private $applicationInitStrategy;

    /**
     * @var Router
     */
    private $router;

    /**
     * Application constructor.
     * @param ApplicationInitStrategyInterface $applicationInitStrategy
     * @param Router $router
     * @author Borys Plotka ( @3plo )
     */
    public function __construct(
        ApplicationInitStrategyInterface $applicationInitStrategy,
        Router $router
    )
    {
        $this->applicationInitStrategy = $applicationInitStrategy;
        $this->router = $router;
    }

    /**
     * @author Borys Plotka ( @3plo )
     */
    public static function init()
    {
        define('DATETIME', date('Y-m-d H:i:s'));
        define('DATE', date('Y-m-d'));
        define('TIME', date('H:i:s'));
        define('TIMESTAMP', time());
        Model::init();
    }

    /**
     * @param string $path
     * @param array $request
     * @param array $session
     * @author Borys Plotka ( @3plo )
     */
    public function run(string $path, array $request, array $session)
    {
        $this->applicationInitStrategy->applicationInit();
        $this->router->routRequest($path, $request, $session);
    }
}