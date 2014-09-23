<?php

namespace La\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\Action;

class LoadActionData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    function getDependencies()
    {
        return array(
            'La\CoreBundle\DataFixtures\ORM\LoadUrlContentData',
            'La\CoreBundle\DataFixtures\ORM\LoadSimpleQuestionData',
            'La\CoreBundle\DataFixtures\ORM\LoadMultipleChoiceQuestionData',
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
        $action4 = $this->createAction('simple-question-1', 'user-anna', 'Planning processes');
        $action5 = $this->createAction('simple-question-2', 'user-anna', 'Immersive');
        $action6 = $this->createAction('simple-question-3', 'user-anna', 'Change Management');
        $action7 = $this->createAction('multiple-choice-question-1', 'user-anna', 'Pop quiz on visual perception');
        $action8 = $this->createAction('multiple-choice-question-2', 'user-anna', 'Design examples');
        $action9 = $this->createAction('multiple-choice-question-3', 'user-anna', 'Chelsea MVP');

        $this->addReference('action-1', $action1);
        $this->addReference('action-2', $action2);
        $this->addReference('action-3', $action3);
        $this->addReference('action-4', $action4);
        $this->addReference('action-5', $action5);
        $this->addReference('action-6', $action6);
        $this->addReference('action-7', $action7);
        $this->addReference('action-8', $action8);
        $this->addReference('action-9', $action9);

        $manager->persist($action1);
        $manager->persist($action2);
        $manager->persist($action3);
        $manager->persist($action4);
        $manager->persist($action5);
        $manager->persist($action6);
        $manager->persist($action7);
        $manager->persist($action8);
        $manager->persist($action9);

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
