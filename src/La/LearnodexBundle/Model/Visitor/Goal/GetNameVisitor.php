<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\LearnodexBundle\Model\Visitor\Goal;

use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\AgoraGoal;
use La\CoreBundle\Entity\HtmlContent;
use La\CoreBundle\Entity\MultipleChoiceQuestion;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\PersonaGoal;
use La\CoreBundle\Entity\SimpleQuestion;
use La\CoreBundle\Entity\UrlContent;
use La\CoreBundle\Visitor\AgoraGoalVisitorInterface;
use La\CoreBundle\Visitor\MultipleChoiceQuestionVisitorInterface;
use La\CoreBundle\Visitor\PersonaGoalVisitorInterface;
use La\CoreBundle\Visitor\SimpleQuestionVisitorInterface;
use La\CoreBundle\Visitor\ActionVisitorInterface;
use La\CoreBundle\Visitor\AgoraVisitorInterface;
use La\CoreBundle\Visitor\HtmlContentVisitorInterface;
use La\CoreBundle\Visitor\ObjectiveVisitorInterface;
use La\CoreBundle\Visitor\UrlContentVisitorInterface;
use La\CoreBundle\Visitor\VisitorInterface;
use La\LearnodexBundle\Forms\HtmlContentType;
use La\LearnodexBundle\Forms\MultipleChoiceQuestionType;
use La\LearnodexBundle\Forms\SimpleQuestionType;
use La\LearnodexBundle\Forms\SimpleUrlQuestionType;
use La\LearnodexBundle\Forms\UrlContentType;


class GetNameVisitor implements
    VisitorInterface,
    AgoraGoalVisitorInterface,
    PersonaGoalVisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visitAgoraGoal(AgoraGoal $goal)
    {
        return $goal->getAgora()->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function visitPersonaGoal(PersonaGoal $goal)
    {
        return $goal->getPersona()->getUser()->getUsername();
    }

}
