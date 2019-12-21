<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:43
 */

namespace views\renders;


use application\registers\RenderConfigRegister;
use configs\ConfigInterface;
use model\mapper\user\UserTypeMapper;

class TwigViewRender implements Render
{
    /**
     * @var Render
     */
    private static $instance;
    /**
     * @var
     */
    private $twigInstance;

    /**
     * TwigViewRender constructor.
     * @param \Twig_Environment $twig
     * @author Borys Plotka ( @3plo )
     */
    private function __construct(\Twig_Environment $twig)
    {
        $this->twigInstance = $twig;
        $this->twigInstance->addGlobal('session', $_SESSION);
    }

    /**
     * @param string $templateName
     * @param array $data
     * @author Borys Plotka ( @3plo )
     */
    public function rend(string $templateName, array $data = [])
    {
        $templateName = str_replace('/', DIRECTORY_SEPARATOR, $templateName);
        $templateName = str_replace('\\', DIRECTORY_SEPARATOR, $templateName);
        $template = $this->twigInstance->load($templateName);
        echo $template->render($data);
    }

    /**
     * @param ConfigInterface $config
     * @author Borys Plotka ( @3plo )
     */
    public static function init(ConfigInterface $config)
    {
        if (!isset(TwigViewRender::$instance)) {
            require_once $config->getConfig()['autoloader_path'];
            $loader = new \Twig_Loader_Filesystem($config->getConfig()['template_dir']);
            $twig = new \Twig_Environment($loader, array(
                'cache' => !empty($config->getConfig()['cache_dir']) ?
                    $config->getConfig()['cache_dir'] :
                    false
            ));
            TwigViewRender::$instance = new TwigViewRender($twig);
        }
    }

    /**
     * @return Render
     * @author Borys Plotka ( @3plo )
     */
    public static function getInstance() : Render
    {
        if (!isset(TwigViewRender::$instance)) {
            TwigViewRender::init(RenderConfigRegister::getInstance()->getConfig());
        }
        return TwigViewRender::$instance;
    }
}