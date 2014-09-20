<?php

namespace La\LearnodexBundle\Model\Exception;

use Exception;

class CardNotFoundException extends Exception
{
    public function __construct($message = 'Could not find requested card.', Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
