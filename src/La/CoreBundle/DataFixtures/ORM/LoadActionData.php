<?php

namespace La\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Agora;

class LoadActionData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    function getDependencies()
    {
        return array(
            'La\CoreBundle\DataFixtures\ORM\LoadUrlContentData',
            'La\CoreBundle\DataFixtures\ORM\LoadUserData',
        );
    }

    /**
     * {@inheritdoc}
     */
    function load(ObjectManager $manager)
    {
        $action1 = $this->createAction('url-content-1', 'user-anna', "Gibson's importance for perception research");
        $action2 = $this->createAction('url-content-2', 'user-anna', 'David Marr on visual perception');
        $action3 = $this->createAction('url-content-3', 'user-anna', 'Gestalt view of perception');

        $this->addReference('action-1', $action1);
        $this->addReference('action-2', $action2);
        $this->addReference('action-3', $action3);

        $manager->persist($action1);
        $manager->persist($action2);
        $manager->persist($action3);

        $manager->flush();
    }

    private function createAction($contentReference, $ownerReference, $name)
    {
        $action = new Action();
        $action->setContent($this->getReference($contentReference));
        $action->setOwner($this->getReference($ownerReference));
        $action->setName($name);

        return $action;
    }
}
