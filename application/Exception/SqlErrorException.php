<?php
/**
 * Invalid type exception
 */

namespace OpenCRM\Exception;


use Throwable;

class SqlErrorException extends CommonException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}