<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\User;

class TechneRepository extends EntityRepository
{
    public function findProbabilitiesForUser(User $user) {
        $query = $this->createQueryBuilder('a')
            ->addSelect('u')
            ->leftJoin('a.userProbabilities', 'u', 'WITH', 'u.user = :userId')
            ->setParameters(array(
                'userId'           => $user->getId(),
            ))
            ->getQuery();

        return $query->getResult();
    }

    public function search($query) {
        $qb = $this->createQueryBuilder('t');
        $query = $this->createQueryBuilder('t')
            //->leftJoin('t.content', 'c')
            ->leftJoin('La\CoreBundle\Entity\HtmlContent', 'c', 'WITH', $qb->expr()->eq('t.content', 'c.id') )
            ->where($qb->expr()->like('t.name',':query'))
            ->orwhere($qb->expr()->like('c.content',':query'))
            ->getQuery()
            ->setParameters(array(
                'query'           => '%'.$query.'%'
            ));

        return $query->getResult();
    }

}
