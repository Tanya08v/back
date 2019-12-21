<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:27
 */

namespace controllers\error_controllers;


use controllers\MainController;

class GeneralErrorController extends MainController
{
    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @var string
     */
    protected $templatePath = 'error_templates/base_error.twig';

    /**
     * @var string
     */
    protected $title = 'Error';

    /**
     * @param array|null $params
     * @return bool
     * @author Borys Plotka ( @3plo )
     */
    protected function hasDedugAccess(array $params = null) : bool
    {
        return isset($params['access']);
    }

    /**
     * @param array $params
     * @author Borys Plotka ( @3plo )
     */
    public function handleAction(array $params)
    {
        $this->setTemplateTitle();
        $this->setParams([
            'path' => $_SERVER['REDIRECT_URL'],
            'error_data_access' => $this->hasDedugAccess($params['request']),
            'session' => $params['session'],
            'request' => $params['request'],
            'stackTrace' => $this->exception
        ]);
        $this->getRender()->rend($this->getTemplatePath(), $this->renderData);
    }

    /**
     * @param \Exception $exception
     * @author Borys Plotka ( @3plo )
     */
    public function setError(\Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return \Exception
     * @author Borys Plotka ( @3plo )
     */
    public function getError() : \Exception
    {
        return $this->exception;
    }
}