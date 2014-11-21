<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\User;

class ActionRepository extends EntityRepository
{

    public function findUnvisited2Actions()
    {
        //this is how it should be but i can't make the query work
        $query = $this->createQueryBuilder('a')
            ->innerJoin('a.outcomes', 'o')
            ->leftJoin('o.traces', 't', 'WITH', 't.user = :userId')
            ->andWhere('t.id IS NULL')
            //->andWhere('t.user = :userId')
            ->getQuery()
            ->setParameters(array(
//                'userId' => $user->getId(),
                'userId' => 1,
            ));

        //return $query->getSQL();
        return $query->getResult();
    }


    public function findOneOrNullPostponedActions_tobe(User $user) {
        $query = $this->createQueryBuilder('a')
            ->innerJoin('a.outcomes', 'o')
            ->leftJoin('o.traces', 't', 'WITH', 't.user = :userId')
            ->andWhere('o.caption = :caption')
            ->andWhere('t.user = :userId')
            ->getQuery()
            ->setParameters(array(
//                'userId' => $user->getId(),
                'userId' => 1,
                'subject' => 'button',
                'caption' => 'LATER',
            ));

        die(var_dump($query->getResult()));
        return $query->getResult();
    }


}
