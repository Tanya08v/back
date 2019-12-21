<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:31
 */

namespace controllers\exceptions\routing_exceptions;


use controllers\exceptions\ExceptionCodeEnum;

class IncorrectControllerPathException extends RoutingException
{
    /**
     * IncorrectControllerPathException constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     * @author Borys Plotka ( @3plo )
     */
    public function __construct(
        $message = 'Не удается создать контроллер по заданому пути',
        $code = ExceptionCodeEnum::INCORRECT_CONTROLLER_PATH,
        \Exception $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    public function __toString()
    {
        return __CLASS__ . ': [{$this->code}]: {$this->message}';
    }
}