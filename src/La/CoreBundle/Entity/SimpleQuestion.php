<?php

namespace La\CoreBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use La\CoreBundle\Visitor\SimpleQuestionVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class SimpleQuestion extends QuestionContent
{
    public function init($em = null)
    {
        $answer1 = new Answer();
        $answer2 = new Answer();
        $answer1->setQuestion($this);
        $answer2->setQuestion($this);
        $em->persist($this);
        $em->persist($answer1);
        $em->persist($answer2);
        $em->flush();
    }

    public function accept(VisitorInterface $visitor)
    {
        if ($visitor instanceof SimpleQuestionVisitorInterface) {
            return $visitor->visitSimpleQuestion($this);
        }

        return null;
    }
}
