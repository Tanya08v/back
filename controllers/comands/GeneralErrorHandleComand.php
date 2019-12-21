<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:25
 */

namespace controllers\comands;

use controllers\error_controllers\GeneralErrorController;
use controllers\exceptions\ExceptionCodeEnum;
use views\renders\Render;

class GeneralErrorHandleComand
{
    /**
     * @param \Exception $exception
     * @param Render $render
     * @return GeneralErrorController
     * @author Borys Plotka ( @3plo )
     */
    public function getErrorHandlerController(\Exception $exception, Render $render)
    {
        switch ($exception->getCode()) {
            case (
            in_array(
                $exception->getCode(),
                [
                    ExceptionCodeEnum::ROUTING_EXCEPTION_CODE,
                    ExceptionCodeEnum::INCORRECT_CONTROLLER_PATH
                ]) ?
                $exception->getCode() :
                0
            ) :
                $errorController = new GeneralErrorController($render);
                break;
            default:
                $errorController = new GeneralErrorController($render);
        }
        return $errorController;
    }
}