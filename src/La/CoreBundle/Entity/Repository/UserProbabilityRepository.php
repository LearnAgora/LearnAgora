<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;

class UserProbabilityRepository extends EntityRepository
{
    public function findFor($user, $learningEntity) {
        return $this->findBy(array('User'=>$user,'LearningEntity'=>$learningEntity));
    }
}
