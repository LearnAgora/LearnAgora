<?php

namespace La\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\SimpleQuestion;

class LoadSimpleQuestionData extends AbstractFixture implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    function load(ObjectManager $manager)
    {
        $simpleQuestion1 = $this->createSimpleQuestion('Old and new', 'Which one of these processes is the most modern?');
        $simpleQuestion2 = $this->createSimpleQuestion('Select the correct statement', "In Mihai Fagadar's PhD thesis, is foreground extraction defined");
        $simpleQuestion3 = $this->createSimpleQuestion('Select the answers most compatible with your intuitive view.', "What is your first priority in change management (assuming it is roughly defined what kind of a change needs to be effected)?\n\nPlease us this for inspiration (but only if you need it!): https://www.google.be/search?q=change+management");

        $this->addReference('simple-question-1', $simpleQuestion1);
        $this->addReference('simple-question-2', $simpleQuestion2);
        $this->addReference('simple-question-3', $simpleQuestion3);

        $manager->persist($simpleQuestion1);
        $manager->persist($simpleQuestion2);
        $manager->persist($simpleQuestion3);

        $manager->flush();
    }

    private function createSimpleQuestion($instruction, $question)
    {
        $simpleQuestion = new SimpleQuestion();
        $simpleQuestion->setInstruction($instruction);
        $simpleQuestion->setQuestion($question);

        return $simpleQuestion;
    }
}
