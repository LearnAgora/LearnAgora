<?php

namespace La\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\UrlContent;

class LoadUrlContentData extends AbstractFixture implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    function load(ObjectManager $manager)
    {
        $urlContent1 = $this->createUrlContent("JJ Gibson is an important name in visual perception.\n\nHe moved from an analysis of what is seen by static observers to an 'ecological' analysis.\n\nPlease explore through this link what this means.", 'http://en.wikipedia.org/wiki/James_J._Gibson');
        $urlContent2 = $this->createUrlContent("David Marr marks an important turning point in the understanding of visual perception specifically, and in cognitive sciences generally.\n\nHis model applied a computational approach to visual perception. Check out the below article for a brief overview.", 'http://en.wikipedia.org/wiki/David_Marr_(neuroscientist)#Levels_of_analysis');
        $urlContent3 = $this->createUrlContent("An early (but still modern) view of perception is called 'Gestalt' (say gesjtalt ;-). It's another clear illustration of how a theory of perception tends to make broader claims about the way the mind works.\n\nYou will know Gestalt from a commonplace such as 'The whole is more than the part of the sums' and from picture paradoxes such as duck/rabbit. Browse the article for more brief background.", 'http://en.wikipedia.org/wiki/Gestalt_psychology');

        $this->addReference('url-content-1', $urlContent1);
        $this->addReference('url-content-2', $urlContent2);
        $this->addReference('url-content-3', $urlContent3);

        $manager->persist($urlContent1);
        $manager->persist($urlContent2);
        $manager->persist($urlContent3);

        $manager->flush();
    }

    private function createUrlContent($instruction, $url)
    {
        $urlContent = new UrlContent();
        $urlContent->setInstruction($instruction);
        $urlContent->setUrl($url);

        return $urlContent;
    }
}
