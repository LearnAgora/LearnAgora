<?php

namespace La\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\HtmlContent;

class LoadHtmlContentData extends AbstractFixture implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    function load(ObjectManager $manager)
    {
        $content1 = $this->createContent('This place is for those who are skilled in explaining stuff to others.');
        $content2 = $this->createContent('This place is for those who love to make designs pleasing on the eye and easy to use.');
        $content3 = $this->createContent('This place is for those who are considering starting up their own SW business.');

        $this->addReference('html-content-1', $content1);
        $this->addReference('html-content-2', $content2);
        $this->addReference('html-content-3', $content3);

        $manager->persist($content1);
        $manager->persist($content2);
        $manager->persist($content3);

        $manager->flush();
    }

    private function createContent($content)
    {
        $htmlContent = new HtmlContent();
        $htmlContent->setContent($content);

        return $htmlContent;
    }
}
