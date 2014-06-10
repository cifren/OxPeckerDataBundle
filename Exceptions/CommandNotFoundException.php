<?php

namespace Earls\OxPeckerDataBundle\Exceptions;


class CommandNotFoundException extends \Exception
{
    public function __construct($message, $code = 610, Exception $previous = null) {
        
        parent::__construct($message, $code, $previous);
    }
}
