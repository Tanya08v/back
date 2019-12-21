<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:27
 */

namespace controllers;


use views\renders\Render;

abstract class MainController
{
    /**
     * @var Render
     */
    private $render;

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var array
     */
    protected $renderData = [];

    /**
     * @var string
     */
    protected $templatePath = 'main_page.twig';

    /**
     * @param array $params
     * @author Borys Plotka ( @3plo )
     */
    abstract public function handleAction (array $params);

    /**
     * MainController constructor.
     * @param Render $render
     * @author Borys Plotka ( @3plo )
     */
    final public function __construct (Render $render)
    {
        $this->render = $render;
    }

    /**
     * @return Render
     * @author Borys Plotka ( @3plo )
     */
    final protected function getRender() : Render
    {
        return $this->render;
    }

    /**
     * @param string $templatePath
     * @author Borys Plotka ( @3plo )
     */
    final public function setTemplatePath(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    final public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * @author Borys Plotka ( @3plo )
     */
    protected function setTemplateTitle()
    {
        $this->addToRenderParams('title', $this->title);
    }

    /**
     * @param array $params
     * @return bool
     * @author Borys Plotka ( @3plo )
     */
    protected function setParams(array $params) : bool
    {
        $result = true;
        foreach ($params as $title => $value){
            if (!$this->addToRenderParams($title, $value)) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * @param string $title
     * @param $value
     * @return bool
     * @author Borys Plotka ( @3plo )
     */
    final protected function addToRenderParams(string $title, $value) : bool
    {
        $result = false;
        if (!isset($this->renderData[$title])) {
            // TODO check $value type (not Object) and throw Exception
            $this->renderData[$title] = $value;
            $result = true;
        }
        return $result;
    }

    /**
     * @param string $path
     * @param array $getParameters
     * @author Borys Plotka ( @3plo )
     */
    final protected function redirect(string $path, array $getParameters = [])
    {
        if ($getParameters) {
            $path .= '?';
            foreach ($getParameters as $title => $value) {
                $path .= $title . '=' . $value . '&';
            }
            $path = substr($path, 0, -1);
        }
        if (strpos($path, '/') !== 0) {
            $path = '/' . $path;
        }
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: $path");
        exit();
    }

    /**
     * @author Borys Plotka ( @3plo )
     */
    final protected function checkLogin()
    {
        if (!$_SESSION['user_id']) {
            $this->redirect('login/logout');
        }
    }
}