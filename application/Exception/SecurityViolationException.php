<?php
/**
 * Common exception class
 */

namespace OpenCRM\Exception;


use Throwable;

class SecurityViolationException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if (!is_string($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        }
        parent::__construct($message, $code, $previous);
    }
}