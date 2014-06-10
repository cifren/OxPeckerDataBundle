<?php

namespace Earls\OxPeckerDataBundle\Exceptions;

class HandlerNotImplementedException extends \Exception
{
    public function __construct($message, $code = 615, Exception $previous = null) {
        
        parent::__construct($message, $code, $previous);
    }
}
