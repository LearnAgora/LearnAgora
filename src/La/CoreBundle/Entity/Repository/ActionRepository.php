<?php

namespace La\CoreBundle\Entity\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\User;

class ActionRepository extends EntityRepository
{

    public function findOneOrNullUnvisitedActions($user)
    {
        /* @var $user User */
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');

        $sql = "SELECT le.id FROM LearningEntity le left outer join (SELECT o.learning_entity_id as leid, t.user_id as uid FROM Outcome o, Trace t WHERE o.id=t.outcome_id AND t.user_id=? group by o.learning_entity_id) a on le.id=a.leid  WHERE le.discr='action' and a.uid IS NULL ORDER BY RAND() LIMIT 1";
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $user->getId());
        $ActionIdArray = $query->getOneOrNullResult();

        return $this->loadAction($ActionIdArray['id']);
    }

    public function findOneOrNullUnvisitedActionsForReferenceUser($user, $ReferenceUser)
    {
        /* @var $user User */
        /* @var $referenceUser User */

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');

        $sql = "SELECT a.id FROM (SELECT le.id as id FROM LearningEntity le, Uplink u, Affinity af WHERE le.discr='action' and le.id=u.child_id and u.parent_id=af.agora_id and af.user_id=?) a left outer join (SELECT o.learning_entity_id as leid, t.user_id as uid FROM Outcome o, Trace t WHERE o.id=t.outcome_id AND t.user_id=? group by o.learning_entity_id) b on a.id=b.leid  WHERE b.uid IS NULL ORDER BY RAND() LIMIT 1";
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $ReferenceUser->getId());
        $query->setParameter(2, $user->getId());
        $ActionIdArray = $query->getOneOrNullResult();

        return $this->loadAction($ActionIdArray['id']);
    }

    public function findOneOrNullUnvisitedActionsForAgora($user, $agora)
    {
        /* @var $user User */
        /* @var $agora Agora */

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');

        $sql = "SELECT a.id FROM (SELECT le.id as id FROM LearningEntity le, Uplink u WHERE le.discr='action' and le.id=u.child_id and u.parent_id=?) a left outer join (SELECT o.learning_entity_id as leid, t.user_id as uid FROM Outcome o, Trace t WHERE o.id=t.outcome_id AND t.user_id=? group by o.learning_entity_id) b on a.id=b.leid  WHERE b.uid IS NULL ORDER BY RAND() LIMIT 1";
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $agora->getId());
        $query->setParameter(2, $user->getId());
        $ActionIdArray = $query->getOneOrNullResult();

        return $this->loadAction($ActionIdArray['id']);
    }

    private function loadAction($actionId) {
        /* @var $action Action */
        $action = null;
        if (isset($actionId)) {
            $action = $this->find($actionId);
        }
        return $action;
    }

}
