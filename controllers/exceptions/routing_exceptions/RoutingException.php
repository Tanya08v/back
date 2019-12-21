<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:32
 */

namespace controllers\exceptions\routing_exceptions;


use controllers\exceptions\ExceptionCodeEnum;

class RoutingException extends \Exception
{
    /**
     * RoutingException constructor.
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     * @author Borys Plotka ( @3plo )
     */
    public function __construct(
        $message = 'Не возможно обработать запрос',
        $code = ExceptionCodeEnum::ROUTING_EXCEPTION_CODE,
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