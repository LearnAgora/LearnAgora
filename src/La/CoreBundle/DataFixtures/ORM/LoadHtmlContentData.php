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
        $htmlContent1 = $this->createHtmlContent('This place is for those who are skilled in explaining stuff to others.');
        $htmlContent2 = $this->createHtmlContent('This place is for those who love to make designs pleasing on the eye and easy to use.');
        $htmlContent3 = $this->createHtmlContent('This place is for those who are considering starting up their own SW business.');

        $this->addReference('html-content-1', $htmlContent1);
        $this->addReference('html-content-2', $htmlContent2);
        $this->addReference('html-content-3', $htmlContent3);

        $manager->persist($htmlContent1);
        $manager->persist($htmlContent2);
        $manager->persist($htmlContent3);

        $manager->flush();
    }

    private function createHtmlContent($content)
    {
        $htmlContent = new HtmlContent();
        $htmlContent->setContent($content);

        return $htmlContent;
    }
}
