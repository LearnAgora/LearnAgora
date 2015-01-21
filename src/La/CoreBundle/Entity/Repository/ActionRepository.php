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

    public function findOneOrNullUnvisitedActions(User $user)
    {
        /* @var $user User */
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');

//        $sql = "SELECT le.id FROM LearningEntity le left outer join (SELECT o.learning_entity_id as leid, t.user_id as uid FROM Outcome o, Trace t WHERE o.id=t.outcome_id AND t.user_id=? group by o.learning_entity_id) a on le.id=a.leid  WHERE le.discr='action' and a.uid IS NULL ORDER BY RAND() LIMIT 1";
        $sql = "SELECT res.id from (select le.id as id, (select count(*)  from Outcome o, Trace t where t.user_id=? and t.outcome_id=o.id and o.learning_entity_id=le.id) as cnt from LearningEntity le where le.discr='action') res where cnt=0 order by rand() limit 1";
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $user->getId());
        $ActionIdArray = $query->getOneOrNullResult();

        return $this->loadAction($ActionIdArray['id']);
    }
    public function findOneOrNullPostponedActions(User $user) {
        /* @var $user User */
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');

        $sql = "select res.id from (select le.id as id, (select o.caption from Outcome o, Trace t where t.user_id=? and t.outcome_id=o.id and o.learning_entity_id=le.id order by t.created_time desc limit 1) as caption from LearningEntity le where le.discr='action') res where res.caption='LATER' order by rand() limit 1";
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $user->getId());
        $ActionIdArray = $query->getOneOrNullResult();

        return $this->loadAction($ActionIdArray['id']);
    }

    public function findOneOrNullUnvisitedActionsForReferenceUser(User $user, User $referenceUser)
    {
        /* @var $user User */
        /* @var $referenceUser User */

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');

        //$sql = "SELECT a.id FROM (SELECT le.id as id FROM LearningEntity le, Uplink u, Affinity af WHERE le.discr='action' and le.id=u.child_id and u.parent_id=af.agora_id and af.user_id=?) a left outer join (SELECT o.learning_entity_id as leid, t.user_id as uid FROM Outcome o, Trace t WHERE o.id=t.outcome_id AND t.user_id=? group by o.learning_entity_id) b on a.id=b.leid  WHERE b.uid IS NULL ORDER BY RAND() LIMIT 1";
        $sql = "SELECT res.id from (select le.id as id, (select count(*)  from Outcome o, Trace t where t.user_id=? and t.outcome_id=o.id and o.learning_entity_id=le.id) as cnt from LearningEntity le, Uplink u, Affinity af where le.discr='action' and u.child_id=le.id and u.parent_id=af.agora_id and af.user_id=? ) res where cnt=0 order by rand() limit 1";
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $user->getId());
        $query->setParameter(2, $referenceUser->getId());
        $ActionIdArray = $query->getOneOrNullResult();

        return $this->loadAction($ActionIdArray['id']);
    }
    public function findOneOrNullPostponedActionsForReferenceUser(User $user,User $referenceUser) {
        /* @var $user User */
        /* @var $referenceUser User */

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');

        $sql = "select * from (select le.id as id, (select o.caption from Outcome o, Trace t where t.user_id=? and t.outcome_id=o.id and o.learning_entity_id=le.id order by t.created_time desc limit 1) as caption from LearningEntity le, Uplink u, Affinity af where le.discr='action' and u.child_id=le.id and u.parent_id=af.agora_id and af.user_id=?) res where res.caption='LATER' order by rand() limit 1";
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $user->getId());
        $query->setParameter(2, $referenceUser->getId());
        $ActionIdArray = $query->getOneOrNullResult();

        return $this->loadAction($ActionIdArray['id']);
    }

    public function findOneOrNullUnvisitedActionsForAgora(User $user, Agora $agora)
    {
        /* @var $user User */
        /* @var $agora Agora */

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');

//        $sql = "SELECT a.id FROM (SELECT le.id as id FROM LearningEntity le, Uplink u WHERE le.discr='action' and le.id=u.child_id and u.parent_id=?) a left outer join (SELECT o.learning_entity_id as leid, t.user_id as uid FROM Outcome o, Trace t WHERE o.id=t.outcome_id AND t.user_id=? group by o.learning_entity_id) b on a.id=b.leid  WHERE b.uid IS NULL ORDER BY RAND() LIMIT 1";
        $sql = "SELECT * from (select le.id as id, (select count(*)  from Outcome o, Trace t where t.user_id=? and t.outcome_id=o.id and o.learning_entity_id=le.id) as cnt from LearningEntity le, Uplink u where le.discr='action' and u.child_id=le.id and u.parent_id=? ) res where cnt=0 order by rand() limit 1";
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $user->getId());
        $query->setParameter(2, $agora->getId());
        $ActionIdArray = $query->getOneOrNullResult();

        return $this->loadAction($ActionIdArray['id']);
    }
    public function findOneOrNullPostponedActionsForAgora(User $user, Agora $agora){
        /* @var $user User */
        /* @var $agora Agora */

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');

//        $sql = "SELECT a.id FROM (SELECT le.id as id FROM LearningEntity le, Uplink u WHERE le.discr='action' and le.id=u.child_id and u.parent_id=?) a left outer join (SELECT o.learning_entity_id as leid, t.user_id as uid FROM Outcome o, Trace t WHERE o.id=t.outcome_id AND t.user_id=? group by o.learning_entity_id) b on a.id=b.leid  WHERE b.uid IS NULL ORDER BY RAND() LIMIT 1";
        $sql = "select * from (select le.id as id, (select o.caption from Outcome o, Trace t where t.user_id=? and t.outcome_id=o.id and o.learning_entity_id=le.id order by t.created_time desc limit 1) as caption from LearningEntity le, Uplink u where le.discr='action' and u.child_id=le.id and u.parent_id=?) res where res.caption='LATER' order by rand() limit 1";
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $user->getId());
        $query->setParameter(2, $agora->getId());
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
