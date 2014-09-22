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
    function getDependencies()
    {
        return array(
            'La\CoreBundle\DataFixtures\ORM\LoadSimpleQuestionData',
        );
    }

    /**
     * {@inheritdoc}
     */
    function load(ObjectManager $manager)
    {
        $answer1 = $this->createAnswer('simple-question-1', 'Agile');
        $answer2 = $this->createAnswer('simple-question-1', 'Waterfall');
        $answer3 = $this->createAnswer('simple-question-2', 'arbitrarily by the application designer?');
        $answer4 = $this->createAnswer('simple-question-2', 'based on objective movement in the image?');
        $answer5 = $this->createAnswer('simple-question-3', 'Settle on a process with clear inputs/outputs and intermediate stages.');
        $answer6 = $this->createAnswer('simple-question-3', 'Talk to the people and understand how they see themselves in this change.');

        $this->addReference('answer-1', $answer1);
        $this->addReference('answer-2', $answer2);
        $this->addReference('answer-3', $answer3);
        $this->addReference('answer-4', $answer4);
        $this->addReference('answer-5', $answer5);
        $this->addReference('answer-6', $answer6);

        $manager->persist($answer1);
        $manager->persist($answer2);
        $manager->persist($answer3);
        $manager->persist($answer4);
        $manager->persist($answer5);
        $manager->persist($answer6);

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
