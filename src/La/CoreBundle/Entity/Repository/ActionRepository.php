<?php

namespace La\CoreBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\User;

class ActionRepository extends EntityRepository
{
    public function findUnvisitedActions(User $user)
    {
    }
}
