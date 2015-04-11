<?php

namespace La\CoreBundle\Model\Exception;

use Exception;

class ObjectErrorException extends Exception
{
    public function __construct($message = 'object has some error.', Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
