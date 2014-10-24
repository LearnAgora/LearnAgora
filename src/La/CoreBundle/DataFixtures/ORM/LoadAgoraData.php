<?php

namespace La\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\Agora;

class LoadAgoraData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return array(
            'La\CoreBundle\DataFixtures\ORM\LoadHtmlContentData',
            'La\CoreBundle\DataFixtures\ORM\LoadUserData',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $agora1 = $this->createAgora('html-content-1', 'user-anna', 'Teaching');
        $agora2 = $this->createAgora('html-content-2', 'user-anna', 'Designing');
        $agora3 = $this->createAgora('html-content-3', 'user-anna', 'SW start-up');

        $this->addReference('agora-1', $agora1);
        $this->addReference('agora-2', $agora2);
        $this->addReference('agora-3', $agora3);

        $manager->persist($agora1);
        $manager->persist($agora2);
        $manager->persist($agora3);

        $manager->flush();
    }

    private function createAgora($contentReference, $ownerReference, $name)
    {
        $agora = new Agora();
        $agora->setContent($this->getReference($contentReference));
        $agora->setOwner($this->getReference($ownerReference));
        $agora->setName($name);

        return $agora;
    }
}
