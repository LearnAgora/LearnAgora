<?php

namespace La\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\MultipleChoiceQuestion;

class LoadMultipleChoiceQuestionData extends AbstractFixture implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        // 12
        $question1 = $this->createQuestion('Mark every statement that seems correct to you.', 'The following statements link a name with a concept in visual perception, pick the ones that are correct.');
        // 19
        $question2 = $this->createQuestion('Check out the tweet, then answer the question.', 'Who is at fault here (https://twitter.com/vinniequinn/status/506116655983099904)?');
        // 23
        $question3 = $this->createQuestion('Select as many as you think are correct.', 'Name the most valuable attacking players of Chelsea in season 2014-2015.');

        $this->addReference('multiple-choice-question-1', $question1);
        $this->addReference('multiple-choice-question-2', $question2);
        $this->addReference('multiple-choice-question-3', $question3);

        $manager->persist($question1);
        $manager->persist($question2);
        $manager->persist($question3);

        $manager->flush();
    }

    private function createQuestion($instruction, $question)
    {
        $multipleChoiceQuestion = new MultipleChoiceQuestion();
        $multipleChoiceQuestion->setInstruction($instruction);
        $multipleChoiceQuestion->setQuestion($question);

        return $multipleChoiceQuestion;
    }
}
