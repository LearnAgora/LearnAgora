<?php

namespace La\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\Answer;

class LoadAnswerData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return array(
            'La\CoreBundle\DataFixtures\ORM\LoadSimpleQuestionData',
            'La\CoreBundle\DataFixtures\ORM\LoadMultipleChoiceQuestionData',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $answer1 = $this->createAnswer('simple-question-1', 'Agile');
        $answer2 = $this->createAnswer('simple-question-1', 'Waterfall');
        $answer3 = $this->createAnswer('simple-question-2', 'arbitrarily by the application designer?');
        $answer4 = $this->createAnswer('simple-question-2', 'based on objective movement in the image?');
        $answer5 = $this->createAnswer('simple-question-3', 'Settle on a process with clear inputs/outputs and intermediate stages.');
        $answer6 = $this->createAnswer('simple-question-3', 'Talk to the people and understand how they see themselves in this change.');
        $answer7 = $this->createAnswer('multiple-choice-question-1', 'David Marr is a Gestalt thinker.');
        $answer8 = $this->createAnswer('multiple-choice-question-1', 'The ecological view is represented by JJ. Gibson.');
        $answer9 = $this->createAnswer('multiple-choice-question-1', 'The famous drawings of Escher can be related to Gestalt theory.');
        $answer10 = $this->createAnswer('multiple-choice-question-1', 'Mihai Fagadar is known for his criticism of the static observer.');
        $answer11 = $this->createAnswer('multiple-choice-question-1', "Foreground extraction as per Fagadar's work is key to developing immersive approaches.");

        $this->addReference('answer-1', $answer1);
        $this->addReference('answer-2', $answer2);
        $this->addReference('answer-3', $answer3);
        $this->addReference('answer-4', $answer4);
        $this->addReference('answer-5', $answer5);
        $this->addReference('answer-6', $answer6);
        $this->addReference('answer-7', $answer7);
        $this->addReference('answer-8', $answer8);
        $this->addReference('answer-9', $answer9);
        $this->addReference('answer-10', $answer10);
        $this->addReference('answer-11', $answer11);

        $manager->persist($answer1);
        $manager->persist($answer2);
        $manager->persist($answer3);
        $manager->persist($answer4);
        $manager->persist($answer5);
        $manager->persist($answer6);
        $manager->persist($answer7);
        $manager->persist($answer8);
        $manager->persist($answer9);
        $manager->persist($answer10);
        $manager->persist($answer11);

        $manager->flush();
    }

    private function createAnswer($questionReference, $content)
    {
        $answer = new Answer();
        $answer->setQuestion($this->getReference($questionReference));
        $answer->setAnswer($content);

        return $answer;
    }
}
