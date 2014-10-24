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
    public function load(ObjectManager $manager)
    {
        $content1 = $this->createContent("JJ Gibson is an important name in visual perception.\n\nHe moved from an analysis of what is seen by static observers to an 'ecological' analysis.\n\nPlease explore through this link what this means.", 'http://en.wikipedia.org/wiki/James_J._Gibson');
        $content2 = $this->createContent("David Marr marks an important turning point in the understanding of visual perception specifically, and in cognitive sciences generally.\n\nHis model applied a computational approach to visual perception. Check out the below article for a brief overview.", 'http://en.wikipedia.org/wiki/David_Marr_(neuroscientist)#Levels_of_analysis');
        $content3 = $this->createContent("An early (but still modern) view of perception is called 'Gestalt' (say gesjtalt ;-). It's another clear illustration of how a theory of perception tends to make broader claims about the way the mind works.\n\nYou will know Gestalt from a commonplace such as 'The whole is more than the part of the sums' and from picture paradoxes such as duck/rabbit. Browse the article for more brief background.", 'http://en.wikipedia.org/wiki/Gestalt_psychology');

        $this->addReference('url-content-1', $content1);
        $this->addReference('url-content-2', $content2);
        $this->addReference('url-content-3', $content3);

        $manager->persist($content1);
        $manager->persist($content2);
        $manager->persist($content3);

        $manager->flush();
    }

    private function createContent($instruction, $url)
    {
        $urlContent = new UrlContent();
        $urlContent->setInstruction($instruction);
        $urlContent->setUrl($url);

        return $urlContent;
    }
}
