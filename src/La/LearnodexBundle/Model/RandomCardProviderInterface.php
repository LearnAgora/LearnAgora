<?php

namespace La\LearnodexBundle\Model;

use La\LearnodexBundle\Model\Exception\CardNotFoundException;

interface RandomCardProviderInterface
{
    /**
     * Fetch a random card.
     *
     * @throws CardNotFoundException when no card is found
     *
     * @return Card
     */
    public function getCard();
}
