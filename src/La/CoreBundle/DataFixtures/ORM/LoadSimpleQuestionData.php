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
    public function load(ObjectManager $manager)
    {
        $question1 = $this->createQuestion('Old and new', 'Which one of these processes is the most modern?');
        $question2 = $this->createQuestion('Select the correct statement', "In Mihai Fagadar's PhD thesis, is foreground extraction defined");
        $question3 = $this->createQuestion('Select the answers most compatible with your intuitive view.', "What is your first priority in change management (assuming it is roughly defined what kind of a change needs to be effected)?\n\nPlease us this for inspiration (but only if you need it!): https://www.google.be/search?q=change+management");

        $this->addReference('simple-question-1', $question1);
        $this->addReference('simple-question-2', $question2);
        $this->addReference('simple-question-3', $question3);

        $manager->persist($question1);
        $manager->persist($question2);
        $manager->persist($question3);

        $manager->flush();
    }

    private function createQuestion($instruction, $question)
    {
        $simpleQuestion = new SimpleQuestion();
        $simpleQuestion->setInstruction($instruction);
        $simpleQuestion->setQuestion($question);

        return $simpleQuestion;
    }
}
