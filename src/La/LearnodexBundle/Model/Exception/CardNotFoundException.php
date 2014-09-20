<?php

namespace La\LearnodexBundle\Model\Exception;

use Exception;

class CardNotFoundException extends Exception
{
    public function __construct($message = 'Could not find requested card.', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
