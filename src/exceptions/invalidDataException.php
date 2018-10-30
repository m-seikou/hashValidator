<?php
/**
 * Created by PhpStorm.
 * User: 745
 * Date: 2016/11/09
 * Time: 9:48
 */

namespace mihoshi\hashValidator\exceptions;


use Exception;

class invalidDataException extends \UnexpectedValueException
{
    protected $clientMessage = '';

    public function __construct($message = '', $code = 0, Exception $previous = null, $clientMessage = '')
    {
        parent::__construct($message, $code, $previous);
        $this->clientMessage = $clientMessage;
    }

    public function getClientMessage(): string
    {
        return $this->clientMessage;
    }
}
