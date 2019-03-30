<?php
/**
 * Common exception class
 */

namespace OpenCRM\Exception;


use Throwable;

class CommonException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}