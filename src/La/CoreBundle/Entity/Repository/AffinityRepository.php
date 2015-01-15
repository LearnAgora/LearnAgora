<?php

namespace La\CoreBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class AffinityRepository extends EntityRepository
{

    public function loadAffinitiesForUsers($users)
    {
        $rsm = new ResultSetMapping();
// build rsm here
        //$rsm->addEntityResult('Affinity', 'a');
        //$rsm->addFieldResult('a', 'agora_id', 'agora_id');
        //$rsm->addFieldResult('a', 'user_id', 'user_id');
        //$rsm->addFieldResult('a', 'value', 'value');
        $rsm->addScalarResult('agora_id', 'agora_id');
        $rsm->addScalarResult('agora_name', 'agora_name');
        $rsm->addScalarResult('persona_affinity', 'persona_affinity');
        $rsm->addScalarResult('user_affinity', 'user_affinity');

        $query = $this->getEntityManager()->createNativeQuery('SELECT a.agora_id, c.name as agora_name, a.value as persona_affinity, b.value as user_affinity FROM (SELECT * FROM `Affinity` WHERE user_id=13 and value>0) a left outer join (SELECT * FROM `Affinity` WHERE user_id=1 and value>0) b on a.agora_id=b.agora_id Left Join LearningEntity c on a.agora_id=c.id', $rsm);
        //$query->setParameter(1, '1');

        $users = $query->getResult();
        return $users;
    }

}
